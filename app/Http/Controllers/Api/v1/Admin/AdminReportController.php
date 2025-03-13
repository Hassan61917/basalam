<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Common\CommonReportRequest;
use App\Http\Resources\v1\ReportResource;
use App\Models\Report;
use App\ModelServices\Support\ReportService;
use Illuminate\Http\JsonResponse;

class AdminReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $reports = $this->reportService->getReports(["category"]);
        return $this->ok($this->paginate($reports));
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report): JsonResponse
    {
        $report->load(["category", "user", "subject"]);
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
