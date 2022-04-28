<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/payment")
 * @IsGranted("ROLE_USER")
 */
class PaymentController extends AbstractController
{
    /**
     * @Route("/buy_units", name="app_payment_buy_units")
     */
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            
        ]);
    }

    /**
     * @Route("/add_units", methods={"POST"}, name="app_payment_add_units")
     */
    public function addUnits(Request $request)
    {
        $content=json_decode($request->getContent(),true);
        /** @var $user User */
        $user=$this->getUser();
        $user->setTrainingUnits($user->getTrainingUnits()+(int)$content['tokens']);
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new JsonResponse(['status'=>'success']);
    }
}
