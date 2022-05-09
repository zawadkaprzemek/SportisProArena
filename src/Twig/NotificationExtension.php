<?php

namespace App\Twig;

use App\Entity\User;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Entity\Notification;
use App\Entity\PlayerManager;
use App\Service\NotificationService;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;

class NotificationExtension extends AbstractExtension
{
    private NotificationService $notificationService;
    private EntityManagerInterface $em;

    public function __construct(NotificationService $notificationService,EntityManagerInterface $em)
    {
        $this->notificationService=$notificationService;
        $this->em=$em;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('notificationText', [$this, 'notificationText']),
            new TwigFilter('requestAnswered', [$this, 'requestAnswered']),
            new TwigFilter('unreadNotifications', [$this, 'unreadNotifications']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('notificationText', [$this, 'notificationText']),
            new TwigFunction('requestAnswered', [$this, 'requestAnswered']),
            new TwigFunction('unreadNotifications', [$this, 'unreadNotifications']),
        ];
    }

    public function notificationText(Notification $notification)
    {
        return $this->notificationService->notificationText($notification->getType(),$notification->getFromWho());
    }

    public function requestAnswered(int $requestId)
    {
        $request=$this->em->getRepository(PlayerManager::class)->find($requestId);
        return ($request==null || $request->isAccepted());
    }

    public function unreadNotifications(User $user)
    {
        return $this->notificationService->unreadNotifications($user,true);
    }
}
