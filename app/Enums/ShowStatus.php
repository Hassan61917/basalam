<?php

namespace App\Enums;

enum ShowStatus: string
{
    case Waiting = 'Waiting';
    case Showing = 'Showing';
    case Completed = "Completed";
}
