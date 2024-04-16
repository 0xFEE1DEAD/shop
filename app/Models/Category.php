<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\HasOrdering;

class Category extends Model
{
    use HasFactory;
    use HasOrdering {
        matchOrderLockKey as private __matchOrderLockKey;
    }

    protected $fillable = [
        'name'
    ];

    private const PARENT_COLUMN_NAME = 'parent_id';

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, self::PARENT_COLUMN_NAME);
    }

    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, self::PARENT_COLUMN_NAME);
    }

    public function ancestors(): BelongsTo
    {
        return $this->parent()->with('ancestors');
    }

    public function scopeOnlyRoot(Builder $query): void
    {
        $query->whereNull(self::PARENT_COLUMN_NAME);
    }

    public function scopeAsTreeViewWithProducts(Builder $query): void
    {
        self::resolveRelationUsing('children_with_products', function (self $model) {
            return $model
                ->children()
                ->applyOrdering()
                ->with('children_with_products')
                ->with('products', function($q) {
                    $q->applyOrdering();
                });
        });

        $query
            ->onlyRoot()
            ->with('children_with_products')
            ->with('products', function($q) {
                $q->applyOrdering();
            })
            ->applyOrdering();
    }

    protected function scopeQueryOderingGroup(Builder $query, Model $model): void
    {
        $query->where(
            self::PARENT_COLUMN_NAME,
            $model->{self::PARENT_COLUMN_NAME}
        );
    }

    protected function matchOrderLockKey(): string
    {
        return $this->__matchOrderLockKey() . '_' . $this->{self::PARENT_COLUMN_NAME};
    }
}
