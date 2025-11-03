<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Workdo\PropertyManagement\DataTables\TenantDataTable;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyInvoice;
use Workdo\PropertyManagement\Entities\PropertyUnit;
use Workdo\PropertyManagement\Entities\Tenant;
use Workdo\PropertyManagement\Entities\TenantDocument;
use Workdo\PropertyManagement\Entities\TenantDocumentType;
use Workdo\PropertyManagement\Events\CreatePropertyInvoice;
use Workdo\PropertyManagement\Events\CreateTenant;
use Workdo\PropertyManagement\Events\DestroyTenant;
use Workdo\PropertyManagement\Events\UpdatePropertyInvoice;
use Workdo\PropertyManagement\Events\UpdateTenant;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(TenantDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('tenant manage'))
        {
            return $dataTable->render('property-management::tenant.index');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if(Auth::user()->isAbleTo('tenant create'))
        {
            $documents        = TenantDocumentType::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
            $properties = Property::where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $properties->prepend(__('Select Property'), '');
            if (module_is_active('CustomField')) {
                $customFields =  \Workdo\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Tenants')->get();
            } else {
                $customFields = null;
            }
            return view('property-management::tenant.create', compact('documents','properties','customFields'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('tenant create'))
        {
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
            if(empty($request->user_id))
            {
                $rules = [
                    'email' => ['required',
                                        Rule::unique('users')->where(function ($query) {
                                        return $query->where('created_by', creatorId())->where('workspace_id',getActiveWorkSpace());
                                    })
                        ],
                    'password' => 'required',
                    'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                ];
                $validator = \Validator::make($request->all(), $rules);
            }

            if ($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->route('tenant.index')->with('error', $messages->first());
            }
            $roles = Role::where('name','tenant')->where('guard_name','web')->where('created_by',creatorId())->first();
            if(empty($roles))
            {
                return redirect()->back()->with('error', __('Tenant Role Not found !'));
            }
            if(!empty($request->user_id))
            {
                $user = User::find($request->user_id);

                if(empty($user))
                {
                    return redirect()->back()->with('error', __('Something went wrong please try again.'));
                }
                if($user->name != $request->name)
                {
                    $user->name = $request->name;
                    $user->save();
                }
                if($user->mobile_no != $request->contact)
                {
                    $user->mobile_no = $request->contact;
                    $user->save();
                }
            }
            else
            {
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
                        'active_workspace' =>getActiveWorkSpace(),
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
                $tenant->total_family_member         = !empty($request->total_member) ? $request->total_member : null;
                $tenant->country         = !empty($request->country) ? $request->country : null;
                $tenant->state           = !empty($request->state) ? $request->state : null;
                $tenant->city            = !empty($request->city) ? $request->city : null;
                $tenant->pincode         = !empty($request->zip) ? $request->zip : null;
                $tenant->address         = !empty($request->address) ? $request->address : null;
                $tenant->documents       = $document_implode;
                $tenant->lease_start_date      = Carbon::today();
                if($request->rent_type == 'Monthly'){
                    $tenant->lease_end_date        = Carbon::today()->addMonth();
                }elseif($request->rent_type == 'Yearly'){
                    $tenant->lease_end_date        = Carbon::today()->addYear();
                }else{
                    $tenant->lease_end_date        = Carbon::today()->addMonths(3);
                }

                $tenant->workspace       = getActiveWorkSpace();
                $tenant->created_by      = creatorId();

                $tenant->save();
                if (module_is_active('CustomField')) {
                    \Workdo\CustomField\Entities\CustomField::saveData($tenant, $request->customField);
                }
                if(isset($request->unit_id) && $request->unit_id != null){
                    $unit = PropertyUnit::find($request->unit_id);
                    $unit->rentable_status = 'Occupied';
                    $unit->save();
                }
                if ($request->hasFile('document')) {
                    foreach ($request->document as $key => $document) {

                        $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . rand(1,100) . '.' . $extension;

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

                // invoice
                $invoice = new PropertyInvoice();
                $invoice->user_id         = $tenant->id;
                $invoice->property_id     = $request->property_id;
                $invoice->unit_id         = $request->unit_id;
                $invoice->issue_date      = Carbon::today();
                if($request->rent_type == 'Monthly'){
                    $invoice->due_date        = Carbon::today()->addMonth();
                }elseif($request->rent_type == 'Yearly'){
                    $invoice->due_date        = Carbon::today()->addYear();
                }else{
                    $invoice->due_date        = Carbon::today()->addMonths(3);
                }
                $invoice->status            = 'Pending';
                $maintenance_charge = !empty($request->maintenance_charge) ? $request->maintenance_charge : 0;
                $invoice->total_amount         = !empty($request->total) ? $request->total + $maintenance_charge : 0 + $maintenance_charge;
                $invoice->workspace       = getActiveWorkSpace();
                $invoice->created_by      = creatorId();
                $invoice->save();


                event(new CreateTenant($request,$tenant));
                event(new CreatePropertyInvoice($request,$invoice));

                //Email notification
                if(!empty(company_setting('New Property Invoice', $invoice->created_by, $invoice->workspace)) && company_setting('New Property Invoice', $invoice->created_by, $invoice->workspace)  == true)
                {
                    $uArr = [
                        'invoice_id' => \Workdo\PropertyManagement\Entities\PropertyInvoice::tenantNumberFormat($invoice->id),
                        'invoice_tenant' => isset($user->name) ? $user->name : '',
                        'invoice_status' => $invoice->status,
                        'invoice_sub_total' => $invoice->total_amount,
                        'created_at' => $invoice->issue_date,
                    ];

                    try
                    {
                        $resp = EmailTemplate::sendEmailTemplate('New Property Invoice', [$user->email],$uArr);
                    }
                    catch(\Exception $e)
                    {
                        $resp['error'] = $e->getMessage();
                    }

                    return redirect()->back()->with('success', __('The tenant has been created successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                }

            return redirect()->back()->with('success', __('The tenant has been created successfully. email notification is off.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request, $e_id)
    {
        if (Auth::user()->isAbleTo('tenant show'))
        {
            $id       = \Crypt::decrypt($e_id);
            $tenant = User::where('users.id',$id)
                        ->leftjoin('tenants', 'users.id', '=', 'tenants.user_id')
                        ->where('users.type', 'tenant')
                        ->select('users.*','tenants.*', 'users.name as name', 'users.email as email', 'users.id as id','users.mobile_no as contact','tenants.id as tenant_id')
                        ->first();

            $documents    = TenantDocumentType::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();

            $data['from_date']  = date('Y-m-1');
            $data['until_date'] = date('Y-m-t');
            if (module_is_active('CustomField')) {
                $tenant->customField = \Workdo\CustomField\Entities\CustomField::getData($tenant, 'PropertyManagement', 'Tenants');
                $customFields      = \Workdo\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Tenants')->get();
            } else {
                $customFields = null;
            }
            return view('property-management::tenant.show', compact('tenant','data','documents','customFields'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('tenant edit'))
        {
            $user         = User::where('id',$id)->where('workspace_id',getActiveWorkSpace())->first();
            $tenant     = Tenant::where('user_id',$id)->where('workspace',getActiveWorkSpace())->first();
            $document_types = TenantDocumentType::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
            $properties = Property::where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $properties->prepend(__('Select Property'), '');
            if(isset($tenant)){
                $property = Property::where('id',$tenant->property_id)->where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->first();
                $security_deposit = isset($property->security_deposit) ? $property->security_deposit : '0';
                $maintenance_charge = isset($property->maintenance_charge) ? $property->maintenance_charge : '0';

                $units = PropertyUnit::where('property_id',$tenant->property_id)->where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
                $unit = PropertyUnit::where('id',$tenant->unit_id)->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->first();
                $unit_rent = isset($unit->rent) ? $unit->rent : '0';
                $unit_rent_type = isset($unit->rent_type) ? $unit->rent_type : '';
            }else{
                $security_deposit = '0';
                $maintenance_charge = '0';

                $units = [];
                $unit_rent = '0';
                $unit_rent_type = '';
            }
            if(isset($tenant) && !empty($tenant)){

                if(module_is_active('CustomField')){
                    $tenant->customField = \Workdo\CustomField\Entities\CustomField::getData($tenant, 'PropertyManagement', 'Tenants');
                    $customFields             = \Workdo\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Tenants')->get();
                }else{
                    $customFields = null;
                }
            }else{
                if(module_is_active('CustomField')){
                    $customFields =  \Workdo\CustomField\Entities\CustomField::where('workspace_id',getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Tenants')->get();
                }else{
                    $customFields = null;
                }
            }
            return view('property-management::tenant.edit', compact('tenant', 'user', 'document_types', 'properties', 'units', 'unit_rent', 'unit_rent_type','security_deposit','maintenance_charge','customFields'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('tenant edit'))
        {
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
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $user = User::where('id',$request->user_id)->first();
            if(empty($user))
            {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            if($user->name != $request->name)
            {
                $user->name = $request->name;
                $user->save();
            }
            if($user->mobile_no != $request->contact)
            {
                $user->mobile_no = $request->contact;
                $user->save();
            }

            if (!empty($request->document) && !is_null($request->document)) {
                $document_implode = implode(',', array_keys($request->document));
            } else {
                $document_implode = null;
            }

            $tenant                   = Tenant::find($id);
            if (module_is_active('CustomField')) {
                \Workdo\CustomField\Entities\CustomField::saveData($tenant, $request->customField);
            }

            if ($request->document) {
                foreach ($request->document as $key => $document) {
                    if (!empty($document)) {
                        $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . rand(1,100) . '.' . $extension;

                        $uplaod = multi_upload_file($document, 'document', $fileNameToStore, 'tenant_document');
                        if ($uplaod['flag'] == 1) {
                            $url = $uplaod['url'];
                        } else {
                            return redirect()->back()->with('error', $uplaod['msg']);
                        }

                        $tenant_document = TenantDocument::where('user_id', $tenant->id)->where('document_id', $key)->first();

                        if (!empty($tenant_document)) {
                            if (!empty($tenant_document->document_value)) {
                                delete_file($tenant_document->document_value);
                            }
                            $tenant_document->document_value = $url;
                            $tenant_document->save();
                        } else {
                            $tenant_document                 = new TenantDocument();
                            $tenant_document->user_id      = $tenant->id;
                            $tenant_document->document_id    = $key;
                            $tenant_document->document_value = $url;
                            $tenant_document->save();
                        }
                    }
                }
            }

            if($tenant->unit_id == $request->unit_id){
                if($tenant->lease_end_date == null || $tenant->lease_end_date <= Carbon::today()){
                    $tenant->lease_start_date      = Carbon::today();
                    if($request->rent_type == 'Monthly'){
                        $tenant->lease_end_date        = Carbon::today()->addMonth();
                    }elseif($request->rent_type == 'Yearly'){
                        $tenant->lease_end_date        = Carbon::today()->addYear();
                    }else{
                        $tenant->lease_end_date        = Carbon::today()->addMonths(3);
                    }
                }
            }else{
                $tenant->lease_start_date      = Carbon::today();
                if($request->rent_type == 'Monthly'){
                    $tenant->lease_end_date        = Carbon::today()->addMonth();
                }elseif($request->rent_type == 'Yearly'){
                    $tenant->lease_end_date        = Carbon::today()->addYear();
                }else{
                    $tenant->lease_end_date        = Carbon::today()->addMonths(3);
                }
            }

            $tenant->property_id      = $request->property_id;
            $tenant->unit_id          = $request->unit_id;
            $tenant->total_family_member          = $request->total_member;
            $tenant->country          = $request->country;
            $tenant->state            = $request->state;
            $tenant->city             = $request->city;
            $tenant->pincode          = $request->zip;
            $tenant->address          = $request->address;
            $tenant->documents        = $document_implode;

            $tenant->save();

            // invoice
            $invoice = PropertyInvoice::where('user_id',$id)->latest()->first();
            if ($invoice == null){
                $invoice = new PropertyInvoice();
                $invoice->issue_date      = Carbon::today();
                if($request->rent_type == 'Monthly'){
                    $invoice->due_date        = Carbon::today()->addMonth();
                }elseif($request->rent_type == 'Yearly'){
                    $invoice->due_date        = Carbon::today()->addYear();
                }else{
                    $invoice->due_date        = Carbon::today()->addMonths(3);
                }
                $invoice->workspace       = getActiveWorkSpace();
                $invoice->created_by      = \Auth::user()->id;
            }elseif($invoice->unit_id == $request->unit_id){
                if($invoice->due_date == null || $invoice->due_date <= Carbon::today()){
                    $invoice->issue_date      = Carbon::today();
                    if($request->rent_type == 'Monthly'){
                        $invoice->due_date        = Carbon::today()->addMonth();
                    }elseif($request->rent_type == 'Yearly'){
                        $invoice->due_date        = Carbon::today()->addYear();
                    }else{
                        $invoice->due_date        = Carbon::today()->addMonths(3);
                    }
                }
            }else{
                $invoice->issue_date      = Carbon::today();
                if($request->rent_type == 'Monthly'){
                    $invoice->due_date        = Carbon::today()->addMonth();
                }elseif($request->rent_type == 'Yearly'){
                    $invoice->due_date        = Carbon::today()->addYear();
                }else{
                    $invoice->due_date        = Carbon::today()->addMonths(3);
                }
            }
            $invoice->user_id         = $tenant->id;
            $invoice->property_id     = $request->property_id;
            $invoice->unit_id         = $request->unit_id;
            $invoice->status            = 'Pending';
            $maintenance_charge = !empty($request->maintenance_charge) ? $request->maintenance_charge : 0;
            $invoice->total_amount         = !empty($request->total) ? $request->total + $maintenance_charge : 0 + $maintenance_charge;
            $invoice->save();


            if(isset($request->unit_id) && $request->unit_id != null){
                $unit = PropertyUnit::find($request->unit_id);
                $unit->rentable_status = 'Occupied';
                $unit->save();
            }
            event(new UpdateTenant($request,$tenant));
            event(new UpdatePropertyInvoice($request,$invoice));

            //Email notification
            if(!empty(company_setting('New Property Invoice', $invoice->created_by, $invoice->workspace)) && company_setting('New Property Invoice', $invoice->created_by, $invoice->workspace)  == true)
            {
                $uArr = [
                    'invoice_id' => \Workdo\PropertyManagement\Entities\PropertyInvoice::tenantNumberFormat($invoice->id),
                    'invoice_tenant' => isset($user->name) ? $user->name : '',
                    'invoice_status' => $invoice->status,
                    'invoice_sub_total' => $invoice->total_amount,
                    'created_at' => $invoice->issue_date,
                ];

                try
                {
                    $resp = EmailTemplate::sendEmailTemplate('New Property Invoice', [$user->email],$uArr);
                }
                catch(\Exception $e)
                {
                    $resp['error'] = $e->getMessage();
                }

                return redirect()->back()->with('success', __('The tenant details are updated successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
            }

            return redirect()->back()->with('success', __('The tenant details are updated successfully. email notification is off.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('tenant delete'))
        {
            $tenant = Tenant::where('user_id',$id)->first();
            if($tenant->workspace == getActiveWorkSpace())
            {
                $tenant_unit = Tenant::where('user_id','!=',$id)->where('unit_id',$tenant->unit_id)->count();
                if($tenant_unit == 0){
                    $unit = PropertyUnit::find($tenant->unit_id);
                    if (isset($unit) && !empty($unit)){
                        $unit->rentable_status = 'Vacant';
                        $unit->save();
                    }
                }
                $tenant_documents = TenantDocument::where('user_id', $tenant->id)->get();
                foreach ($tenant_documents as $key => $file) {
                    if (File::exists($file->document_value)) {
                        File::delete($file->document_value);
                    }
                    $file->delete();
                }
                if (module_is_active('CustomField')) {
                    $customFields = \Workdo\CustomField\Entities\CustomField::where('module', 'PropertyManagement')->where('sub_module', 'Tenants')->get();
                    foreach ($customFields as $customField) {
                        $value = \Workdo\CustomField\Entities\CustomFieldValue::where('record_id', '=', $tenant->id)->where('field_id', $customField->id)->first();
                        if (!empty($value)) {
                            $value->delete();
                        }
                    }
                }
                event(new DestroyTenant($tenant));
                $tenant->delete();
                return redirect()->route('tenant.index')->with('success', __('The tenant has been deleted'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function grid()
    {
        if (Auth::user()->isAbleTo('tenant manage'))
        {
            if (Auth::user()->type == 'tenant'){
                $tenants = User::where('workspace_id',getActiveWorkSpace())
                            ->leftjoin('tenants', 'users.id', '=', 'tenants.user_id')
                            ->where('users.type', 'tenant')
                            ->where('users.id', Auth::user()->id)
                            ->select('users.*','tenants.*', 'users.name as name', 'users.email as email', 'users.id as id','users.mobile_no as contact','tenants.id as tenant_id');
            }else{
                $tenants = User::where('workspace_id',getActiveWorkSpace())
                    ->leftjoin('tenants', 'users.id', '=', 'tenants.user_id')
                    ->where('users.type', 'tenant')
                    ->select('users.*','tenants.*', 'users.name as name', 'users.email as email', 'users.id as id','users.mobile_no as contact','tenants.id as tenant_id');
            }
            $tenants = $tenants->paginate(11);
            return view('property-management::tenant.grid', compact('tenants'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getUnit(Request $request)
    {
        if($request->property_id == 0)
        {
            $units = PropertyUnit::where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $units = PropertyUnit::where('property_id', $request->property_id)->where('rentable_status','Vacant')->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();

        }
        return response()->json($units);
    }

    public function getUnitRent(Request $request)
    {
        if($request->unit_id == 0)
        {
            $unit = ['rent'=>'0','rent_type'=>''];
            $property = ['security_deposit'=>'0','maintenance_charge'=>'0'];
            $data = [$unit,$property];
        }
        else
        {
            $unit = PropertyUnit::where('id',$request->unit_id)->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->first();
            $property = Property::where('id',$unit->property_id)->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->first();
            $data = [$unit,$property];
        }
        return $data;
    }

    public function status($id)
    {
        if (Auth::user()->isAbleTo('tenant manage'))
        {
            $tenant = User::where('users.id',$id)
                        ->leftjoin('tenants', 'users.id', '=', 'tenants.user_id')
                        ->where('users.type', 'tenant')
                        ->select('users.*','tenants.*', 'users.name as name', 'users.email as email', 'users.id as id','users.mobile_no as contact','tenants.id as tenant_id')
                        ->first();
            $property = Property::find($tenant['property_id']);
            $unit = PropertyUnit::find($tenant['unit_id']);

            return view('property-management::tenant.status', compact('tenant','property','unit'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function reNew(Request $request)
    {
        if (Auth::user()->isAbleTo('tenant manage'))
        {
            $unit = PropertyUnit::find($request->unit_id);
            if($request->status == 'renew'){
                $tenant = Tenant::find($request->tenant_id);
                $tenant->lease_start_date      = Carbon::today();
                if($unit->rent_type == 'Monthly'){
                    $tenant->lease_end_date        = Carbon::today()->addMonth();
                }elseif($unit->rent_type == 'Yearly'){
                    $tenant->lease_end_date        = Carbon::today()->addYear();
                }else{
                    $tenant->lease_end_date        = Carbon::today()->addMonths(3);
                }
                $tenant->save();
                if (module_is_active('CustomField')) {
                    \Workdo\CustomField\Entities\CustomField::saveData($tenant, $request->customField);
                }
                $property = Property::find($tenant->property_id);
                // invoice
                $invoice = new PropertyInvoice();
                $invoice->user_id         = $tenant->id;
                $invoice->property_id     = $tenant->property_id;
                $invoice->unit_id         = $tenant->unit_id;
                $invoice->issue_date      = Carbon::today();
                if($unit->rent_type == 'Monthly'){
                    $invoice->due_date        = Carbon::today()->addMonth();
                }elseif($unit->rent_type == 'Yearly'){
                    $invoice->due_date        = Carbon::today()->addYear();
                }else{
                    $invoice->due_date        = Carbon::today()->addMonths(3);
                }
                $invoice->status            = 'Pending';

                $maintenance_charge = !empty($property->maintenance_charge) ? $property->maintenance_charge : 0;
                $invoice->total_amount         = !empty($unit->rent) ? $unit->rent + $maintenance_charge : 0 + $maintenance_charge;
                $invoice->workspace       = getActiveWorkSpace();
                $invoice->created_by      = creatorId();
                $invoice->save();

                event(new CreatePropertyInvoice($request,$invoice));

                //Email notification
                if(!empty(company_setting('New Property Invoice', $invoice->created_by, $invoice->workspace)) && company_setting('New Property Invoice', $invoice->created_by, $invoice->workspace)  == true)
                {
                    $user = User::where('id',$tenant->user_id)->first();
                    $uArr = [
                        'invoice_id' => \Workdo\PropertyManagement\Entities\PropertyInvoice::tenantNumberFormat($invoice->id),
                        'invoice_tenant' => isset($user->name) ? $user->name : '',
                        'invoice_status' => $invoice->status,
                        'invoice_sub_total' => $invoice->total_amount,
                        'created_at' => $invoice->issue_date,
                    ];

                    try
                    {
                        $resp = EmailTemplate::sendEmailTemplate('New Property Invoice', [$user->email],$uArr);
                    }
                    catch(\Exception $e)
                    {
                        $resp['error'] = $e->getMessage();
                    }

                    return redirect()->back()->with('success', __('Successfully Renewal!') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                }

                return response()->json([
                    'flag' => 'success',
                    'status' => true,
                    'msg' =>  __('Successfully Renewal! email notification is off.')
                ]);
            }else{
                $unit->rentable_status = 'Vacant';
                $unit->save();

                return response()->json([
                    'flag' => 'success',
                    'status' => true,
                    'msg' =>  __('Successfully Cancelled!')
                ]);
            }
        }else{
            return response()->json([
                'flag' => 'error',
                'status' => false,
                'msg' =>  __('Permission denied!')
            ]);
        }
    }
}
