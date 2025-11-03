<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyMaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'unit_id',
        'issue',
        'description_of_issue',
        'request_date',
        'status',
        'attachment',
        'workspace',
        'created_by',
    ];

    public function property()
    {
        return  $this->hasOne(Property::class,'id','property_id');
    }

    public function unit()
    {
        return  $this->hasOne(PropertyUnit::class,'id','unit_id');
    }
    
    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'id', 'user_id');
    }
}
