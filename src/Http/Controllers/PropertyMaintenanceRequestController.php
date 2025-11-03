<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Workdo\PropertyManagement\DataTables\PropertyMaintenanceRequestDataTable;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyMaintenanceRequest;
use Workdo\PropertyManagement\Entities\PropertyUnit;
use Workdo\PropertyManagement\Entities\Tenant;
use Workdo\PropertyManagement\Events\CreateMaintenanceRequest;
use Workdo\PropertyManagement\Events\UpdateMaintenanceRequest;
use Workdo\PropertyManagement\Events\DestroyMaintenanceRequest;

class PropertyMaintenanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PropertyMaintenanceRequestDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('maintenance request manage')) {
            return $dataTable->render('property-management::maintenance-request.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('maintenance request create')) {
            if (Auth::user()->type == 'tenant') {
                $tenant = Tenant::where('user_id', Auth::user()->id)->first();
                $tenants = User::where('workspace_id', getActiveWorkSpace())
                    ->leftjoin('tenants', 'users.id', '=', 'tenants.user_id')
                    ->where('users.type', 'tenant')
                    ->where('tenants.id', $tenant['id'])
                    ->select('users.*', 'tenants.*', 'users.name as name', 'users.email as email', 'users.id as id', 'users.mobile_no as contact', 'tenants.id as tenant_id')
                    ->get()->pluck('name', 'tenant_id');
                $tenants->prepend(__('Select Tenant'), '');

                if (module_is_active('CustomField')) {
                    $customFields =  \Workdo\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Maintenance Request')->get();
                } else {
                    $customFields = null;
                }
            } else {
                $tenants = User::where('workspace_id', getActiveWorkSpace())
                    ->leftjoin('tenants', 'users.id', '=', 'tenants.user_id')
                    ->where('users.type', 'tenant')
                    ->select('users.*', 'tenants.*', 'users.name as name', 'users.email as email', 'users.id as id', 'users.mobile_no as contact', 'tenants.id as tenant_id')
                    ->get()->pluck('name', 'tenant_id');
                $tenants->prepend(__('Select Tenant'), '');
                if (module_is_active('CustomField')) {
                    $customFields =  \Workdo\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Maintenance Request')->get();

                } else {
                    $customFields = null;
                }
            }

            return view('property-management::maintenance-request.create', compact('tenants', 'customFields'));
        } else {
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
        if (Auth::user()->isAbleTo('maintenance request create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'tenant_id'     => 'required',
                    'property_id'   => 'required',
                    'unit_id'       => 'required',
                    'status'        => 'required',
                    'issue'         => 'required|string|max:150',
                    'description'   => 'required|string|max:255',
                    'attachment'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if ($request->attachment) {

                $filenameWithExt = time() . '_' . $request->file('attachment')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachment')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $url = '';
                $path = upload_file($request, 'attachment', $filenameWithExt, 'property_maintenance_image', []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            $maintenance                         = new PropertyMaintenanceRequest();
            $maintenance->user_id                = $request->tenant_id;
            $maintenance->property_id            = $request->property_id;
            $maintenance->unit_id                = $request->unit_id;
            $maintenance->issue                  = $request->issue;
            $maintenance->description_of_issue   = $request->description;
            $maintenance->request_date           = Carbon::today();
            $maintenance->status                 = $request->status;
            $maintenance->attachment             = isset($filenameWithExt) ? $filenameWithExt : '';
            $maintenance->workspace              = getActiveWorkSpace();
            $maintenance->created_by             = creatorId();
            $maintenance->save();

            if (module_is_active('CustomField')) {
                \Workdo\CustomField\Entities\CustomField::saveData($maintenance, $request->customField);
            }


            event(new CreateMaintenanceRequest($request,$maintenance));

            return redirect()->route('property-maintenance-request.index')->with('success', __('The maintenance request has been created successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('maintenance request show')) {
            $id       = \Crypt::decrypt($id);
            $maintenance        = PropertyMaintenanceRequest::where('id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
            $tenant = User::where('workspace_id', getActiveWorkSpace())
                ->leftjoin('tenants', 'users.id', '=', 'tenants.user_id')
                ->where('users.type', 'tenant')
                ->where('tenants.id', $maintenance['user_id'])
                ->select('users.*', 'tenants.*', 'users.name as name', 'users.email as email', 'users.id as id', 'users.mobile_no as contact', 'tenants.id as tenant_id')
                ->get()->pluck('name');
            $property = Property::find($maintenance['property_id']);
            $unit = PropertyUnit::find($maintenance['unit_id']);

            if (module_is_active('CustomField')) {
                $maintenance->customField = \Workdo\CustomField\Entities\CustomField::getData($maintenance, 'PropertyManagement', 'Maintenance Request');
                $customFields      = \Workdo\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Maintenance Request')->get();
            } else {
                $customFields = null;
            }

            return view('property-management::maintenance-request.show', compact('maintenance', 'tenant', 'property', 'unit', 'customFields'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('maintenance request edit')) {
            $maintenance        = PropertyMaintenanceRequest::where('id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
            $tenants = User::where('workspace_id', getActiveWorkSpace())
                ->leftjoin('tenants', 'users.id', '=', 'tenants.user_id')
                ->where('users.type', 'tenant')
                ->select('users.*', 'tenants.*', 'users.name as name', 'users.email as email', 'users.id as id', 'users.mobile_no as contact', 'tenants.id as tenant_id')
                ->get()->pluck('name', 'tenant_id');
            $tenants->prepend(__('Select Tenant'), '');
            $property = Property::find($maintenance->property_id);
            $unit = PropertyUnit::find($maintenance->unit_id);

            if (module_is_active('CustomField')) {
                $maintenance->customField = \Workdo\CustomField\Entities\CustomField::getData($maintenance, 'PropertyManagement', 'Maintenance Request');
                $customFields             = \Workdo\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Maintenance Request')->get();
            } else {
                $customFields = null;
            }
            return view('property-management::maintenance-request.edit', compact('maintenance', 'tenants', 'property', 'unit', 'customFields'));
        } else {
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
        if (Auth::user()->isAbleTo('maintenance request edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'tenant_id'     => 'required',
                    'property_id'   => 'required',
                    'unit_id'       => 'required',
                    'status'        => 'required',
                    'issue'         => 'required|string|max:150',
                    'description'   => 'required|string|max:255',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $maintenance                         = PropertyMaintenanceRequest::find($id);
            if (isset($request->attachment)) {

                $destinationPath = 'uploads/property_maintenance_image/' . $maintenance->attachment;
                if (File::exists($destinationPath)) {
                    File::delete($destinationPath);
                }

                $filenameWithExt = time() . '_' . $request->file('attachment')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachment')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $url = '';
                $path = upload_file($request, 'attachment', $filenameWithExt, 'property_maintenance_image', []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }

                $maintenance->attachment             = isset($filenameWithExt) ? $filenameWithExt : '';
            }

            $maintenance->user_id                = $request->tenant_id;
            $maintenance->property_id            = $request->property_id;
            $maintenance->unit_id                = $request->unit_id;
            $maintenance->issue                  = $request->issue;
            $maintenance->description_of_issue   = $request->description;
            $maintenance->request_date           = Carbon::today();
            $maintenance->status                 = $request->status;
            $maintenance->workspace              = getActiveWorkSpace();
            $maintenance->created_by             = creatorId();
            $maintenance->save();

            if (module_is_active('CustomField')) {
                \Workdo\CustomField\Entities\CustomField::saveData($maintenance, $request->customField);
            }

            event(new UpdateMaintenanceRequest($request,$maintenance));

            return redirect()->route('property-maintenance-request.index')->with('success', __('The maintenance request details are updated successfully'));
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
        if (Auth::user()->isAbleTo('maintenance request delete')) {
            $maintenance = PropertyMaintenanceRequest::find($id);
            if ($maintenance->workspace == getActiveWorkSpace()) {
                $destinationPath = 'uploads/property_maintenance_image/' . $maintenance->attachment;
                if (File::exists($destinationPath)) {
                    File::delete($destinationPath);
                }

                if (module_is_active('CustomField')) {
                    $customFields = \Workdo\CustomField\Entities\CustomField::where('module', 'PropertyManagement')->where('sub_module', 'Maintenance Request')->get();
                    foreach ($customFields as $customField) {
                        $value = \Workdo\CustomField\Entities\CustomFieldValue::where('record_id', '=', $maintenance->id)->where('field_id', $customField->id)->first();
                        if (!empty($value)) {
                            $value->delete();
                        }
                    }
                }

                event(new DestroyMaintenanceRequest($maintenance));

                $maintenance->delete();
                return redirect()->route('property-maintenance-request.index')->with('success', __('The maintenance request has been deleted'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getProperty(Request $request)
    {
        if ($request->tenant_id == 0) {
            $data = [];
        } else {
            $tenant = Tenant::find($request->tenant_id);
            $property = Property::where('id', $tenant->property_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            $unit = PropertyUnit::where('id', $tenant->unit_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

            $data = [$property, $unit];
        }
        return $data;
    }

    public function showDescription($id)
    {
        if (Auth::user()->isAbleTo('maintenance request manage')) {
            $id       = \Crypt::decrypt($id);
            $maintenance = PropertyMaintenanceRequest::where('id', $id)->where('created_by',creatorId())->where('workspace', getActiveWorkSpace())->first();
            return view('property-management::maintenance-request.description', compact('maintenance'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
}
