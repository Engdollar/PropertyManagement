<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyContractor extends Model
{
    use HasFactory;
    protected $table = 'property_contractors';
    public $guarded = [];
}
