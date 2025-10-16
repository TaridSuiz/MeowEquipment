<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounterModel extends Model
{
    protected $table = 'tbl_counter';
    protected $primaryKey = 'c_id '; // ตั้งให้ตรงกับชื่อจริงใน DB
    protected $fillable = ['c_date'];
    public $incrementing = true; // ถ้า primary key เป็นตัวเลข auto increment
    public $timestamps = false;
}
