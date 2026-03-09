<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataBrandName extends Model
{
    protected $fillable = ['data_brand_category_id', 'name', 'status'];

    public function category()
    {
        return $this->belongsTo(DataBrandCategory::class, 'data_brand_category_id');
    }
}
