<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Open = 'open';
    case In_Progress = 'in_progress';
    case Cancelled = 'cancelled';
    case Blocked = 'blocked';
    case Completed = 'completed';
}
