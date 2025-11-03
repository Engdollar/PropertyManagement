<?php

namespace Workdo\PropertyManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class PropertyUtility extends Model
{
    use HasFactory;

    public static function countTenants()
    {
        return Tenant::where('workspace', '=', getActiveWorkSpace())->count();
    }

    public static function countProperties()
    {
        return Property::where('workspace', '=', getActiveWorkSpace())->count();
    }

    public static function countUnits()
    {
        return PropertyUnit::where('workspace', '=', getActiveWorkSpace())->count();
    }

    public static function getInvExpLineChartDate()
    {
        $m = date("m");
        $de = date("d");
        $y = date("Y");
        $format = 'Y-m-d';
        $arrDate = [];
        $arrDateFormat = [];

        for ($i = 0; $i <= 15 - 1; $i++) {
            $date = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));

            $arrDay[] = date('D', mktime(0, 0, 0, $m, ($de - $i), $y));
            $arrDate[] = $date;
            $arrDateFormat[] = date("d-M", strtotime($date));
        }
        $dataArr['day'] = $arrDateFormat;

        for ($i = 0; $i < count($arrDate); $i++) {

            if(\Auth::user()->type != 'tenant'){
                $invoice = PropertyInvoice::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->whereRAW('issue_date = ?', $arrDate[$i])->count();
            }else{
                $tenant = Tenant::where('user_id',\Auth::user()->id)->first();
                $invoice = PropertyInvoice::where('user_id', $tenant->id ?? 0)->where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->whereRAW('issue_date = ?', $arrDate[$i])->count();
            }

            $totalInvoiceArr[] = $invoice;
        }

        $dataArr['totalInvoice'] = $totalInvoiceArr;

        return $dataArr;
    }



    public static function defaultdata($company_id = null, $workspace_id = null)
    {

        $tenant_permission = [
            'property manage',
            'property dashboard manage',
            'property show',
            'tenant manage',
            'tenant show',
            'property unit manage',
            'property unit show',
            'maintenance request manage',
            'maintenance request create',
            'maintenance request show',
            'property invoice manage',
            'property invoice create',
            'property invoice show',
            'property payment manage',
            'property payment create',
            'property payment edit',
            'property payment delete',
            'user profile manage',
            'workspace manage',
            'tenant request manage',
            'tenant request convert',
            'tenant request delete',
        ];

        if ($company_id == Null) {
            $companys = User::where('type', 'company')->get();
            foreach ($companys as $company) {
                $tenant_role = Role::where('name', 'tenant')->where('created_by', $company->id)->where('guard_name', 'web')->first();
                if (empty($tenant_role)) {
                    $tenant_role = new Role();
                    $tenant_role->name = 'tenant';
                    $tenant_role->guard_name = 'web';
                    $tenant_role->module = 'PropertyManagement';
                    $tenant_role->created_by = $company->id;
                    $tenant_role->save();

                    foreach ($tenant_permission as $permission_v) {
                        $permission = Permission::where('name', $permission_v)->first();
                        if (!empty($permission)) {
                            if (!$tenant_role->hasPermission($permission_v)) {
                                $tenant_role->givePermission($permission);
                            }
                        }
                    }
                }

            }
        } elseif ($workspace_id == Null) {
            $tenant_role = Role::where('name', 'tenant')->where('created_by', $company_id)->where('guard_name', 'web')->first();
            if (empty($tenant_role)) {
                $tenant_role = new Role();
                $tenant_role->name = 'tenant';
                $tenant_role->guard_name = 'web';
                $tenant_role->module = 'PropertyManagement';
                $tenant_role->created_by = $company_id;
                $tenant_role->save();

                foreach ($tenant_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$tenant_role->hasPermission($permission_v)) {
                            $tenant_role->givePermission($permission);
                        }
                    }
                }
            }

        } else {

            $tenant_role = Role::where('name', 'tenant')->where('created_by', $company_id)->where('guard_name', 'web')->first();
            if (empty($tenant_role)) {
                $tenant_role = new Role();
                $tenant_role->name = 'tenant';
                $tenant_role->guard_name = 'web';
                $tenant_role->module = 'PropertyManagement';
                $tenant_role->created_by = $company_id;
                $tenant_role->save();

                foreach ($tenant_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$tenant_role->hasPermission($permission_v)) {
                            $tenant_role->givePermission($permission);
                        }
                    }
                }
            }

        }
    }
    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $tenant_permission = [
            'property manage',
            'property dashboard manage',
            'property show',
            'tenant manage',
            'tenant show',
            'property unit manage',
            'property unit show',
            'maintenance request manage',
            'maintenance request create',
            'maintenance request show',
            'property invoice manage',
            'property invoice create',
            'property invoice show',
            'property payment manage',
            'property payment create',
            'property payment edit',
            'property payment delete',
            'user profile manage',
            'workspace manage',
            'tenant request manage',
            'tenant request convert',
            'tenant request delete',
        ];

        if ($role_id == Null) {
            // tenant
            $roles_v = Role::where('name', 'tenant')->get();

            foreach ($roles_v as $role) {
                foreach ($tenant_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$role->hasPermission($permission_v)) {
                            $role->givePermission($permission);
                        }
                    }
                }
            }
        } else {
            if ($rolename == 'tenant') {
                $roles_v = Role::where('name', 'tenant')->where('id', $role_id)->first();
                foreach ($tenant_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$roles_v->hasPermission($permission_v)) {
                            $roles_v->givePermission($permission);
                        }
                    }
                }
            }
        }
    }

}
