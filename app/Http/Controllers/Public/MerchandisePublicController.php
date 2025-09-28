<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MerchandiseModel;
use App\Models\CategorieModel;

class MerchandisePublicController extends Controller
{
    /**
     * GET /shop
     * แสดงรายการสินค้า + Search/Filter + Pagination
     */
    public function index(Request $request)
    {
        // เตรียม Query
        $q = MerchandiseModel::query()
            ->with('category')
            ->when($request->filled('q'), function ($qq) use ($request) {
                $kw = trim($request->q);
                $qq->where(function ($w) use ($kw) {
                    $w->where('merchandise_name', 'like', "%{$kw}%")
                      ->orWhere('brand', 'like', "%{$kw}%");
                });
            })
            ->when($request->filled('category_id'), fn($qq) => $qq->where('category_id', $request->category_id))
            ->when($request->filled('min_rating'), fn($qq) => $qq->where('rating_avg', '>=', (float)$request->min_rating))
            ->orderByDesc('merchandise_id');

        $items = $q->paginate(8)->withQueryString();

        // ใช้สำหรับ dropdown filter
        $categories = CategorieModel::orderBy('category_name')->get();

        return view('public.shop.index', compact('items', 'categories'));
    }

    /**
     * GET /shop/{id}
     * แสดงรายละเอียดสินค้า
     */
    public function show($id)
    {
        $item = MerchandiseModel::with('category')->findOrFail($id);
        return view('public.shop.show', compact('item'));
    }

    /**
     * GET /compare?A=1&B=2
     * เปรียบเทียบสินค้า 2 ชิ้น
     */
    public function compare(Request $request)
    {
        // ตรวจสอบ input
        $request->validate([
            'A' => ['required','integer','different:B','exists:tbl_merchandise,merchandise_id'],
            'B' => ['required','integer','different:A','exists:tbl_merchandise,merchandise_id'],
        ]);

        $a = MerchandiseModel::with('category')->find($request->A);
        $b = MerchandiseModel::with('category')->find($request->B);

        return view('public.shop.compare', compact('a','b'));
    }
}
