<?php

namespace Workdo\PropertyManagement\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantCommunication extends Model
{
    use HasFactory;
    protected $table = 'tenant_communications';
    protected $guarded = [];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
