<?php

namespace App\Controller;

use App\Entity\Notification;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Unique;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="app_home")
     */
    public function home():Response
    {
        return $this->render('default/index.html.twig',[
            
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
