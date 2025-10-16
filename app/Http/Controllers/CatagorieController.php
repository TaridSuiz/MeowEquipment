<?php

namespace App\Http\Controllers;

use App\Models\CategorieModel;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class CatagorieController extends Controller
{
    // GET /admin/categories
    public function index()
    {
        Paginator::useBootstrap();
        $categories = CategorieModel::orderBy('category_id', 'desc')->paginate(10);
        return view('categories.list', compact('categories'));
    }

    // GET /admin/categories/create
    public function create()
    {
        return view('categories.create');
    }

    // POST /admin/categories
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => ['required','string','max:100'],
            'description'   => ['nullable','string'],
        ]);

        CategorieModel::create([
            'category_name' => trim($request->category_name),
            'description'   => $request->description,
            'created_at'    => now(),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'เพิ่มหมวดหมู่สำเร็จ');
    }

    // GET /admin/categories/{category}/edit
    public function edit($id)
    {
        $category = CategorieModel::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    // PUT/PATCH /admin/categories/{category}
    public function update($id, Request $request)
    {
        $request->validate([
            'category_name' => ['required','string','max:100'],
            'description'   => ['nullable','string'],
        ]);

        $category = CategorieModel::findOrFail($id);
        $category->update([
            'category_name' => trim($request->category_name),
            'description'   => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'แก้ไขหมวดหมู่สำเร็จ');
    }

    // DELETE /admin/categories/{category}
    public function destroy($id)
    {
        $category = CategorieModel::find($id);
        if (!$category) {
            return back()->with('error','ไม่พบหมวดหมู่');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'ลบหมวดหมู่สำเร็จ');
    }
}
