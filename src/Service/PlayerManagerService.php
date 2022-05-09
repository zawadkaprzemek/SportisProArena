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
        $repo=$this->em->getRepository(PlayerManager::class);
        $assign=$repo->isPlayerAssignedToManager($manager,$player);

        return ($assign!==null && $assign->isAccepted() && $assign->getActive());
    }
    
}