<?php

namespace App\Http\Controllers;
use App\Models\CategorieModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Rule;

class CatagorieController extends Controller
{
    /** GET /category */
    public function index()
    {
        Paginator::useBootstrap();
        $categories = CategorieModel::orderBy('category_id', 'desc')->paginate(5);
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
            'description'   => 'nullable|max:65535', // TEXT
        ], $messages);

        if ($validator->fails()) {
            return redirect('category/adding')->withErrors($validator)->withInput();
        }

        try {
            CategorieModel::create([
                'category_name' => strip_tags($request->category_name),
                'description'   => $request->description ? strip_tags($request->description) : null,
                'created_at'    => now(),
            ]);

            Alert::success('Insert Successfully');
            return redirect('/category'); // หรือ ->route('category.index') ถ้าตั้งชื่อ route
        } catch (\Exception $e) {
            // return response()->json(['error' => $e->getMessage()], 500);
            return view('errors.404');
        }
    }

    /** GET /category/{id} */
    public function edit($id)
    {
        try {
            $category = CategorieModel::findOrFail($id);

            if (isset($category)) {
                $id            = $category->category_id;
                $category_name = $category->category_name;
                $description   = $category->description;
                $created_at    = $category->created_at;

                return view('categories.edit', compact(
                    'id', 'category_name', 'description', 'created_at'
                ));
            }
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
            return redirect('category/' . $id)->withErrors($validator)->withInput();
        }

        try {
            $category = CategorieModel::findOrFail($id);

            $category->category_name = strip_tags($request->category_name);
            $category->description   = $request->description ? strip_tags($request->description) : null;
            // $category->created_at  // ปกติไม่แก้ created_at ตอน update

            $category->save();

            Alert::success('Update Successfully');
            return redirect('/category'); // หรือ ->route('category.index')
        } catch (\Exception $e) {
            // return response()->json(['error' => $e->getMessage()], 500);
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
                return redirect('/category');
            }

            $category->delete();

            Alert::success('Delete Successfully');
            return redirect('/category');
        } catch (\Exception $e) {
            Alert::error('เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect('/category');
        }
    }
}
