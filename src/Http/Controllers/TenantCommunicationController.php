<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\DataTables\TenantCommunicationDataTable;
use Workdo\PropertyManagement\Entities\Tenant;
use Workdo\PropertyManagement\Entities\TenantCommunication;
use Workdo\PropertyManagement\Events\CreateTenantCommunication;
use Workdo\PropertyManagement\Events\DestroyTenantCommunication;
use Workdo\PropertyManagement\Events\UpdateTenantCommunication;

class TenantCommunicationController extends Controller
{
    public function index(TenantCommunicationDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('tenant communications manage')) {
            return $dataTable->render('property-management::tenant-communications.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('tenant communications create')) {
            $tenants = Tenant::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
            return view('property-management::tenant-communications.create', compact('tenants'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('tenant communications create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'tenant_id' => 'required',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $tenant_communication                           = new TenantCommunication();
            $tenant_communication->tenant_id              = $request->tenant_id;
            $tenant_communication->communication_date             = $request->communication_date;
            $tenant_communication->sender             = $request->sender;
            $tenant_communication->message               = $request->message;
            $tenant_communication->workspace                = getActiveWorkSpace();
            $tenant_communication->created_by               = creatorId();
            $tenant_communication->save();
            event(new CreateTenantCommunication($request,$tenant_communication));

            return redirect()->route('tenant-communications.index')->with('success', __('The Tenant Communication has been created successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function show($id)
    {
        if (Auth::user()->isAbleTo('tenant communications show')) {
            $tenant_communication = TenantCommunication::with('tenant')->find($id);
            return view('property-management::tenant-communications.show', compact('tenant_communication'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('tenant communications edit')) {
            $tenant_communication = TenantCommunication::find($id);
            if ($tenant_communication->created_by == creatorId() && $tenant_communication->workspace == getActiveWorkSpace()) {
                $tenants = Tenant::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
                return view('property-management::tenant-communications.edit', compact('tenant_communication', 'tenants'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('tenant communications edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'tenant_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $tenant_communication                        = TenantCommunication::find($id);
            $tenant_communication->tenant_id              = $request->tenant_id;
            $tenant_communication->communication_date             = $request->communication_date;
            $tenant_communication->sender             = $request->sender;
            $tenant_communication->message               = $request->message;
            $tenant_communication->workspace             = getActiveWorkSpace();
            $tenant_communication->created_by            = creatorId();
            $tenant_communication->update();
            event(new UpdateTenantCommunication($request,$tenant_communication));

            return redirect()->route('tenant-communications.index')->with('success', __('The Tenant Communication has been updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function destroy($id)
    {

        if (Auth::user()->isAbleTo('tenant communications delete')) {
            $tenant_communication = TenantCommunication::find($id);
            if ($tenant_communication->created_by == creatorId()  && $tenant_communication->workspace == getActiveWorkSpace()) {
                event(new DestroyTenantCommunication($tenant_communication));

                $tenant_communication->delete();
                return redirect()->route('tenant-communications.index')->with('success', __('The Tenant Communication has been deleted successfully'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function showMessage($id)
    {
        if (Auth::user()->isAbleTo('tenant communications manage')) {
            $id       = \Crypt::decrypt($id);
            $tenant_communication = TenantCommunication::where('id', $id)->where('created_by',creatorId())->where('workspace', getActiveWorkSpace())->first();
            return view('property-management::tenant-communications.message', compact('tenant_communication'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
}
