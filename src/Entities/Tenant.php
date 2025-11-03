<?php

namespace Workdo\PropertyManagement\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'unit_id',
        'total_family_member',
        'address',
        'country',
        'state',
        'city',
        'pincode',
        'documents',
        'lease_start_date',
        'lease_end_date',
        'workspace',
        'created_by',
    ];
    

    public function user()
    {
        return  $this->hasOne(User::class,'id','user_id');
    }
    public function documents()
    {
        return $this->hasOne(TenantDocument::class, 'employee_id', 'employee_id');
    }

}
