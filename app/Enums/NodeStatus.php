<?php

namespace App\Enums;

enum NodeStatus: string
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case Done = 'done';
    case Skipped = 'skipped';
}
