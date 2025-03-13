<?php

namespace App\Models\Trait\Relations;

use App\Models\Comment;
use App\Models\Follow;
use App\Models\Like;
use App\Models\Message;
use App\Models\Page;
use App\Models\Post;
use App\Models\UserBlock;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserSocialRelations
{
    public function page(): HasOne
    {
        return $this->hasOne(Page::class);
    }
    public function inbox(): HasMany
    {
        return $this->hasMany(Message::class, "receiver_id");
    }

    public function outbox(): HasMany
    {
        return $this->hasMany(Message::class, "sender_id");
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(UserBlock::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function follows(): HasMany
    {
        return $this->hasMany(Follow::class);
    }

    public function followings(): HasMany
    {
        return $this->hasMany(Follow::class, "user_id")
            ->where("accepted", true);
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follow::class, "follow_id")
            ->where("accepted", true);
    }
    public function visits():HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
}
