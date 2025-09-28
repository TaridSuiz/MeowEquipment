<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistModel extends Model
{
    protected $table = 'tbl_wishlist';
    protected $primaryKey = 'wishlist_id';
    public $incrementing = true;
    public $timestamps = false; // ใช้ created_at อย่างเดียว

    protected $fillable = [
        'user_id',
        'merchandise_id',
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
