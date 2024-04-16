<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function (OrderProduct $orderProduct) {
            $orderProduct->price = Product::find($orderProduct->product_id)->price;
        });
    }
    protected $table = 'order_product';
}