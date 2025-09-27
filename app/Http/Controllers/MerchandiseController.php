<?php

namespace App\Http\Controllers;

use App\Models\MerchandiseModel;
use App\Models\CategorieModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class MerchandiseController extends Controller
{
    /** GET /merchandise */
    public function index(Request $request)
    {
        Paginator::useBootstrap();

        $q = MerchandiseModel::with('category');

        // ค้นหา/กรอง
        if ($s = trim($request->get('s', ''))) {
            $q->where(function ($w) use ($s) {
                $w->where('merchandise_name', 'like', "%{$s}%")
                  ->orWhere('brand', 'like', "%{$s}%");
            });
        }
        if ($cat = $request->get('category_id')) {
            $q->where('category_id', $cat);
        }
        if ($min = $request->get('min_price')) {
            $q->where('price', '>=', $min);
        }
        if ($max = $request->get('max_price')) {
            $q->where('price', '<=', $max);
        }
        if ($minR = $request->get('min_rating')) {
            $q->where('rating_avg', '>=', $minR);
        }

        $merchandise = $q->orderBy('merchandise_id', 'desc')
                         ->paginate(10)->withQueryString();

        $categories = CategorieModel::orderBy('category_name')->get();

        return view('merchandise.list', compact('merchandise', 'categories'));
    }

    /** GET /merchandise/adding */
    public function adding()
    {
        $categories = CategorieModel::orderBy('category_name')->get();
        return view('merchandise.create', compact('categories'));
    }

    /** POST /merchandise */
    public function create(Request $request)
    {
        $messages = [
            'merchandise_name.required' => 'กรุณากรอกชื่อสินค้า',
            'merchandise_name.min'      => 'ชื่อต้องมีอย่างน้อย :min ตัวอักษร',
            'merchandise_name.unique'   => 'ชื่อนี้มีอยู่แล้ว',
            'category_id.required'      => 'กรุณาเลือกหมวดหมู่',
            'category_id.exists'        => 'หมวดหมู่ไม่ถูกต้อง',
            'price.numeric'             => 'กรุณากรอกราคาเป็นตัวเลข',
            'rating_avg.numeric'        => 'เรตติ้งต้องเป็นตัวเลข',
            'rating_avg.max'            => 'เรตติ้งสูงสุด 5',
            'merchandise_image.image'   => 'ไฟล์ต้องเป็นรูปภาพ',
            'merchandise_image.mimes'   => 'รองรับ jpeg, png, jpg เท่านั้น',
            'merchandise_image.max'     => 'ไฟล์ไม่เกิน 5MB',
        ];

        $validator = Validator::make($request->all(), [
            'category_id'       => 'required|exists:tbl_categories,category_id',
            'merchandise_name'  => 'required|min:3|unique:tbl_merchandise,merchandise_name',
            'description'       => 'nullable|max:65535',
            'price'             => 'nullable|numeric|min:0',
            'brand'             => 'nullable|max:100',
            'age_range'         => 'nullable|max:50',
            'rating_avg'        => 'nullable|numeric|min:0|max:5',
            'link_store'        => 'nullable|max:255',
            'merchandise_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], $messages);

        if ($validator->fails()) {
            return redirect('/merchandise/adding')->withErrors($validator)->withInput();
        }

        try {
            $imagePath = null;
            if ($request->hasFile('merchandise_image')) {
                $imagePath = $request->file('merchandise_image')->store('uploads/merchandise', 'public');
            }

            MerchandiseModel::create([
                'category_id'       => $request->category_id,
                'merchandise_name'  => strip_tags($request->merchandise_name),
                'description'       => $request->filled('description') ? strip_tags($request->description) : null,
                'price'             => $request->price,
                'brand'             => $request->brand,
                'age_range'         => $request->age_range,
                'rating_avg'        => $request->rating_avg,
                'link_store'        => $request->link_store,
                'merchandise_image' => $imagePath,
                'created_at'        => now(),
            ]);

            Alert::success('เพิ่มสินค้าเรียบร้อย');
            return redirect('/merchandise');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** GET /merchandise/{id} */
    public function edit($id)
    {
        try {
            $merchandise = MerchandiseModel::findOrFail($id);
            $categories  = CategorieModel::orderBy('category_name')->get();
            return view('merchandise.edit', compact('merchandise', 'categories'));
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** PUT /merchandise/{id} */
    public function update($id, Request $request)
    {
        $messages = [
            'merchandise_name.required' => 'กรุณากรอกชื่อสินค้า',
            'category_id.required'      => 'กรุณาเลือกหมวดหมู่',
            'price.numeric'             => 'กรุณากรอกราคาเป็นตัวเลข',
        ];

        $validator = Validator::make($request->all(), [
            'category_id'       => 'required|exists:tbl_categories,category_id',
            'merchandise_name'  => [
                'required','min:3',
                Rule::unique('tbl_merchandise','merchandise_name')->ignore($id, 'merchandise_id')
            ],
            'description'       => 'nullable|max:65535',
            'price'             => 'nullable|numeric|min:0',
            'brand'             => 'nullable|max:100',
            'age_range'         => 'nullable|max:50',
            'rating_avg'        => 'nullable|numeric|min:0|max:5',
            'link_store'        => 'nullable|max:255',
            'merchandise_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], $messages);

        if ($validator->fails()) {
            return redirect('/merchandise/' . $id)->withErrors($validator)->withInput();
        }

        try {
            $merchandise = MerchandiseModel::findOrFail($id);

            // อัปโหลดรูปใหม่ถ้ามี
            if ($request->hasFile('merchandise_image')) {
                if ($merchandise->merchandise_image && Storage::disk('public')->exists($merchandise->merchandise_image)) {
                    Storage::disk('public')->delete($merchandise->merchandise_image);
                }
                $merchandise->merchandise_image = $request->file('merchandise_image')->store('uploads/merchandise', 'public');
            }

            // อัปเดตฟิลด์อื่น ๆ
            $merchandise->category_id       = $request->category_id;
            $merchandise->merchandise_name  = strip_tags($request->merchandise_name);
            $merchandise->description       = $request->filled('description') ? strip_tags($request->description) : null;
            $merchandise->price             = $request->price;
            $merchandise->brand             = $request->brand;
            $merchandise->age_range         = $request->age_range;
            $merchandise->rating_avg        = $request->rating_avg;
            $merchandise->link_store        = $request->link_store;

            $merchandise->save();

            Alert::success('อัปเดตข้อมูลสำเร็จ');
            return redirect('/merchandise');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** DELETE /merchandise/remove/{id} */
    public function remove($id)
    {
        try {
            $merchandise = MerchandiseModel::find($id);

            if (!$merchandise) {
                Alert::error('ไม่พบสินค้า');
                return redirect('/merchandise');
            }

            // ลบไฟล์รูปถ้ามี
            if ($merchandise->merchandise_image && Storage::disk('public')->exists($merchandise->merchandise_image)) {
                Storage::disk('public')->delete($merchandise->merchandise_image);
            }

            $merchandise->delete();

            Alert::success('ลบสินค้าเรียบร้อย');
            return redirect('/merchandise');
        } catch (\Exception $e) {
            Alert::error('เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect('/merchandise');
        }
    }
}
