<?php

namespace Workdo\PropertyManagement\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class PermissionTableSeeder extends Seeder
{
     public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $module = 'PropertyManagement';

        $permissions  = [
            'propertymanagement manage',
            'property manage',
            'property dashboard manage',
            'property create',
            'property edit',
            'property show',
            'property delete',
            'tenant manage',
            'tenant create',
            'tenant edit',
            'tenant delete',
            'tenant show',
            'property unit manage',
            'property unit create',
            'property unit edit',
            'property unit delete',
            'property unit show',
            'lease manage',
            'lease create',
            'lease edit',
            'lease delete',
            'maintenance request manage',
            'maintenance request create',
            'maintenance request edit',
            'maintenance request show',
            'maintenance request delete',
            'tenant document manage',
            'tenant document create',
            'tenant document edit',
            'tenant document delete',
            'property invoice manage',
            'property invoice create',
            'property invoice edit',
            'property invoice delete',
            'property invoice show',
            'property payment manage',
            'property payment create',
            'property payment edit',
            'property payment delete',
            'property listing manage',
            'property listing create',
            'property listing edit',
            'property listing show',
            'property listing delete',
            'tenant request manage',
            'tenant request convert',
            'tenant request delete',
            'expenses tracking manage',
            'expenses tracking create',
            'expenses tracking edit',
            'expenses tracking show',
            'expenses tracking delete',
            'property inspections manage',
            'property inspections create',
            'property inspections edit',
            'property inspections show',
            'property inspections delete',
            'tenant communications manage',
            'tenant communications create',
            'tenant communications edit',
            'tenant communications show',
            'tenant communications delete',
            'property utilities manage',
            'property utilities create',
            'property utilities edit',
            'property utilities show',
            'property utilities delete',
            'property contractors manage',
            'property contractors create',
            'property contractors edit',
            'property contractors show',
            'property contractors delete',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permissions as $key => $value)
        {
            $check = Permission::where('name',$value)->where('module',$module)->exists();
            if($check == false)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => $module,
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$company_role->hasPermission($value))
                {
                    $company_role->givePermission($permission);
                }
            }
        }
    }
}
