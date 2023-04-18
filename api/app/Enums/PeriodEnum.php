<?php

namespace App\Enums;

enum PeriodEnum: string
{
    case DAY = 'Day';
    case WEEK = 'Week';
    case MONTH = 'Month';
    case YEAR = 'Year';
}
