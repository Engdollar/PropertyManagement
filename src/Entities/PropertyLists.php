<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyLists extends Model
{

    use HasFactory;

    protected $fillable = [
        'property_id',
        'unit',
        'status',
        'list_type',
        'rent_amount',
        'tax',
        'en_suites',
        'lounge',
        'garage_parking',
        'dining',
        'total_sq',
        'rent_type',
        'workspace',
        'created_by',
    ];


    public static $status = [
        'Visible',
        'Not Visible',
    ];


    public function property()
    {
        return  $this->hasOne(Property::class,'id','property_id');
    }


    public function unit_id()
    {
        return  $this->hasOne(PropertyUnit::class,'id','unit');
    }


    public function propertyImage()
    {
        return $this->hasMany(PropertyImages::class, 'property_id', 'property_id');
    }

    public function propertyListImage()
    {
        return $this->hasMany(PropertyListImages::class, 'unit_id', 'unit');
    }

}
