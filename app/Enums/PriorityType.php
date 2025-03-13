<?php

namespace App\Enums;

enum PriorityType: string
{
    case Low = "Low";
    case Medium = "Medium";
    case High = "High";
    case Emergency = "Emergency";
}
