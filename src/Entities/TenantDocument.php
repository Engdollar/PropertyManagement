<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_id',
        'document_value',
        'workspace',
        'created_by',
    ];
    
}
