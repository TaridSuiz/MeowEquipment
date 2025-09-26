<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'tbl_user';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    public $timestamps = true; // ✅ เปิดใช้ created_at, updated_at

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_img',
    ];
}
