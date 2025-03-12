<?php

namespace App\Models\Trait\Relations;

use App\Models\Message;
use App\Models\Page;
use App\Models\Post;
use App\Models\UserBlock;
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
}
