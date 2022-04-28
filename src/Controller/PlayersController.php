<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\PlayerManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/players")
 * @IsGranted("ROLE_USER")
 */
class PlayersController extends AbstractController
{
    /**
     * @Route("/my_players", name="app_my_players")
     */
    public function myPlayers(): Response
    {
        $user=$this->getUser();

        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository(PlayerManager::class);

        $players=$repo->getManagerPlayers($user);
        return $this->render('players/my_players.html.twig', [
            'players' => $players,
        ]);
    }

    /**
     * @Route("/list", name="app_players_list")
     * Wyświetla tylko listę zawodników z klubu trenera
     */
    public function playersList()
    {
        /** @var User $user */
        $user=$this->getUser();

        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository(User::class);
        $players=$repo->findUsersFromClub(User::PLAYER_TYPE,$user->getClub());

        return $this->render('players/list.html.twig',[
            'players'=>$players,
            'club'=>$user->getClub()
        ]);
    }
}
