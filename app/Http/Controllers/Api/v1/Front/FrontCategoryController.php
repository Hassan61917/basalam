<?php

namespace App\Http\Controllers\Api\v1\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
class FrontCategoryController extends Controller
{
    protected string $resource = CategoryResource::class;

    public function index(): JsonResponse
    {
        $categories = Category::query()->parent()->with("children")->withCount("services");
        return $this->ok($this->paginate($categories));
    }
    public function show(Category $category): JsonResponse
    {
        $category->load("children");
        return $this->ok($category);
    }
}
