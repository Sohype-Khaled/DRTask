<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends \Spatie\Permission\Models\Role
{
    public function owner(): BelongsTo
    {
        return $this->BelongsTo(Shop::class, 'shop_id');
    }
}
