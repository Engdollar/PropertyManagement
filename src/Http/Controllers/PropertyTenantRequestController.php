<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\PropertyManagement\DataTables\PropertyTenantRequestDataTable;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyInvoice;
use Workdo\PropertyManagement\Entities\PropertyInvoicePayment;
use Workdo\PropertyManagement\Entities\PropertyTenantRequest;
use Workdo\PropertyManagement\Entities\PropertyUnit;
use Workdo\PropertyManagement\Entities\Tenant;
use Workdo\PropertyManagement\Entities\TenantDocument;
use Workdo\PropertyManagement\Entities\TenantDocumentType;
use Workdo\PropertyManagement\Events\CreatePropertyInvoice;
use Workdo\PropertyManagement\Events\CreatePropertyInvoicePayment;
use Workdo\PropertyManagement\Events\CreateTenant;
use Workdo\PropertyManagement\Events\DestroyTenantRequest;
use Workdo\PropertyManagement\Providers\PropertyManagementServiceProvider;

class PropertyTenantRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PropertyTenantRequestDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('tenant request manage')) {
            return $dataTable->render('property-management::tenant-request.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    public function convert($tenant_request)
    {
        if (Auth::user()->isAbleTo('tenant request convert')) {
            $tenant_request = PropertyTenantRequest::find($tenant_request);
            $properties = Property::where('id', $tenant_request->property_id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $properties->prepend(__('Select Property'), '');
            $documents        = TenantDocumentType::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
            $property = Property::where('id', $tenant_request->property_id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $security_deposit = isset($property->security_deposit) ? $property->security_deposit : '0';
            $maintenance_charge = isset($property->maintenance_charge) ? $property->maintenance_charge : '0';

            $units = PropertyUnit::where('property_id', $tenant_request->property_id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $unit = PropertyUnit::where('id', $tenant_request->unit_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->first();
            $unit_rent = isset($unit->rent) ? $unit->rent : '0';
            $unit_rent_type = isset($unit->rent_type) ? $unit->rent_type : '';
            if (module_is_active('CustomField')) {
                $customFields =  \Workdo\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Tenants')->get();
            } else {
                $customFields = null;
            }
            return view('property-management::tenant-request.tenant_convert', compact('tenant_request', 'properties', 'documents', 'property', 'security_deposit', 'maintenance_charge', 'units', 'unit', 'unit_rent', 'unit_rent_type','customFields'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function convertStore(Request $request, $id)
    {


        if (Auth::user()->isAbleTo('tenant request convert')) {
            $canUse =  PlanCheck('User', Auth::user()->id);
            if ($canUse == false) {
                return redirect()->back()->with('error', __('You have maxed out the total number of tenant allowed on your current plan'));
            }
            $rules = [
                'name' => 'required|string|max:180',
                'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                'address' => 'required|string|max:180',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'country' => 'required|string|max:100',
                'zip' => 'required|string|max:30',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if (empty($request->user_id)) {
                $rules = [
                    'email' => [
                        'required',
                        Rule::unique('users')->where(function ($query) {
                            return $query->where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace());
                        })
                    ],
                    'password' => 'required',
                    'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                ];
                $validator = \Validator::make($request->all(), $rules);
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->route('property-tenant-request.index')->with('error', $messages->first());
            }
            $roles = Role::where('name', 'tenant')->where('guard_name', 'web')->where('created_by', creatorId())->first();
            if (empty($roles)) {
                return redirect()->back()->with('error', __('Tenant Role Not found !'));
            }


            if (!empty($request->user_id)) {
                $user = User::find($request->user_id);

                if (empty($user)) {
                    return redirect()->back()->with('error', __('Something went wrong please try again.'));
                }
                if ($user->name != $request->name) {
                    $user->name = $request->name;
                    $user->save();
                }
                if ($user->mobile_no != $request->contact) {
                    $user->mobile_no = $request->contact;
                    $user->save();
                }
            } else {
                $user = User::create(
                    [
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'mobile_no' => $request['contact'],
                        'password' => Hash::make($request['password']),
                        'email_verified_at' => date('Y-m-d h:i:s'),
                        'type' => $roles->name,
                        'lang' => 'en',
                        'workspace_id' => getActiveWorkSpace(),
                        'active_workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ]
                );
                $user->save();
                $user->addRole($roles);
            }

            if (!empty($request->document) && !is_null($request->document)) {
                $document_implode = implode(',', array_keys($request->document));
            } else {
                $document_implode = null;
            }


            $tenant                  = new Tenant();
            $tenant->user_id         = $user->id;
            $tenant->property_id     = $request->property_id;
            $tenant->unit_id         = $request->unit_id;
            $tenant->total_family_member = !empty($request->total_member) ? $request->total_member : null;
            $tenant->country         = !empty($request->country) ? $request->country : null;
            $tenant->state           = !empty($request->state) ? $request->state : null;
            $tenant->city            = !empty($request->city) ? $request->city : null;
            $tenant->pincode         = !empty($request->zip) ? $request->zip : null;
            $tenant->address         = !empty($request->address) ? $request->address : null;
            $tenant->documents       = $document_implode;
            $tenant->lease_start_date      = Carbon::today();
            if ($request->rent_type == 'Monthly') {
                $tenant->lease_end_date        = Carbon::today()->addMonth();
            } elseif ($request->rent_type == 'Yearly') {
                $tenant->lease_end_date        = Carbon::today()->addYear();
            } else {
                $tenant->lease_end_date        = Carbon::today()->addMonths(3);
            }
            $tenant->workspace       = getActiveWorkSpace();
            $tenant->created_by      = creatorId();

            $tenant->save();
            if (module_is_active('CustomField')) {
                \Workdo\CustomField\Entities\CustomField::saveData($tenant, $request->customField);
            }
            if (isset($request->unit_id) && $request->unit_id != null) {
                $unit = PropertyUnit::find($request->unit_id);
                $unit->rentable_status = 'Occupied';
                $unit->save();
            }
            if ($request->hasFile('document')) {
                foreach ($request->document as $key => $document) {

                    $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . rand(1, 100) . '.' . $extension;

                    $uplaod = multi_upload_file($document, 'document', $fileNameToStore, 'tenant_document');
                    if ($uplaod['flag'] == 1) {
                        $url = $uplaod['url'];
                    } else {
                        return redirect()->back()->with('error', $uplaod['msg']);
                    }
                    $tenant_document = TenantDocument::create(
                        [
                            'user_id' => $tenant['id'],
                            'document_id' => $key,
                            'document_value' => !empty($url) ? $url : '',
                            'workspace' => $user->workspace_id,
                            'created_by' => creatorId(),
                        ]
                    );
                    $tenant_document->save();
                }
            }

            //Tenant Request Status Change
            $tenant_request                = PropertyTenantRequest::find($id);
            $tenant_request->status        =  'Approve';
            $tenant_request->save();


            // invoice
            $invoice = new PropertyInvoice();
            $invoice->user_id         = $tenant->id;
            $invoice->property_id     = $request->property_id;
            $invoice->unit_id         = $request->unit_id;
            $invoice->issue_date      = Carbon::today();
            if ($request->rent_type == 'Monthly') {
                $invoice->due_date        = Carbon::today()->addMonth();
            } elseif ($request->rent_type == 'Yearly') {
                $invoice->due_date        = Carbon::today()->addYear();
            } else {
                $invoice->due_date        = Carbon::today()->addMonths(3);
            }
            $invoice->status            = 'Paid';
            $invoice->total_amount    = !empty($tenant_request->total_amount) ? $tenant_request->total_amount : 0 ;
            $invoice->workspace       = getActiveWorkSpace();
            $invoice->created_by      = creatorId();
            $invoice->save();

            $invoice_payment                       = new PropertyInvoicePayment();
            $invoice_payment->invoice_id           = $invoice->id;
            $invoice_payment->user_id              = $invoice->user_id;
            $invoice_payment->date                 = date('Y-m-d');
            $invoice_payment->amount               = isset($invoice->total_amount) ? $invoice->total_amount : 0;
            $invoice_payment->payment_type         = __('Manually');
            $invoice_payment->receipt              = '';
            $invoice_payment->save();

            event(new CreateTenant($request,$tenant));
            event(new CreatePropertyInvoice($request,$invoice));

            $type = "propertyinvoice";
            event(new CreatePropertyInvoicePayment($request,$type,$invoice));

            return redirect()->back()->with('success', __('The tenant has been created successfully'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('tenant request delete')) {
            $tenant_request = PropertyTenantRequest::find($id);
            event(new DestroyTenantRequest($tenant_request));
            $tenant_request->delete();
            return redirect()->back()->with('success', __('The tenant request has been deleted'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
