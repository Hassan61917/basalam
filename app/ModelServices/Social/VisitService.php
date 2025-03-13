<?php

namespace App\ModelServices\Social;


use App\Models\Interfaces\Visitable;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class VisitService
{

    public function getVisits(array $relations = []): Builder
    {
        return Visit::query()->with($relations);
    }

    public function userVisits(User $user, array $relations = [])
    {
        return $user->visits()->with($relations);
    }

    public function modelVisits(Model $model, array $relations = [])
    {
        return $model->visits()->with($relations);
    }

    public function destroyAllFor(User $user): void
    {
        $user->visits()->delete();
    }

    public function visit(Visitable $model, ?User $user, string $ip): ?Visit
    {
        if ($user) {
            $this->updateVisitsUser($ip, $user);
        }
        if ($this->isVisited($ip, $user, $model)) {
            return null;
        }
        return $model->visits()->create([
            'ip' => $ip,
            "user_id" => $user->id
        ]);
    }

    public function lastVisit(string $ip, ?User $user, Visitable $model)
    {
        return $this->getVisit($ip, $user, $model)->latest()->first();
    }

    public function isVisited(string $ip, ?User $user, Visitable $model): bool
    {
        return $this->getVisit($ip, $user, $model)->exists();
    }

    private function getVisit(string $ip, ?User $user, Visitable $model): MorphMany
    {
        return $model->visits()
            ->where("ip", $ip)
            ->where("user_id", $user?->id);
    }

    private function updateVisitsUser(string $ip, User $user): void
    {
        $visits = Visit::onlyIps($ip);
        if (!$visits->exists()) {
            return;
        }
        $visits->update(["user_id", $user->id]);
    }

}
