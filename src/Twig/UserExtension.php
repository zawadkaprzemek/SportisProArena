<?php

namespace App\Twig;

use App\Entity\User;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class UserExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('player_type', [$this, 'isPlayerType']),
            new TwigFilter('manager_type', [$this, 'isManagerType']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('player_type', [$this, 'isPlayerType']),
            new TwigFunction('manager_type', [$this, 'isManagerType']),
        ];
    }

    public function isPlayerType($value):bool
    {
        return $value===User::PLAYER_TYPE;
    }

    public function isManagerType($value):bool
    {
        return $value===User::MANAGER_TYPE;
    }
}
