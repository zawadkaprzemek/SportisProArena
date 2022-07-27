<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Notification;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService{

    const PLAYER_ASSIGN_REQUEST='player_assign_request';
    const PLAYER_ASSIGN_ACCEPT='player_assing_accept';
    const PLAYER_ASSIGN_REJECT='player_assign_reject';
    const ASSIGNED_BY_MANAGER_TO_SESSION='assigned_by_manager_to_session';

    const NOTIFICATION_TEXTS=[
        self::PLAYER_ASSIGN_REQUEST =>"Trener {user} chce przypisać Cię jako swojego zawodnika",
        self::PLAYER_ASSIGN_ACCEPT =>"Zawodnik {user} zaakceptował Twoją prośbę o przypisanie",
        self::PLAYER_ASSIGN_REJECT =>"Zawodnik {user} odrzucił Twoją prośbę o przypisanie",
        self::ASSIGNED_BY_MANAGER_TO_SESSION => "Trener {user} przypisał Ci nową sesję treningową"
    ];

    private EntityManagerInterface $em;


    public function __construct(EntityManagerInterface $entityManager,MailerService $mailer)
    {
        $this->em=$entityManager;
        $this->mailer=$mailer;
    }


    public function addNotification(User $user, string $type,int $from=0,?int $referenceId=null)
    {
        $notification=new Notification();
        $notification->setUser($user)
            ->setType($type)
            ->setFromWho($from)
            ->setReferenceId($referenceId)
            ;

        $this->em->persist($notification);
        $this->em->flush();

        $this->mailer->sendMailGmail(
            $user->getEmail(),
            'Nowe powiadomienie',
            'Nowe powiadomienie w Sportis Pro Arena'
        );
    }

    public function notificationText(string $type,int $from)
    {
        if($from==0)
        {
            
        }
        $user=$this->em->getRepository(User::class)->find($from);
        $text=str_replace('{user}',$user->getFullName(),self::NOTIFICATION_TEXTS[$type]);

        return $text;
    }

    public function unreadNotifications(User $user,bool $count=false)
    {
        $repo=$this->em->getRepository(Notification::class);
        $notifications=$repo->getUserNotReadedNotifications($user);
        if($count)
        {
            return sizeof($notifications);
        }
        return $notifications;
    }
}