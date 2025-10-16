<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    // GET /admin/articles
    public function index()
    {
        Paginator::useBootstrap();

        $articles = ArticleModel::with('author')
            ->orderBy('article_id', 'desc')
            ->paginate(10);

        return view('articles.list', compact('articles'));
    }

    // GET /admin/articles/create
    public function create()
    {
        return view('articles.create');
    }

    // POST /admin/articles
    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required','string','max:200'],
            'content'     => ['required','string'],   // ถ้าคอลัมน์เป็น TEXT จะพอดี
            'cover_image' => ['nullable','image','mimes:jpeg,png,jpg','max:5120'],
        ]);

        $path = null;
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('uploads/articles', 'public');
        }

        ArticleModel::create([
            'title'       => trim($request->title),
            'content'     => $request->content,
            'author_id'   => Auth::id(),       // ผู้เขียน = admin ที่ล็อกอิน
            'cover_image' => $path,
            'created_at'  => now(),
            'update_at'   => now(),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'เพิ่มบทความสำเร็จ');
    }

    // GET /admin/articles/{article}/edit
    public function edit($id)
    {
        $article = ArticleModel::findOrFail($id);
        return view('articles.edit', compact('article'));
    }

    // PUT/PATCH /admin/articles/{article}
    public function update($id, Request $request)
    {
        $request->validate([
            'title'       => ['required','string','max:200'],
            'content'     => ['required','string'],
            'cover_image' => ['nullable','image','mimes:jpeg,png,jpg','max:5120'],
        ]);

        $article = ArticleModel::findOrFail($id);

        if ($request->hasFile('cover_image')) {
            if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
                Storage::disk('public')->delete($article->cover_image);
            }
            $article->cover_image = $request->file('cover_image')->store('uploads/articles', 'public');
        }

        $article->title     = trim($request->title);
        $article->content   = $request->content;
        $article->update_at = now();
        $article->save();

        return redirect()->route('admin.articles.index')->with('success', 'แก้ไขบทความสำเร็จ');
    }

    // DELETE /admin/articles/{article}
    public function destroy($id)
    {
        try {
            $article = ArticleModel::find($id);
            if (!$article) {
                return redirect()->route('admin.articles.index')->with('error','ไม่พบบทความ');
            }

            if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
                Storage::disk('public')->delete($article->cover_image);
            }

            $article->delete();

            return redirect()->route('admin.articles.index')->with('success','ลบบทความสำเร็จ');
        } catch (\Throwable $e) {
            return redirect()->route('admin.articles.index')->with('error','ลบไม่สำเร็จ: '.$e->getMessage());
        }
    }
}
