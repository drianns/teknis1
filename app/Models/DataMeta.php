<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataMeta extends Model
{
    protected $fillable = ['data_brand_name_id', 'data_type_id', 'data_category_id', 'name', 'status', 'created_by'];

    public function brand()
    {
        return $this->belongsTo(DataBrandName::class, 'data_brand_name_id');
    }

    public function dataType()
    {
        return $this->belongsTo(DataType::class, 'data_type_id');
    }

    public function category()
    {
        return $this->belongsTo(DataCategory::class, 'data_category_id');
    }
}
