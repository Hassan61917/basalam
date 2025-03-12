<?php

namespace App\Models;

use App\Models\Trait\Relations\CategoryRelations;
use Illuminate\Database\Eloquent\Builder;

class Category extends AppModel
{
    use CategoryRelations;

    protected $fillable = [
        "name",
        "slug",
        "icon",
        "parent_id"
    ];

    public function scopeParent(Builder $builder): Builder
    {
        return $builder->whereNull("parent_id");
    }
    public function isMainParent(): bool
    {
        return !$this->parent_id;
    }

    public function isMyChild(Category $category): bool
    {
        if ($this->id == $category->id) {
            return true;
        }
        $queue = [$this->children()];
        while (!empty($queue)) {
            $current = array_shift($queue);
            if ($current->where("id", $category->id)->exists()) {
                return true;
            }
            foreach ($current->get() as $child) {
                $queue[] = $child->children();
            }
        }
        return false;
    }
    public function getChildrenIds(): array
    {
        $queue = [...$this->children];
        $result = [];
        while (!empty($queue)) {
            $current = array_shift($queue);
            foreach ($current->children as $child) {
                $queue[] = $child;
            }
            $result[] = $current->id;
        }
        return $result;
    }

    public function getParentsIds(): array
    {
        $parent = $this->parent;
        $result = [];
        while ($parent) {
            $result[] = $parent->id;
            $parent = $parent->parent;
        }
        return $result;
    }

    public function getLastParent(): Category
    {
        $parent = $this;
        while ($parent->parent) {
            $parent = $parent->parent;
        }
        return $parent;
    }
}
