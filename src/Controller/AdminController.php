<?php

namespace App\Controller;

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
     * @Route("/", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/users/managers", name="app_admin_managers_list")
     */
    public function managersList()
    {
        $em=$this->getDoctrine()->getManager();
        $users=$em->getRepository(User::class)->findBy(['userType'=>User::MANAGER_TYPE],['createdAt'=>'DESC']);

        return $this->render('admin/users.html.twig',[
           'users'=>$users,
           'title'=>'Lista managerów'
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
}
