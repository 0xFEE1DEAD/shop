<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\HasOrdering;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    use HasOrdering {
        matchOrderLockKey as __matchOrderLockKey;
    }

    protected $fillable = [
        'name',
        'price'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->using(OrderProduct::class);
    }

    protected function scopeQueryOderingGroup(Builder $query, Model $model): void
    {
        $key = $this->category()->getForeignKeyName();
        $query->where(
            $key,
            $model->$key
        );
    }

    protected function matchOrderLockKey(): string
    {
        $key = $this->category()->getForeignKeyName();
        return $this->__matchOrderLockKey() . '_' . $this->$key;
    }
}
