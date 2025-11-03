<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $table='properties';
    protected $fillable = [
        'name',
        'address',
        'country',
        'state',
        'city',
        'pincode',
        'latitude',
        'longitude',
        'description',
        'security_deposit',
        'maintenance_charge',
        'workspace',
        'created_by',
    ];

    public function propertyImage()
    {
        return $this->hasMany(PropertyImages::class, 'property_id', 'id');
    }

    public function propertyUnitCount()
    {
        return $this->hasMany(PropertyUnit::class, 'property_id', 'id')->count();
    }

    public function availablePropertyUnit()
    {
        return $this->hasMany(PropertyUnit::class, 'property_id', 'id')->where('rentable_status','Vacant')->count();
    }

}
