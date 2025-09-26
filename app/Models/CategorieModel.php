<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieModel extends Model
{
    protected $table = 'tbl_categories';
    protected $primaryKey = 'category_id'; // ตั้งให้ตรงกับชื่อจริงใน DB
    protected $fillable = [ 'category_name',
        'description',
        'created_at',];
    public $incrementing = true; // ถ้า primary key เป็นตัวเลข auto increment
    public $timestamps = false;
}
