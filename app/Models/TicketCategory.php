<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketCategory extends AppModel
{
    protected $fillable = [
        "title", "priority", "auto_close"
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class,"category_id");
    }
}
