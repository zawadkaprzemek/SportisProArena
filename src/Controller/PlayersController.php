<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\PlayerManager;
use App\Service\PlayerManagerService;
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

    private PlayerManagerService $playerManagerService;

    public function __construct(PlayerManagerService $playerManagerService)
    {
        $this->playerManagerService=$playerManagerService;
    }
    /**
     * @Route("/assigned", name="app_my_players")
     */
    public function myPlayers(): Response
    {
        if(!$this->isGranted("ROLE_MANAGER"))
        {
            return $this->redirectToRoute('app_home');
        }
        $user=$this->getUser();
        

        $players=$this->playerManagerService->getManagerPlayers($user);
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
        if(!$this->isGranted("ROLE_MANAGER"))
        {
            return $this->redirectToRoute('app_home');
        }
        /** @var User $user */
        $user=$this->getUser();

        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository(User::class);
        $players=$repo->findUsersFromClub(User::PLAYER_TYPE,$user->getClub());

        return $this->render('players/list.html.twig',[
            'players'=>$players,
            'club'=>$user->getClub(),
            'with_assign_button'=>false,
            'title'=>'Lista zawodników z mojego klubu'
        ]);
    }

    /**
     * @Route("/possible_to_assign", name="app_players_possible_to_assign")
     * Wyświetla tylko listę zawodników z klubu trenera możliwych do przypisania
     */
    public function possibleToAssign()
    {
        if(!$this->isGranted("ROLE_MANAGER"))
        {
            return $this->redirectToRoute('app_home');
        }
        /** @var User $user */
        $user=$this->getUser();

        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository(User::class);
        $players=$repo->findUsersFromClub(User::PLAYER_TYPE,$user->getClub(),$user);

        return $this->render('players/list.html.twig',[
            'players'=>$players,
            'club'=>$user->getClub(),
            'with_assign_button'=>true,
            'title'=>'Lista zawodników możliwych do przypisania'
        ]);
    }
}
