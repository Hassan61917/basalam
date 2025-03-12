<?php

namespace App\ModelServices\Social;

use App\Models\User;
use App\Models\UserBlock;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlockService
{
    public function getBlocksFor(User $user,array $relations = []): HasMany
    {
        return $user->blocks()->withFilters()->with($relations);
    }

    public function block(User $user, array $data): UserBlock
    {
        return $user->blocks()->create($data);
    }

    public function isBlocked(User $user, User $block): bool
    {
        return $user->blocks()->where("block_id", $block->id)->exists();
    }

    public function unblock(User $user, User $block): void
    {
        $user->blocks()->where("block_id", $block->id)->delete();
    }
}
