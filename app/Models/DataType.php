<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataType extends Model
{
    protected $fillable = ['data_brand_name_id', 'name', 'status'];

    public function brand()
    {
        return $this->belongsTo(DataBrandName::class, 'data_brand_name_id');
    }
}
