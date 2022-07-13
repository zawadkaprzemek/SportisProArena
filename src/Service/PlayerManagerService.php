<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\PlayerManager;
use Doctrine\ORM\EntityManagerInterface;

class PlayerManagerService{


    private EntityManagerInterface $em;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em=$entityManager;
    }


    public function isPlayerAssignedToManager(User $player,User $manager)
    {
        /** @var PlayerManagerRepository $repo */
        $repo=$this->em->getRepository(PlayerManager::class);
        $assign=$repo->isPlayerAssignedToManager($manager,$player);

        return ($assign!==null && $assign->isAccepted() && $assign->getActive());
    }
    

    public function getManagerPlayers(User $manager, ?int $count=null)
    {
        /** @var PlayerManagerRepository $repo */
        $repo=$this->em->getRepository(PlayerManager::class);
        $assigns=$repo->getManagerPlayers($manager,$count);

        $players=[];
        foreach($assigns as $assign)
        {
            $players[]=$assign->getPlayer();
        }
        return $players;
    }
}