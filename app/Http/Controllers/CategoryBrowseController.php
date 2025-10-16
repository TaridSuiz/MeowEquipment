<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategorieModel;
use App\Models\MerchandiseModel;
use Illuminate\Pagination\Paginator;

class CategoryBrowseController extends Controller
{
    /**
     * Show merchandise by category id.
     */
    public function show($category_id, Request $request)
    {
        Paginator::useBootstrap();

        $category = CategorieModel::findOrFail($category_id);

       $items = MerchandiseModel::with('category')
            ->where('category_id', $category_id)
            ->orderByDesc('merchandise_id')
            ->paginate(12)
            ->withQueryString();

        return view('public.category.index', compact('category','items'));
    }
}
