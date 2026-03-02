<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataBrandCategory extends Model
{
    protected $fillable = ['data_group_name_id', 'name', 'status'];

    public function group()
    {
        return $this->belongsTo(DataGroupName::class, 'data_group_name_id');
    }
}
