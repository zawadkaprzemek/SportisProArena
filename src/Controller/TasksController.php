<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PlayerManagerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 /**
 * @IsGranted("ROLE_USER")
 */
class TasksController extends AbstractController
{
    private PlayerManagerService $playerManagerService;

    public function __construct(PlayerManagerService $playerManager)
    {
        $this->playerManagerService=$playerManager;
    }

    /**
     * @Route("/tasks/completed", name="app_tasks_completed")
     */
    public function tasksCompleted(): Response
    {
        return $this->render('tasks/index.html.twig', [
            'title'=>'Zadania zrealizowane'
        ]);
    }

    /**
     * @Route("/tasks/to_do", name="app_tasks_to_do")
     */
    public function tasksToDo(): Response
    {
        return $this->render('tasks/index.html.twig', [
            'title'=>'Zadania do zrealizowania'
        ]);
    }

    /**
     * @Route("/profile/player/{id}/tasks/completed", name="app_profile_player_tasks_completed")
     */
    public function playerTaskCompleted(User $player): Response
    {
        $user=$this->getUser();
        $assigned=$this->playerManagerService->isPlayerAssignedToManager($player,$user);
        if($player->getUserType()!==User::PLAYER_TYPE|| $user->getUserType()!==User::MANAGER_TYPE||!$assigned)
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('tasks/index.html.twig', [
            'title'=>'Zadania zrealizowane',
            'data'=>[]
        ]);
    }

    /**
     * @Route("/profile/player/{id}/tasks/to_do", name="app_profile_player_tasks_to_do")
     */
    public function playerTaskToDo(User $player): Response
    {
        $user=$this->getUser();
        $assigned=$this->playerManagerService->isPlayerAssignedToManager($player,$user);
        if($player->getUserType()!==User::PLAYER_TYPE|| $user->getUserType()!==User::MANAGER_TYPE||!$assigned)
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('tasks/index.html.twig', [
            'title'=>'Zadania do zrealizowania',
            'data'=>[]
        ]);
    }
}
