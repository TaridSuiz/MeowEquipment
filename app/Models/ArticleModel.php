<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleModel extends Model
{
    protected $table = 'tbl_articles';
    protected $primaryKey = 'article_id';
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'cover_image',
        'created_at',
        'updated_at',
    ];
    public $timestamps = false;

    // ความสัมพันธ์กับ Users
    public function author()
    {
        return $this->belongsTo(UserModel::class, 'author_id', 'user_id');
    }
}
