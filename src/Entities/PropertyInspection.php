<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyInspection extends Model
{
    use HasFactory;
    protected $table = 'property_inspections';
    public $guarded = [];


    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
