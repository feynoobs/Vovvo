<?php

namespace App\Enums;

enum Period: string
{
    case OneDay = 'd';
    case OneWeek = 'w';
    case OneMonth = 'm';
    case OneYear = 'y';
}
