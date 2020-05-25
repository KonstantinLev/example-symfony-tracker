<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DayTitleExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('dayTitle', [$this, 'dayTitle'])
        ];
    }

    public function dayTitle(\DateTimeImmutable $date): string
    {
        $title = '';
        $day = $date->format('d');
        switch ($date->format('w')) {
            case 0:
                $title = 'Вс';
                break;
            case 1:
                $title = 'Пн';
                break;
            case 2:
                $title = 'Вт';
                break;
            case 3:
                $title = 'Ср';
                break;
            case 4:
                $title = 'Чт';
                break;
            case 5:
                $title = 'Пт';
                break;
            case 6:
                $title = 'Сб';
                break;
        }
        return $day.' '.$title;
    }
}