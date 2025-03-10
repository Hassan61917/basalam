<?php

namespace App\Enums;

enum ShopStatus: string
{
    case Draft = "Draft";
    case Opened = "Opened";
    case InProcess = "InProcess";
    case Closed = "Closed";
    case Suspend = "Suspend";
}
