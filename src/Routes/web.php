<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Workdo\PropertyManagement\Http\Controllers\ExpenseTrackingController;
use Workdo\PropertyManagement\Http\Controllers\PropertycontractorsController;
use Workdo\PropertyManagement\Http\Controllers\PropertyFrontController;
use Workdo\PropertyManagement\Http\Controllers\PropertyInspectionController;
use Workdo\PropertyManagement\Http\Controllers\PropertyInvoiceController;
use Workdo\PropertyManagement\Http\Controllers\PropertyListingController;
use Workdo\PropertyManagement\Http\Controllers\TenantController;
use Workdo\PropertyManagement\Http\Controllers\PropertyUnitController;
use Workdo\PropertyManagement\Http\Controllers\PropertyManagementController;
use Workdo\PropertyManagement\Http\Controllers\TenantDocumentTypeController;
use Workdo\PropertyManagement\Http\Controllers\PropertyMaintenanceRequestController;
use Workdo\PropertyManagement\Http\Controllers\PropertyTenantRequestController;
use Workdo\PropertyManagement\Http\Controllers\PropertyUtilitiesController;
use Workdo\PropertyManagement\Http\Controllers\TenantCommunicationController;

Route::middleware(['web'])->group(function () {
    Route::group(['middleware' => ['auth', 'verified','PlanModuleCheck:PropertyManagement']], function () {
        // dashboard
        Route::get('dashboard/property', [PropertyManagementController::class, 'dashboard'])->name('dashboard.property');

        // tenant
        Route::resource('tenant', TenantController::class);
        Route::get('tenant-grid', [TenantController::class, 'grid'])->name('tenant.grid');

        Route::resource('tenant-document-type', TenantDocumentTypeController::class)->middleware(
            [
                'auth'
            ]
        );

        Route::get('tenant/status/{id}', [TenantController::class,'status'])->name('tenant.status');
        Route::post('tenant/renew', [TenantController::class,'reNew'])->name('tenant.renew');
        Route::post('tenant/getproperty', [PropertyMaintenanceRequestController::class,'getProperty'])->name('property.getproperty');
        Route::post('tenant/getunit', [TenantController::class,'getUnit'])->name('property.getunit');
        Route::post('tenant/getunitrent', [TenantController::class,'getUnitRent'])->name('property.getunitrent');

        // property
        Route::resource('property', PropertyManagementController::class);
        Route::get('property-list', [PropertyManagementController::class, 'list'])->name('property.list');
        Route::delete('property-images/delete/{id}', [PropertyManagementController::class, 'propertyImageDelete'])->name('property.images.delete');

        // Unit
        Route::resource('property-unit', PropertyUnitController::class);

        // Maintenance Request
        Route::resource('property-maintenance-request', PropertyMaintenanceRequestController::class);
        Route::get('property-maintenance-request-description/{id}', [PropertyMaintenanceRequestController::class, 'showDescription'])->name('property.maintenance.request.description');

        // Invoice
        Route::resource('property-invoice', PropertyInvoiceController::class);
        Route::Put('property-invoice-status/{id}', [PropertyInvoiceController::class, 'statusUpdate'])->name('property.invoice.status.update');
        Route::get('property-invoice/pdf/{id}', [PropertyInvoiceController::class,'pdf'])->name('property.invoice.pdf');

        Route::resource('property-listing', PropertyListingController::class);
        Route::post('property/details', [PropertyListingController::class, 'propertydetails'])->name('property.details');
        Route::delete('property-listing-images/delete/{id}', [PropertyListingController::class, 'propertylistImageDelete'])->name('property.list.images.delete');

        Route::post('property-listing-update/{id}', [PropertyListingController::class, 'update'])->name('property.list.update');

        // Tenant Request
        Route::get('property-tenant-request', [PropertyTenantRequestController::class,'index'])->name('property-tenant-request.index');
        Route::delete('property-tenant-request/{id}/destroy', [PropertyTenantRequestController::class, 'destroy'])->name('property-tenant-request.destroy');
        Route::get('property-tenant-convert/{id}/convert', [PropertyTenantRequestController::class, 'convert'])->name('tenant.convert');
        Route::post('tenant-convert/{id}/store', [PropertyTenantRequestController::class, 'convertStore'])->name('tenant.convert.store');

        // expenxe
        Route::resource('expenses-tracking', ExpenseTrackingController::class);
        Route::get('expenses-tracking-description/{id}', [ExpenseTrackingController::class, 'showDescription'])->name('property.expenses.tracking.description');
        //inspection
        Route::resource('property-inspections', PropertyInspectionController::class);
        // tenant communication
        Route::resource('tenant-communications', TenantCommunicationController::class);
        Route::get('tenant-communications-message/{id}', [TenantCommunicationController::class, 'showMessage'])->name('tenant.communications.message');
        // utilities
        Route::resource('property-utilities', PropertyUtilitiesController::class);
        //contractor
        Route::resource('property-contractors', PropertycontractorsController::class);
    });

    // property-details
    Route::get('property/listing/{slug}/{lang?}', [PropertyFrontController::class,'copylink'])->name('property.listing');
    Route::get('property-change-language-store/{slug?}/{lang}', [PropertyFrontController::class, 'changeLanquageStore'])->name('property.change.languagestore');
    Route::get('/property-details/{slug}/{property_id}/{unit_id}/{lang?}', [PropertyFrontController::class,'property_details'])->name('get.property.detail');
    Route::get('/checkout-property/{slug}/{id}', [PropertyFrontController::class, 'Checkoutticket'])->name('checkout.property');
    Route::post('/property-booking-store/{slug}', [PropertyFrontController::class,'store'])->name('property.booking.store');
});
