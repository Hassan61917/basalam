<?php

namespace App\Enums;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Question;
use App\Models\Review;

enum LikeableModel: string
{
    case Product = Product::class;
    case review = Review::class;
    case question = Question::class;
    case post = Post::class;
    case comment = Comment::class;
}
