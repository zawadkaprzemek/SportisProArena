<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Notification;
use App\Entity\User;
use App\Entity\PlayerManager;
use App\Service\NotificationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/ajax")
 */
class AjaxController extends AbstractController
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService=$notificationService;
    }


    /**
     * @Route("/add_club", methods={"POST"}, name="app_ajax_add_club")
     */
    public function addClub(Request $request): Response
    {
        $content=json_decode($request->getContent(),true);
        if(array_key_exists('name',$content)&&trim($content['name'])!=''&& strlen(trim($content['name']))>=10)
        {
            $club = new Club();
            $club->setName($content['name']);
            $em=$this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();
            return new JsonResponse([
            'id'=>$club->getId(),
            'name'=>$club->getName()
            ],Response::HTTP_CREATED);    
        }else{
            return new JsonResponse([
                'error'=>'Name too short'
            ],Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/assign_request", methods={"POST"}, name="app_player_assign_request")
     * @IsGranted("ROLE_MANAGER")
     */
    public function assignPlayerRequest(Request $request)
    {
        $content=json_decode($request->getContent(),true);
        $em=$this->getDoctrine()->getManager();
        $manager=$this->getUser();
        $repo=$em->getRepository(PlayerManager::class);
        $player=$em->getRepository(User::class)->find($content['player']);
        if($player==null)
        {
            return new JsonResponse(['status'=>'error','message'=>'Player dont exists']);
        }
        $assign=$repo->isPlayerAssignedToManager($manager,$player);
        if($assign!==null)
        {
            if($assign->isAccepted())
            {
                return new JsonResponse(['status'=>'info','message'=>'Player assigned']);
            }else{
                return new JsonResponse(['status'=>'info','message'=>'Waiting for accept']);
            }
        }

        $assign=new PlayerManager();
        $assign->setPlayer($player)
            ->setManager($manager);
        $em->persist($assign);
        $em->flush();
        $this->notificationService->addNotification($player,NotificationService::PLAYER_ASSIGN_REQUEST,$manager->getId(),$assign->getId());
        return new JsonResponse(['status'=>'success','message'=>'Request send']);
    }

    /**
     * @Route("/answer_request", methods={"POST"}, name="app_player_answer_request")
     * @IsGranted("ROLE_USER")
     */
    public function answerPlayerRequest(Request $request)
    {
        $content=json_decode($request->getContent(),true);
        $em=$this->getDoctrine()->getManager();
        $user=$this->getUser();
        $repo=$em->getRepository(PlayerManager::class);
        
        $request=$repo->findOneBy(['id'=>$content['request'],'player'=>$user]);
        if($request===null)
        {
            return new JsonResponse(['status'=>'error','message'=>'Request dont exists']);
        }
        if((int)$content['answer']===1)
        {
            $request->setAccepted(1)->setActive(1);
            $em->persist($request);
            $this->notificationService->addNotification($request->getManager(),NotificationService::PLAYER_ASSIGN_ACCEPT,$request->getPlayer()->getId());
        }else{
            $em->remove($request);
            $this->notificationService->addNotification($request->getManager(),NotificationService::PLAYER_ASSIGN_REJECT,$request->getPlayer()->getId());
        }
        $em->flush();
        return new JsonResponse(['status'=>'success']);
    }

    /**
     * @Route("/read_notification/{id}", methods={"GET"}, name="app_notification_read")
     * @IsGranted("ROLE_USER")
     */
    public function readNotification(Notification $notification)
    {
        $user=$this->getUser();
        if($notification->getUser()!==$user)
        {
            return new JsonResponse(['status'=>'error']);
        }
        $em=$this->getDoctrine()->getManager();
        $notification->setReaded(1);
        $em->persist($notification);
        $em->flush();
        return new JsonResponse(['status'=>'success']);
    }

}
