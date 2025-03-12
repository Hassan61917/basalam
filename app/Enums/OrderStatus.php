<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Draft = "Draft";
    case Paid = "Paid";
}
