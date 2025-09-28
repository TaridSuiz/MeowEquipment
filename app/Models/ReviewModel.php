<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewModel extends Model
{
    protected $table = 'tbl_reviews';
    protected $primaryKey = 'review_id';
    public $incrementing = true;
    public $timestamps = false; // ใช้ created_at อย่างเดียว

    protected $fillable = [
        'user_id',
        'merchandise_id',
        'rating',
        'comment',
        'created_at',
    ];

    // ความสัมพันธ์
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function merchandise()
    {
        return $this->belongsTo(MerchandiseModel::class, 'merchandise_id', 'merchandise_id');
    }
}
