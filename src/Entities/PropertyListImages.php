<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyListImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'property_id',
        'unit_id',
        'workspace',
        'created_by',
    ];


}
