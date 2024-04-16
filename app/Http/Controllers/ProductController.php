<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderingChangeRequest;
use App\Models\Product;

class ProductController extends Controller
{

    public function changeOrderUp(Product $product, OrderingChangeRequest $request)
    {
        $product->changeOrderUpBy((int)$request->get('by'));

        return response(200);
    }

    public function changeOrderDown(Product $product, OrderingChangeRequest $request)
    {
        $product->changeOrderDownBy((int)$request->get('by'));

        return response(200);
    }
}
