<?php

namespace App\Http\Controllers;

use App\Models\ArticleModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use RealRashid\SweetAlert\Facades\Alert;

class ArticleController extends Controller
{
    /** GET /article */
    public function index()
    {
        Paginator::useBootstrap();
        $articles = ArticleModel::with('author')->orderBy('article_id', 'desc')->paginate(5);
        return view('articles.list', compact('articles'));
    }

    /** GET /article/adding */
    public function adding()
    {
        $authors = UserModel::all();
        return view('articles.create', compact('authors'));
    }

    /** POST /article */
    public function create(Request $request)
    {
        $messages = [
            'title.required'   => 'กรุณากรอกชื่อบทความ',
            'content.required' => 'กรุณากรอกเนื้อหา',
            'author_id.exists' => 'ผู้เขียนไม่ถูกต้อง',
            'cover_image.image' => 'ไฟล์ต้องเป็นรูปภาพ',
            'cover_image.mimes' => 'รองรับ jpeg, png, jpg เท่านั้น',
            'cover_image.max'   => 'ไฟล์ต้องไม่เกิน 5MB',
        ];

        $validator = Validator::make($request->all(), [
            'title'       => 'required|min:3',
            'content'     => 'required',
            'author_id'   => 'required|exists:tbl_user,user_id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], $messages);

        if ($validator->fails()) {
            return redirect('article/adding')->withErrors($validator)->withInput();
        }

        try {
            $imagePath = null;
            if ($request->hasFile('cover_image')) {
                $imagePath = $request->file('cover_image')->store('uploads/articles', 'public');
            }

            ArticleModel::create([
                'title'       => strip_tags($request->title),
                'content'     => $request->content,
                'author_id'   => $request->author_id,
                'cover_image' => $imagePath,
                'created_at'  => now(),
            ]);

            Alert::success('เพิ่มบทความสำเร็จ');
            return redirect('/article');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /** GET /article/{id} */
    public function edit($id)
    {
        try {
            $article = ArticleModel::findOrFail($id);
            $authors = UserModel::all();
            return view('articles.edit', compact('article', 'authors'));
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** PUT /article/{id} */
    public function update($id, Request $request)
    {
        $messages = [
            'title.required'   => 'กรุณากรอกชื่อบทความ',
            'content.required' => 'กรุณากรอกเนื้อหา',
            'author_id.exists' => 'ผู้เขียนไม่ถูกต้อง',
            'cover_image.image' => 'ไฟล์ต้องเป็นรูปภาพ',
            'cover_image.mimes' => 'รองรับ jpeg, png, jpg เท่านั้น',
            'cover_image.max'   => 'ไฟล์ต้องไม่เกิน 5MB',
        ];

        $validator = Validator::make($request->all(), [
            'title'       => 'required|min:3',
            'content'     => 'required',
            'author_id'   => 'required|exists:tbl_user,user_id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], $messages);

        if ($validator->fails()) {
            return redirect('article/' . $id)->withErrors($validator)->withInput();
        }

        try {
            $article = ArticleModel::findOrFail($id);

            if ($request->hasFile('cover_image')) {
                if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
                    Storage::disk('public')->delete($article->cover_image);
                }
                $article->cover_image = $request->file('cover_image')->store('uploads/articles', 'public');
            }

            $article->title   = strip_tags($request->title);
            $article->content = $request->content;
            $article->author_id = $request->author_id;
            $article->updated_at = now();

            $article->save();

            Alert::success('แก้ไขบทความสำเร็จ');
            return redirect('/article');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    /** DELETE /article/remove/{id} */
    public function remove($id)
    {
        try {
            $article = ArticleModel::find($id);

            if (!$article) {
                Alert::error('ไม่พบบทความ');
                return redirect('/article');
            }

            if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
                Storage::disk('public')->delete($article->cover_image);
            }

            $article->delete();

            Alert::success('ลบบทความสำเร็จ');
            return redirect('/article');
        } catch (\Exception $e) {
            Alert::error('เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect('/article');
        }
    }
}
