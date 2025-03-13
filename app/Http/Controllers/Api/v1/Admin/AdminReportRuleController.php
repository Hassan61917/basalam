<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\AdminReportRuleRequest;
use App\Http\Resources\v1\ReportRuleResource;
use App\Models\ReportRule;
use Illuminate\Http\JsonResponse;

class AdminReportRuleController extends Controller
{
    protected string $resource = ReportRuleResource::class;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $rules = ReportRule::query()->with("category");
        return $this->ok($this->paginate($rules));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminReportRuleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $category = ReportRule::create($data);
        return $this->ok($category);//
    }

    /**
     * Display the specified resource.
     */
    public function show(ReportRule $rule): JsonResponse
    {
        $rule->load("category");
        return $this->ok($rule);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminReportRuleRequest $request, ReportRule $rule): JsonResponse
    {
        $data = $request->validated();
        $rule->update($data);
        return $this->ok($rule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportRule $rule): JsonResponse
    {
        $rule->delete();
        return $this->deleted();
    }
}
