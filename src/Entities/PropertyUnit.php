<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name',
        'bedroom',
        'baths',
        'kitchen',
        'amenities',
        'description',
        'rentable_status',
        'rent_type',
        'rent',
        'utilities_included',
        'workspace',
        'created_by',
    ];

    public function property()
    {
        return  $this->hasOne(Property::class,'id','property_id');
    }
    
}
