<?php

namespace App\Enums;

use App\Models\Post;
use App\Models\Product;

enum VisitableModel: string
{
    case item = Product::class;
    case post = Post::class;
}
