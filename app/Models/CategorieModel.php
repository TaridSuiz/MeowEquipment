<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieModel extends Model
{
    protected $table = 'tbl_categories';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $fillable = ['category_name','description','created_at'];
}
