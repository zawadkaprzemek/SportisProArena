<?php

namespace App\Controller;

use App\Entity\Arena;
use App\Entity\Club;
use App\Entity\User;
use App\Form\AdminUserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/users/managers", name="app_admin_managers_list")
     */
    public function managersList(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $users=$em->getRepository(User::class)->findBy(['userType'=>User::MANAGER_TYPE],['createdAt'=>'DESC']);

        return $this->render('admin/users.html.twig',[
           'users'=>$users,
           'title'=>'Lista managerów',
            'show_club'=>true
        ]);
    }

    /**
     * @Route("/users/players", name="app_admin_players_list")
     */
    public function playersList(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $users=$em->getRepository(User::class)->findBy(['userType'=>User::PLAYER_TYPE],['createdAt'=>'DESC']);

        return $this->render('admin/users.html.twig',[
            'users'=>$users,
            'title'=>'Lista zawodników',
            'show_club'=>true
        ]);
    }

    /**
     * @Route("/users/{id}/edit", name="app_admin_user_edit")
     */
    public function editUser(Request $request,User $user)
    {
        $form=$this->createForm(AdminUserEditType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $roles=$user->getRoles();
            if($form->get('expert_manager')->getData())
            {
                $roles[]="ROLE_MANAGER_EXPERT";
            }else{
                if (($key = array_search("ROLE_MANAGER_EXPERT", $roles)) !== false) {
                    unset($roles[$key]);
                }
            }
            $user->setRoles(array_unique($roles));
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_admin_managers_list');
        }

        return $this->render('admin/edit_user.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user
        ]);
    }

    /**
     * @Route("/clubs", name="app_admin_clubs_list")
     */
    public function clubList(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $clubs=$em->getRepository(Club::class)->findAll();

        return $this->render('admin/clubs.html.twig',[
            'clubs'=>$clubs,
            'title'=>'Lista klubów'
        ]);
    }

    /**
     * @Route("/arenas", name="app_admin_arenas_list")
     */
    public function arenasList(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $arenas=$em->getRepository(Arena::class)->findAll();

        return $this->render('admin/arenas.html.twig',[
            'arenas'=>$arenas,
            'title'=>'Lista arena'
        ]);
    }
}
