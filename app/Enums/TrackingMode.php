<?php

namespace App\Enums;

enum TrackingMode: string
{
    case Checkbox = 'checkbox';
    case Numeric = 'numeric';
    case Timed = 'timed';
    case Checklist = 'checklist';
}
