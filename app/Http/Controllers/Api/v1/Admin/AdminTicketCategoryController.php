<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\AdminTicketCategoryRequest;
use App\Http\Resources\v1\TicketCategoryResource;
use App\Models\TicketCategory;
use Illuminate\Http\JsonResponse;

class AdminTicketCategoryController extends Controller
{
    protected string $resource = TicketCategoryResource::class;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = TicketCategory::withFilters()->withCount('tickets');
        return $this->ok($this->paginate($categories));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminTicketCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $category = TicketCategory::create($data);
        return $this->ok($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketCategory $category): JsonResponse
    {
        $category->load('tickets');
        return $this->ok($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminTicketCategoryRequest $request, TicketCategory $category): JsonResponse
    {
        $data = $request->validated();
        $category->update($data);
        return $this->ok($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketCategory $category): JsonResponse
    {
        $category->delete();
        return $this->deleted();
    }
}
