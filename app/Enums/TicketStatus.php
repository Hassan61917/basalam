<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Waiting = "Waiting";
    case Answered = "Answered";
    case Closed = "Closed";
}
