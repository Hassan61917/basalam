<?php

namespace App\Models;

use App\Models\Trait\Relations\DiscountRelations;
use App\Models\Trait\Scopes\DiscountScopes;

class Discount extends AppModel
{
    use DiscountRelations,
        DiscountScopes;

    protected $fillable = [
        "title", "description", "code", "category_id",
        "client_id", "shop_id", "product_id", "limit", "amount",
        "percent", "total_balance", "max_amount", "expired_at"
    ];

    protected function casts(): array
    {
        return [
            "expired_at" => "datetime",
        ];
    }

    public function getValue(int $total_price)
    {
        $value = 0;
        if ($this->amount) {
            $value = $this->amount;
        }
        if ($this->percent) {
            $value = ($total_price / 100) * $this->percent;
        }
        if ($this->max_amount) {
            $value = min($this->max_amount, $value);
        }
        return $value;
    }
}
