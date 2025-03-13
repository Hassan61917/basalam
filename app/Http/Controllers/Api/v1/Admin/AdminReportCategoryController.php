<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\AdminReportCategoryRequest;
use App\Http\Resources\v1\ReportCategoryResource;
use App\Models\ReportCategory;
use Illuminate\Http\JsonResponse;

class AdminReportCategoryController extends Controller
{
    protected string $resource = ReportCategoryResource::class;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = ReportCategory::query()->withCount("reports");
        return $this->ok($this->paginate($categories));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminReportCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $category = ReportCategory::create($data);
        return $this->ok($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(ReportCategory $category): JsonResponse
    {
        $category->load('reports');
        return $this->ok($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminReportCategoryRequest $request, ReportCategory $category): JsonResponse
    {
        $data = $request->validated();
        $category->update($data);
        return $this->ok($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportCategory $category): JsonResponse
    {
        $category->delete();
        return $this->deleted();
    }
}
