<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\OrderingChangeRequest;

class CategoryController extends Controller
{
    public function __construct(
        private Category $category
    ) {
    }

    public function index()
    {
        $categories = $this->category->asTreeViewWithProducts()->get();

        return view('categories-tree')->withCategories($categories);
    }

    public function changeOrderUp(Category $category, OrderingChangeRequest $request)
    {
        $category->changeOrderUpBy((int)$request->get('by'));

        return response(200);
    }

    public function changeOrderDown(Category $category, OrderingChangeRequest $request)
    {
        $category->changeOrderDownBy((int)$request->get('by'));

        return response(200);
    }
}
