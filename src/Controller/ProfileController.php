<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\PlayerManager;
use App\Form\EditProfileType;
use App\Form\PasswordChangeType;
use App\Service\PlayerManagerService;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/profile")
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{

    private PlayerManagerService $playerManagerService;

    public function __construct(PlayerManagerService $playerManager)
    {
        $this->playerManagerService=$playerManager;
    }

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
        /** @var User $user */
        $user=$this->getUser();
        $currentClub=$user->getClub();
        $form=$this->createForm(EditProfileType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            if($user->getClub()!==$currentClub)
            {
                $repo=$em->getRepository(PlayerManager::class);
                if($user->getUserType()==User::MANAGER_TYPE)
                {
                    $repo->updatePlayersAssignedToManager($user);
                }
                if($user->getUserType()==User::PLAYER_TYPE)
                {
                    $repo->updateManagersAssignedToPlayer($user);
                }
            }

            $this->addFlash('success','Zapisano zmiany');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user
        ]);
    }

    /**
     * @Route("/change_password", name="app_profile_change_password")
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordEncoder
     * @return RedirectResponse|Response
     */
    public function changePassword(Request $request,UserPasswordHasherInterface $passwordEncoder)
    {
        /** @var User $user */
        $user=$this->getUser();
        $form=$this->createForm(PasswordChangeType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            if(!$passwordEncoder->isPasswordValid($user,$form->get('oldPassword')->getData())){
                $form->get('oldPassword')->addError(new FormError('Wprowadzono złe hasło'));
            }
            if($form->isValid())
            {
                $newpassword=$passwordEncoder->hashPassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($newpassword);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Hasło zostało zmienione');
                return $this->redirectToRoute('app_profile');
            }
        }

        return $this->render('profile/change_password.html.twig',[
           'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/player/{id}/show", name="app_player_profile_show")
     */
    public function showPlayerProfile(User $player)
    {
        $user=$this->getUser();
        if($player->getUserType()!==User::PLAYER_TYPE|| $user->getUserType()!==User::MANAGER_TYPE)
        {
            return $this->redirectToRoute('app_home');
        }
        $assigned=$this->playerManagerService->isPlayerAssignedToManager($player,$user);
        

        return $this->render('profile/show_player_profile.html.twig',[
            'player'=>$player,
            'assigned'=>$assigned
        ]);
    }


    /**
     * //@Route("/player/{id}/card", name="app_player_card_show")
     */
    /*public function showPlayerCard(User $player)
    {
        $user=$this->getUser();
        if($player->getUserType()!==User::PLAYER_TYPE|| $user->getUserType()!==User::MANAGER_TYPE)
        {
            return $this->redirectToRoute('app_home');
        }
        $assigned=$this->playerManagerService->isPlayerAssignedToManager($player,$user);
        

        return $this->render('profile/player_card.html.twig',[
            'player'=>$player,
            'assigned'=>$assigned
        ]);
    }*/
}
