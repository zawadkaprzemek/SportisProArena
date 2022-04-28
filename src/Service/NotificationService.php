<?php
namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService{

    const PLAYER_ASSIGN_REQUEST='player_assign_request';
    const PLAYER_ASSIGN_ACCEPT='player_assing_accept';
    const PLAYER_ASSIGN_REJECT='player_assign_reject';

    private EntityManagerInterface $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em=$entityManager;
    }


    public function addNotification(User $user, string $type,int $from=0)
    {
        $notification=new Notification();
        $notification->setUser($user)
            ->setType($type)
            ->setFromWho($from)
            ;

        $this->em->persist($notification);
        $this->em->flush();
    }
}