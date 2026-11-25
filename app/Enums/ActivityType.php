<?php

namespace App\Enums;

enum ActivityType: string
{
    case Time = 'time';
    case Note = 'note';
    case Complete = 'complete';
    case Skip = 'skip';
    case Reopen = 'reopen';
}
