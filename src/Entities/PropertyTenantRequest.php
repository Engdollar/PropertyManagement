<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyTenantRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'mobile_no',
        'property_id',
        'unit_id',
        'status',
        'workspace',
        'created_by',
    ];


    public function property()
    {
        return  $this->hasOne(Property::class,'id','property_id');
    }


    public function unit_detail()
    {
        return  $this->hasOne(PropertyUnit::class,'id','unit_id');
    }
    
    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'id', 'user_id');
    }
}
