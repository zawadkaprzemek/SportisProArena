<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DateExtension extends AbstractExtension
{

    const POLISH_MONTHS=[
        1=>'Styczeń',
        2=>'Luty',
        3=>'Marzec',
        4=>'Kwiecień',
        5=>'Maj',
        6=>'Czerwiec',
        7=>'Lipiec',
        8=>'Sierpień',
        9=>'Wrzesień',
        10=>'Październik',
        11=>'Listopad',
        12=>'Grudzień'
    ];

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('polish_month', [$this, 'polishMonth']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('polish_month', [$this, 'polishMonth']),
        ];
    }

    public function polishMonth($value)
    {
        return self::POLISH_MONTHS[(int)$value];
    }
}
