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
class RankingController extends AbstractController
{

    private PlayerManagerService $playerManagerService;

    public function __construct(PlayerManagerService $playerManager)
    {
        $this->playerManagerService=$playerManager;
    }

    /**
     * @Route("ranking/list", name="app_ranking_list")
     */
    public function rankingList(): Response
    {
        $user=$this->getUser();
        return $this->render('ranking/index.html.twig', [
            'title'=>'Lista rankingów',
            'user'=>$user,
            'data'=>[]
        ]);
    }

    /**
     * @Route("competitions/list", name="app_competitions_list")
     */
    public function competitionsList(): Response
    {
        $user=$this->getUser();
        return $this->render('competitions/list.html.twig', [
            'title'=>'Lista konkursów',
            'data'=>[],
            'user'=>$user
        ]);
    }


    /**
     * @Route("profile/player/{id}/rankings", name="app_profile_player_rankings")
     */
    public function playerRankings(User $player): Response
    {
        $user=$this->getUser();
        $assigned=$this->playerManagerService->isPlayerAssignedToManager($player,$user);
        if($player->getUserType()!==User::PLAYER_TYPE|| $user->getUserType()!==User::MANAGER_TYPE||!$assigned)
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('ranking/index.html.twig', [
            'title'=>'Lista rankingów zawodnika',
            'data'=>[],
            'user'=>$player
        ]);
    }

    /**
     * @Route("profile/player/{id}/competitions", name="app_profile_player_competitions")
     */
    public function playerCompetitions(User $player): Response
    {
        $user=$this->getUser();
        $assigned=$this->playerManagerService->isPlayerAssignedToManager($player,$user);
        if($player->getUserType()!==User::PLAYER_TYPE|| $user->getUserType()!==User::MANAGER_TYPE||!$assigned)
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('ranking/index.html.twig', [
            'title'=>'Lista konkursów zawodnika',
            'data'=>[],
            'user'=>$player
        ]);
    }
}
