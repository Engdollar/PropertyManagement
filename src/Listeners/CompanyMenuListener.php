<?php

namespace Workdo\PropertyManagement\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'PropertyManagement';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Property Manage'),
            'icon' => '',
            'name' => 'property-management-dashboard',
            'parent' => 'dashboard',
            'order' => 165,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'dashboard.property',
            'module' => $module,
            'permission' => 'property dashboard manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Property Manage'),
            'icon' => 'building-community',
            'name' => 'property-management',
            'parent' => null,
            'order' => 687,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'property manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Property'),
            'icon' => '',
            'name' => 'property',
            'parent' => 'property-management',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'property.index',
            'module' => $module,
            'permission' => 'property manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Units'),
            'icon' => '',
            'name' => 'all-unit',
            'parent' => 'property-management',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'property-unit.index',
            'module' => $module,
            'permission' => 'property unit manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Listing'),
            'icon' => '',
            'name' => 'listing',
            'parent' => 'property-management',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'property-listing.index',
            'module' => $module,
            'permission' => 'property listing manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Tenant'),
            'icon' => '',
            'name' => 'tenant',
            'parent' => 'property-management',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'tenant.index',
            'module' => $module,
            'permission' => 'tenant manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Invoice'),
            'icon' => '',
            'name' => 'property-invoice',
            'parent' => 'property-management',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'property-invoice.index',
            'module' => $module,
            'permission' => 'property invoice manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Maintenance Request'),
            'icon' => '',
            'name' => 'maintenance-request',
            'parent' => 'property-management',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'property-maintenance-request.index',
            'module' => $module,
            'permission' => 'maintenance request manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Document Type'),
            'icon' => '',
            'name' => 'tenant-document-type',
            'parent' => 'property-management',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'tenant-document-type.index',
            'module' => $module,
            'permission' => 'tenant document manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Tenant Request'),
            'icon' => '',
            'name' => 'tenant-request',
            'parent' => 'property-management',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'property-tenant-request.index',
            'module' => $module,
            'permission' => 'tenant request manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Expense Tracking'),
            'icon' => '',
            'name' => 'expenses-tracking',
            'parent' => 'property-management',
            'order' => 55,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'expenses-tracking.index',
            'module' => $module,
            'permission' => 'expenses tracking manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Inspections'),
            'icon' => '',
            'name' => 'property-inspections',
            'parent' => 'property-management',
            'order' => 60,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'property-inspections.index',
            'module' => $module,
            'permission' => 'property inspections manage'
        ]);
        // $menu->add([
        //     'category' => 'Operations',
        //     'title' => __('Tenant Communication'),
        //     'icon' => '',
        //     'name' => 'tenant-communications',
        //     'parent' => 'property-management',
        //     'order' => 65,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => 'tenant-communications.index',
        //     'module' => $module,
        //     'permission' => 'tenant communications manage'
        // ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('reports'),
            'icon' => '',
            'name' => 'tenant-communications',
            'parent' => 'property-management',
            'order' => 65,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.index',
            'module' => $module,
            'permission' => 'tenant communications manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Utilities'),
            'icon' => '',
            'name' => 'property-utilities',
            'parent' => 'property-management',
            'order' => 70,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'property-utilities.index',
            'module' => $module,
            'permission' => 'property utilities manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Contractors'),
            'icon' => '',
            'name' => 'property-contractors',
            'parent' => 'property-management',
            'order' => 75,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'property-contractors.index',
            'module' => $module,
            'permission' => 'property contractors manage'
        ]);
    }
}
