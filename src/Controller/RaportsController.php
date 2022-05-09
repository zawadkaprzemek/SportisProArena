<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\PlayerManagerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RaportsController extends AbstractController
{

    private PlayerManagerService $playerManagerService;

    public function __construct(PlayerManagerService $playerManager)
    {
        $this->playerManagerService=$playerManager;
    }
    /**
     * @Route("/raports", name="app_raports")
     */
    public function index(): Response
    {
        return $this->render('raports/index.html.twig', [
            'title'=>'Lista raportów',
            'raports'=>[]
        ]);
    }

    /**
     * @Route("/profile/player/{id}/raports", name="app_player_raports")
     */
    public function playerRaports(User $player)
    {
        $user=$this->getUser();
        $assigned=$this->playerManagerService->isPlayerAssignedToManager($player,$user);
        if($player->getUserType()!==User::PLAYER_TYPE|| $user->getUserType()!==User::MANAGER_TYPE||!$assigned)
        {
            return $this->redirectToRoute('app_home');
        }
        

        return $this->render('raports/index.html.twig', [
            'title'=>'Lista raportów',
            'raports'=>[]
        ]);
    }
}
