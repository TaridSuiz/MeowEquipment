<?php

namespace App\Http\Controllers;

use App\Models\CategorieModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class CatagorieController extends Controller
{
    /** GET /category */
    public function index(Request $request)
    {
        Paginator::useBootstrap();

        $q = CategorieModel::query();

        // simple search by name/description
        if ($s = $request->get('s')) {
            $s = trim($s);
            $q->where(function ($w) use ($s) {
                $w->where('category_name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $categories = $q->orderBy('category_id', 'desc')->paginate(5)->withQueryString();

        return view('categories.list', compact('categories'));
    }

    /** GET /category/adding */
    public function adding()
    {
        return view('categories.create');
    }

    /** POST /category */
    public function create(Request $request)
    {
        $messages = [
            'category_name.required' => 'กรุณากรอกชื่อหมวดหมู่',
            'category_name.min'      => 'ต้องมีอย่างน้อย :min ตัวอักษร',
            'category_name.unique'   => 'ชื่อหมวดหมู่นี้ถูกใช้แล้ว',
            'description.max'        => 'รายละเอียดต้องไม่เกิน :max ตัวอักษร',
        ];

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|min:3|unique:tbl_categories,category_name',
            'description'   => 'nullable|max:65535',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('category.create')->withErrors($validator)->withInput();
        }

        try {
            CategorieModel::create([
                'category_name' => strip_tags(trim($request->category_name)),
                'description'   => $request->filled('description') ? strip_tags(trim($request->description)) : null,
                'created_at'    => now(),
            ]);

            Alert::success('Insert Successfully');
            return redirect()->route('category.index');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** GET /category/{id} */
    public function edit($id)
    {
        try {
            $category = CategorieModel::findOrFail($id);

            // ส่งค่าเดิมไปให้ Blade
            return view('categories.edit', [
                'id'            => $category->category_id,
                'category_name' => $category->category_name,
                'description'   => $category->description,
                'created_at'    => $category->created_at,
            ]);
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** PUT /category/{id} */
    public function update($id, Request $request)
    {
        $messages = [
            'category_name.required' => 'กรุณากรอกชื่อหมวดหมู่',
            'category_name.min'      => 'ต้องมีอย่างน้อย :min ตัวอักษร',
            'category_name.unique'   => 'ชื่อหมวดหมู่นี้ถูกใช้แล้ว',
            'description.max'        => 'รายละเอียดต้องไม่เกิน :max ตัวอักษร',
        ];

        $validator = Validator::make($request->all(), [
            'category_name' => [
                'required', 'min:3',
                Rule::unique('tbl_categories', 'category_name')->ignore($id, 'category_id'),
            ],
            'description'   => 'nullable|max:65535',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('category.edit', $id)->withErrors($validator)->withInput();
        }

        try {
            $category = CategorieModel::findOrFail($id);

            $category->category_name = strip_tags(trim($request->category_name));
            $category->description   = $request->filled('description') ? strip_tags(trim($request->description)) : null;
            $category->save();

            Alert::success('Update Successfully');
            return redirect()->route('category.index');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** DELETE /category/remove/{id} */
    public function remove($id)
    {
        try {
            $category = CategorieModel::find($id);

            if (!$category) {
                Alert::error('Category not found.');
                return redirect()->route('category.index');
            }

            $category->delete();

            Alert::success('Delete Successfully');
            return redirect()->route('category.index');

        } catch (QueryException $e) {
            // รหัส error 1451 (MySQL) = Cannot delete or update a parent row: a foreign key constraint fails
            if ((int)$e->getCode() === 23000) {
                Alert::error('ไม่สามารถลบได้: หมวดหมู่นี้ถูกใช้งานโดยสินค้าอยู่');
                return redirect()->route('category.index');
            }
            Alert::error('เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect()->route('category.index');

        } catch (\Exception $e) {
            Alert::error('เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect()->route('category.index');
        }
    }
}
