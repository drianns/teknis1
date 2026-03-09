<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCategory extends Model
{
    use HasFactory;

    protected $fillable = ['data_brand_name_id', 'data_type_id', 'name', 'status'];

    public function brand()
    {
        return $this->belongsTo(DataBrandName::class, 'data_brand_name_id');
    }

    public function dataType()
    {
        return $this->belongsTo(DataType::class, 'data_type_id');
    }
}
