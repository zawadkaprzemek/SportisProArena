<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Notification;
use App\Service\PlayerManagerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Unique;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    private PlayerManagerService $playerManagerService;

    public function __construct(PlayerManagerService $playerManagerService)
    {
        $this->playerManagerService=$playerManagerService;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function home():Response
    {
        $user=$this->getUser();
        $data=[];
        if($user!=null)
        {
            /** @var User $user */
            if($user->getUserType()==User::MANAGER_TYPE)
            {
                $data['players']=$this->playerManagerService->getManagerPlayers($user,12);
            }

        }
        return $this->render('default/index.html.twig',[
            'data'=>$data
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/notifications", name="app_notifications")
     */
    public function notifications()
    {
        $user=$this->getUser();
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository(Notification::class);
        $notifications=$repo->getUserNotifications($user);
        
        return $this->render('default/notifications.html.twig',[
            'notifications'=>$notifications
        ]);
    }

}
