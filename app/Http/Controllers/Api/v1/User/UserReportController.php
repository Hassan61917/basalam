<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserReportRequest;
use App\Http\Resources\v1\ReportResource;
use App\Models\Report;
use App\ModelServices\Support\ReportService;
use Illuminate\Http\JsonResponse;

class UserReportController extends AuthUserController
{
    protected string $resource = ReportResource::class;

    public function __construct(
        private ReportService $reportService,
    )
    {
    }

    public function index(): JsonResponse
    {
        $reports = $this->reportService->getReportsFor($this->authUser(), ["category"]);
        return $this->ok($this->paginate($reports));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserReportRequest $request): JsonResponse
    {
        $data = $request->all();
        $report = $this->reportService->make($this->authUser(), $data);
        return $this->ok($report);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report): JsonResponse
    {
        $report->load(["category", "subject"]);
        return $this->ok($report);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report): JsonResponse
    {
        $report->delete();
        return $this->deleted();
    }
}
