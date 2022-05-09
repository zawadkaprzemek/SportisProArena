<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TrainingExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('status_name', [$this, 'statusName']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('status_name', [$this, 'statusName']),
        ];
    }

    public function statusName($value)
    {
        switch($value)
        {
            case -1:
                $status="Przeterminowana";
                break;
            case 0: 
                $status="Do odbycia";
                break;
            case 1:
                $status="Odbyta";
                break;
            default:
                $status="";
            break;
        }
        return $status;
    }
}
