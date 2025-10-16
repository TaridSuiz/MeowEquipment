<?php

namespace App\Http\Controllers;

use App\Models\CategorieModel;
use App\Models\Merchandise;
use App\Models\Category;
use App\Models\MerchandiseModel;
use App\Models\ReviewModel;
use App\Models\WishlistModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MerchandiseController extends Controller
{
    // GET /admin/merchandise
    public function index()
    {
        Paginator::useBootstrap();
        $items = MerchandiseModel::with('category')
            ->orderBy('merchandise_id','desc')
            ->paginate(10);

        return view('merchandise.list', compact('items'));
    }

    // GET /admin/merchandise/create
    public function create()
    {
        $categories = CategorieModel::orderBy('category_name')->get(['category_id','category_name']);
        return view('merchandise.create', compact('categories'));
    }

    // POST /admin/merchandise
    public function store(Request $request)
    {
        $request->validate([
            'category_id'       => ['required','integer','exists:tbl_categories,category_id'],
            'merchandise_name'  => ['required','string','max:150'],
            'description'       => ['nullable','string'],
            'price'             => ['nullable','numeric'],
            'brand'             => ['nullable','string','max:100'],
            'age_range'         => ['nullable','string','max:50'],
            'rating_avg'        => ['nullable','numeric'],
            'link_store'        => ['nullable','string','max:255'],
            'merchandise_image' => ['nullable','image','mimes:jpeg,png,jpg','max:5120'],
        ]);

        $imagePath = null;
        if ($request->hasFile('merchandise_image')) {
            $imagePath = $request->file('merchandise_image')->store('uploads/merchandise','public');
        }

        MerchandiseModel::create([
            'category_id'       => $request->category_id,
            'merchandise_name'  => trim($request->merchandise_name),
            'description'       => $request->description,
            'price'             => $request->price,
            'brand'             => $request->brand,
            'age_range'         => $request->age_range,
            'rating_avg'        => $request->rating_avg,
            'merchandise_image' => $imagePath,
            'link_store'        => $request->link_store,
            'created_at'        => now(),
        ]);

        return redirect()->route('admin.merchandise.index')->with('success','เพิ่มสินค้าเรียบร้อย');
    }

    // GET /admin/merchandise/{merchandise}/edit
    public function edit($id)
    {
        $item = MerchandiseModel::findOrFail($id);
        $categories = CategorieModel::orderBy('category_name')->get(['category_id','category_name']);
        return view('merchandise.edit', compact('item','categories'));
    }

    // PUT/PATCH /admin/merchandise/{merchandise}
    public function update($id, Request $request)
    {
        $request->validate([
            'category_id'       => ['required','integer','exists:tbl_categories,category_id'],
            'merchandise_name'  => ['required','string','max:150'],
            'description'       => ['nullable','string'],
            'price'             => ['nullable','numeric'],
            'brand'             => ['nullable','string','max:100'],
            'age_range'         => ['nullable','string','max:50'],
            'rating_avg'        => ['nullable','numeric'],
            'link_store'        => ['nullable','string','max:255'],
            'merchandise_image' => ['nullable','image','mimes:jpeg,png,jpg','max:5120'],
        ]);

        $item = MerchandiseModel::findOrFail($id);

        if ($request->hasFile('merchandise_image')) {
            if ($item->merchandise_image && Storage::disk('public')->exists($item->merchandise_image)) {
                Storage::disk('public')->delete($item->merchandise_image);
            }
            $item->merchandise_image = $request->file('merchandise_image')->store('uploads/merchandise','public');
        }

        $item->update([
            'category_id'       => $request->category_id,
            'merchandise_name'  => trim($request->merchandise_name),
            'description'       => $request->description,
            'price'             => $request->price,
            'brand'             => $request->brand,
            'age_range'         => $request->age_range,
            'rating_avg'        => $request->rating_avg,
            'link_store'        => $request->link_store,
        ]);

        return redirect()->route('admin.merchandise.index')->with('success','แก้ไขสินค้าเรียบร้อย');
    }

    // DELETE /admin/merchandise/{merchandise}
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $item = MerchandiseModel::findOrFail($id);

                // 1) ลบลูก: reviews & wishlists ของสินค้านี้
                ReviewModel::where('merchandise_id', $item->merchandise_id)->delete();
                // ถ้าไม่มี wishlist หรือไม่ใช้ ฟังก์ชันนี้ ลบบรรทัดนี้ออกได้
                WishlistModel::where('merchandise_id', $item->merchandise_id)->delete();

                // 2) ลบไฟล์รูปถ้ามี
                if ($item->merchandise_image && Storage::disk('public')->exists($item->merchandise_image)) {
                    Storage::disk('public')->delete($item->merchandise_image);
                }

                // 3) ลบตัวสินค้า
                $item->delete();
            });

            return redirect()->route('admin.merchandise.index')->with('success','ลบสินค้าและรีวิวที่เกี่ยวข้องแล้ว');
        } catch (\Throwable $e) {
            return redirect()->route('admin.merchandise.index')->with('error','ลบไม่สำเร็จ: '.$e->getMessage());
        }
    }
}
