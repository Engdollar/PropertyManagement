<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantDocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_required',
        'workspace',
        'created_by',
    ];
    
}
