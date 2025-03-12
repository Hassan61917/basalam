<?php

namespace App\ModelServices\Financial;

use App\Exceptions\ModelException;
use App\Models\Category;
use App\Models\Commission;
use Illuminate\Database\Eloquent\Builder;

class CommissionService
{

    public function getAll(array $relations): Builder
    {
        return Commission::query()->unExpired()->with($relations);
    }

    public function make(array $data)
    {
        $category = Category::find($data['category_id']);
        if (!$category->isMainParent()) {
            throw new ModelException("Commission creation is not allowed for child categories");
        }
        $this->handleExists($category);
        $data["applied_at"] = $data["applied_at"] ?? now();
        $data["expired_at"] = $data["expired_at"] ?? now()->addYear();
        return Commission::create($data);
    }

    private function handleExists(Category $category): void
    {
        $commission = $category->commissions()->latest()->first();
        if ($commission) {
            $commission->update([
                "applied_at" => null,
                "expired_at" => now()
            ]);
        }
    }
}
