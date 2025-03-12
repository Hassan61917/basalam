<?php

namespace App\Enums;

enum OrderItemStatus: string
{
    case Waiting = "Waiting";
    case Processed = "Processed";
    case Accepted = "Accepted";
    case Shipped = "Shipped";
    case Cancelled = "Cancelled";
    case Completed = "Completed";
}
