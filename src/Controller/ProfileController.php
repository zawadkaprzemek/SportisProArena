<?php

namespace App\Controller;

use App\Form\EditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/profile")
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="app_profile")
     */
    public function index(): Response
    {
        $user=$this->getUser();
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'title'=>'Mój profil'
        ]);
    }

    /**
     * @Route("/edit", name="app_profile_edit")
     */
    public function edit(Request $request):Response
    {
        $user=$this->getUser();
        $form=$this->createForm(EditProfileType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','Zapisano zmiany');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user
        ]);
    }
}
