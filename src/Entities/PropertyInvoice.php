<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'unit_id',
        'issue_date',
        'due_date',
        'status',
        'total_amount',
        'workspace',
        'created_by',
    ];
    
    
    public static function tenantNumberFormat($number,$company_id = null,$workspace = null)
    {
        if(!empty($company_id) && empty($workspace))
        {
            $company_settings = getCompanyAllSetting($company_id);
        }
        elseif(!empty($company_id) && !empty($workspace))
        {
            $company_settings = getCompanyAllSetting($company_id,$workspace);
        }
        else
        {
            $company_settings = getCompanyAllSetting();
        }
        $data = !empty($company_settings['tenant_prefix']) ? $company_settings['tenant_prefix'] : '#PMS';

        return $data. sprintf("%05d", $number);
    }
    
    public function payments()
    {
        return $this->hasMany(PropertyInvoicePayment::class, 'invoice_id', 'id');
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'id', 'user_id');
    }

    public static function countPropertyInvoices()
    {
        return PropertyInvoice::where('workspace', '=', getActiveWorkSpace())->count();
    }

}
