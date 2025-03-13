<?php

namespace App\ModelServices\Support;

use App\Events\ReportWasCreated;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ReportService
{
    public function getReportsFor(User $user, array $relations = []): Builder
    {
        return $user->reports()->with($relations);
    }

    public function getReports(array $relations = []): Builder
    {
        return Report::query()->with($relations);
    }

    public function make(User $user, array $data): Report
    {
        $report = $user->reports()->create($data);
        ReportWasCreated::dispatch($report);
        return $report;
    }
}
