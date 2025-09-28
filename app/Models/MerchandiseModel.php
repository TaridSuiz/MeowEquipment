<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchandiseModel extends Model
{
    protected $table = 'tbl_merchandise';
    protected $primaryKey = 'merchandise_id';
    protected $fillable = [
        'category_id','merchandise_name','description','price',
        'brand','age_range','rating_avg','merchandise_image',
        'link_store','created_at',
    ];
    public $timestamps = false;


    public function category()
    {
        return $this->belongsTo(CategorieModel::class, 'category_id', 'category_id');
    }
}
