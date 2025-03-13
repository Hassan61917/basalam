<?php

namespace App\Listeners;

use App\Enums\BanRule;
use App\Events\ReportWasCreated;
use App\Models\Ban;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\User;
use App\ModelServices\User\BanService;

class CheckBanUser
{
    public function __construct(
        private BanService $banService
    )
    {
    }

    /**
     * Handle the event.
     */
    public function handle(ReportWasCreated $event): void
    {
        $report = $event->report;
        $user = $this->getUser($report);
        $category = $report->category;
        if ($this->shouldBeBanned($category, $user->id)) {
            $this->banUser($user);
            $report->subject()->delete();
        }
    }

    private function banUser(User $user): void
    {
        $reason = $this->getReason();
        $this->banService->banUser(auth()->user(),$user, [
            "user_id" => $user->id,
            "reason" => BanRule::Report->value,
        ]);
    }

    private function shouldBeBanned(ReportCategory $category, int $userId): bool
    {
        $rule = $category->rule;
        return $category->reports()
            ->with("subject")
            ->between(now()->subHours($rule->duration), now())
            ->where("subject.user_id", $userId)
            ->where(fn($query) => $query->count() >= $rule->count)
            ->exists();
    }

    private function getReason(): Ban
    {
        return Ban::where("reason", "like", "%report%")->first();
    }

    private function getUser(Report $report): User
    {
        return User::find($report->subject->user_id);
    }
}
