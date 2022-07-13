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
            new TwigFilter('status_icon', [$this, 'statusIcon']),
            new TwigFilter('status_icon_class', [$this, 'statusIconClass']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('status_name', [$this, 'statusName']),
            new TwigFunction('status_icon', [$this, 'statusIcon']),
            new TwigFunction('status_icon_class', [$this, 'statusIconClass']),
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

    public function statusIcon($value)
    {
        switch($value)
        {
            case -1:
                $icon="icons/exclamation.svg";
                break;
            case 0: 
                $icon="icons/calendar.svg";
                break;
            case 1:
                $icon="icons/check.svg";
                break;
            default:
                $icon="";
            break;
        }

        return $icon;
    }

    public function statusIconClass($value)
    {
        switch($value)
        {
            case -1:
                $class="missed";
                break;
            case 0: 
                $class="to-do";
                break;
            case 1:
                $class="done";
                break;
            default:
                $class="";
            break;
        }

        return $class;
    }
}
