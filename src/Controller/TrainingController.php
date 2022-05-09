<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Arena;
use App\Entity\TrainingSession;
use App\Form\AssignPlayerToSessionType;
use App\Service\NotificationService;
use App\Service\TrainingService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/training")
 * @IsGranted("ROLE_USER")
 */
class TrainingController extends AbstractController
{
    private TrainingService $trainingService;
    private NotificationService $notificationService;

    public function __construct(TrainingService $trainingService,NotificationService $notificationService)
    {
        $this->trainingService=$trainingService;
        $this->notificationService=$notificationService;
    }

    /**
     * @Route("/reserve", name="app_training_reserve_step1")
     */
    public function arenaList(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $arenas=$em->getRepository(Arena::class)->findAll();

        return $this->render('training/step1.html.twig', [
            'arenas' => $arenas,
        ]);
    }

    /**
     * @Route("/reserve/arena/{id}", name="app_training_reserve_choose_dates")
     */
    public function chooseDates(Arena $arena,Request $request)
    {
        $user=$this->getUser();
        $reqDate=$request->query->get("date");
        $start=new \DateTime("first day of this month");
        if($reqDate==null)
        {
            $dateStart=$start;
        }else{
            $arr=explode("-",$reqDate);
            if(sizeof($arr)!==2)
            {
                //wrong date format, set to currentDate
                $dateStart=$start;
            }else{
                try{
                    $dateStart=new \DateTime($arr[0]."-".$arr[1]."-1");
                }catch(\Exception $e)
                {
                    $dateStart=$start;
                }
            }
        }

        $dateEnd=(clone $dateStart)->modify('last day of this month');
        

        return $this->render('training/choose_dates.html.twig', [
            'arena' => $arena,
            'date'=>[
                'start'=>$dateStart,
                'end'=>$dateEnd
                ]
        ]);
    }


    /**
     * @Route("/arena/{id}/get_free_dates", name="app_training_arena_get_free_dates", methods={"GET"})
     */
    public function getEmptyTrainingDates(Arena $arena,Request $request)
    {
        $date=$request->query->get("date");
        if($date==null)
        {
            $date=(new \DateTime())->format('Y-m-d');
        }
        $slots=$this->trainingService->generateFreeSlotsArray($arena,$date);
        return new JsonResponse([
            'slots'=>$slots
            ]);

    }

    /**
     * @Route("/reserve_slots", name="app_training_reserve_slots", methods={"POST"})
     */
    public function reserveSlots(Request $request)
    {
        $content=json_decode($request->getContent(),true);
        $user=$this->getUser();
        if($user->getTrainingUnits()<sizeof($content['training_dates']))
        {
            return new JsonResponse([
                'status'=>'danger',
                'message'=>'Posiadasz nie wystarczającą ilość jednostek treningowych'
            ]);
        }

        $sessions=$this->trainingService->reserveSlots($content,$user);

        if(sizeof($sessions)>0)
        {
            $status='success';
            $message="Zarezerwowano ".sizeof($sessions)." sesje treningowe";
        }else{
            $status='warning';
            $message="Wybrane terminy są już zarezerwowane";
        }

        return new JsonResponse([
            'status'=>$status,
            'message'=>$message,
            'sessions'=>$sessions,
            'units'=>$user->getTrainingUnits()
        ]);
    }


    /**
     * @Route("/reserved", name="app_training_reserved")
     */
    public function reservedSessions(Request $request)
    {
        $user=$this->getUser();
        $em=$this->getDoctrine()->getManager();
        $sessions=$em->getRepository(TrainingSession::class)->getUserSessions($user);
        
        return $this->render('training/reserved_sessions.html.twig',[
            'reserved'=>$sessions
        ]);
    }


    /**
     * @Route("/{id}/assign_player", name="app_training_assign_player")
     */
    public function assignPlayerToTraining(TrainingSession $session,Request $request)
    {
        $user=$this->getUser();
        if($user!==$session->getBuyer()||$user->getUserType()!==User::MANAGER_TYPE)
        {
            return $this->redirectToRoute('app_home');
        }

        $form=$this->createForm(AssignPlayerToSessionType::class,$session);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();
            $this->notificationService->addNotification(
                $session->getPlayer(),
                NotificationService::ASSIGNED_BY_MANAGER_TO_SESSION,
                $session->getBuyer()->getId()
            );
            $this->addFlash('success',"Zawodnik został przypisany do sesji treningowej");
            return $this->redirectToRoute('app_training_reserved');
            
        }

        return $this->render('training/assign_player.html.twig',[
            'session'=>$session,
            'form'=>$form->createView()
        ]);
    }
}
