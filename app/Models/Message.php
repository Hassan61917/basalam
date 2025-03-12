<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends AppModel
{
    protected $fillable = [
        "receiver_id", "reply_id", "message", "seen_at",
    ];

    public function scopeChat(Builder $builder, int $senderId, int $receiverId): Builder
    {
        return $builder->where([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId
        ])->orWhere([
            'sender_id' => $receiverId,
            'receiver_id' => $senderId
        ]);
    }

    public function scopeChats(Builder $builder, int $senderId): Builder
    {
        return $builder
            ->where("sender_id", $senderId)
            ->orWhere("receiver_id", $senderId);
    }

    public function casts(): array
    {
        return [
            "seen_at" => "datetime",
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, "sender_id");
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, "receiver_id");
    }

    public function reply(): BelongsTo
    {
        return $this->belongsTo(Message::class, "reply_id");
    }
}
