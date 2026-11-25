<?php

namespace App\Enums;

enum RoadmapKind: string
{
    case Learning = 'learning';
    case Fitness = 'fitness';
    case Project = 'project';
    case Habit = 'habit';
    case Reading = 'reading';
    case Career = 'career';
    case Custom = 'custom';
}
