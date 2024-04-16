<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Exceptions\HasOrdering\OrderingColumnChangeNotAllowed;

trait HasOrdering
{
    public static function bootHasOrdering() {
        static::creating(function ($model) {
            Cache::lock($model->matchOrderLockKey(), 10)
                ->get(function() use ($model) {
                    $orderColName = $model->getOrderColName();
                    $maxOrder = $model->queryOderingGroup($model)->max($orderColName);
                    $model->$orderColName = $maxOrder + 1;
                });
        });

        static::updating(function ($model) {
            $orderColName = $model->getOrderColName();
            $originalValues = $model->getOriginal();
            $currentValues = $model->getAttributes();
            $changedAttributes = array_diff_assoc($currentValues, $originalValues);
            if(array_key_exists($orderColName, $changedAttributes)) {
                throw new OrderingColumnChangeNotAllowed("The $orderColName column is changed directly");
            }
        });
    }

    public function changeOrderUpBy(int $by): void
    {
        if ($by <= 0) return;
        Cache::lock($this->matchOrderLockKey(), 10)
                ->get(function() use ($by) {
                    DB::transaction(function() use ($by) {
                        $orderColName = $this->getOrderColName();
                        $needOrderValue = max($this->order - $by, 1);
                        $betweenOrderMaxValue = max($this->order - 1, 1);

                        self::whereBetween(
                            $orderColName, 
                            [$needOrderValue, $betweenOrderMaxValue]
                        )
                        ->queryOderingGroup($this)
                        ->increment(
                            $orderColName
                        );

                        self::where('id', $this->id)->update([
                            'order' => $needOrderValue
                        ]);
                        
                        $this->refresh();
                    });
                });
    }

    public function changeOrderDownBy(int $by): void
    {
        if ($by <= 0) return;
        Cache::lock($this->matchOrderLockKey(), 10)
                ->get(function() use ($by) {
                    DB::transaction(function() use ($by) {
                        $orderColName = $this->getOrderColName();
                        $maxOrder = self::queryOderingGroup($this)->max($orderColName);

                        $needOrderValue = min($this->order + $by, $maxOrder);
                        $betweenOrderMinValue = min($this->order + 1, $maxOrder);

                        self::whereBetween(
                            $orderColName, 
                            [$betweenOrderMinValue, $needOrderValue]
                        )
                        ->queryOderingGroup($this)
                        ->decrement(
                            $orderColName
                        );

                        self::where('id', $this->id)->update([
                            'order' => $needOrderValue
                        ]);

                        $this->refresh();
                    });
                });
    }
    
    public function scopeApplyOrdering(Builder $query, $direction = 'asc'): void
    {
        $query->orderBy($this->getOrderColName(), $direction);
    }

    protected function getOrderColName(): string
    {
        return 'order';
    }
    protected function scopeQueryOderingGroup(Builder $query, Model $model): void
    {
    }

    protected function matchOrderLockKey(): string
    {
        return $this->getTable() . '_ordering_match_lock';
    }
}
