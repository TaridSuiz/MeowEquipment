<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ArticleModel;
use Illuminate\Http\Request;

class ArticlePublicController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->q);

        $articles = ArticleModel::query()
            ->when($q !== '', fn($qq) => $qq->where('title','like',"%{$q}%"))
            ->orderBy('article_id','desc')
            ->paginate(10)
            ->withQueryString();

        return view('articles.index', compact('articles','q'));
    }

    public function show($id)
    {
        $article = ArticleModel::with('author')->findOrFail($id);
        return view('articles.show', compact('article'));
    }
}
