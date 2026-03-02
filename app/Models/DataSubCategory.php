<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSubCategory extends Model
{
    protected $fillable = [
        'data_brand_name_id', 
        'data_type_id', 
        'data_category_id', 
        'data_meta_id', 
        'name', 
        'department_escalation_unit_id', 
        'escalation_layer', 
        'sla', 
        'status', 
        'created_by'
    ];

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

    public function meta()
    {
        return $this->belongsTo(DataMeta::class, 'data_meta_id');
    }

    public function escalationUnit()
    {
        return $this->belongsTo(DepartmentEscalationUnit::class, 'department_escalation_unit_id');
    }
}
