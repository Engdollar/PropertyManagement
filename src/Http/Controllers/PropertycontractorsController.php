<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\DataTables\PropertyContractorsDatatable;
use Workdo\PropertyManagement\Entities\PropertyContractor;
use Workdo\PropertyManagement\Events\CreatePropertyContractor;
use Workdo\PropertyManagement\Events\DestroyPropertyContractor;
use Workdo\PropertyManagement\Events\UpdatePropertyContractor;

class PropertycontractorsController extends Controller
{
    public function index(PropertyContractorsDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('property contractors manage')) {
            return $dataTable->render('property-management::property-contractors.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('property contractors create')) {
            return view('property-management::property-contractors.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('property contractors create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $property_contractor                        = new PropertyContractor();
            $property_contractor->name         = $request->name;
            $property_contractor->mobile_no            = $request->mobile_no;
            $property_contractor->start_date              = $request->start_date;
            $property_contractor->end_date     = $request->end_date;
            $property_contractor->service_type         = $request->service_type;
            $property_contractor->workspace             = getActiveWorkSpace();
            $property_contractor->created_by            = creatorId();
            $property_contractor->save();
            event(new CreatePropertyContractor($request,$property_contractor));

            return redirect()->route('property-contractors.index')->with('success', __('The Property Contractors has been created successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function show($id)
    {
        if (Auth::user()->isAbleTo('property contractors show')) {
            $property_contractor = PropertyContractor::find($id);
            return view('property-management::property-contractors.show', compact('property_contractor'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('property contractors edit')) {
            $property_contractor = PropertyContractor::find($id);
            if ($property_contractor->created_by == creatorId() && $property_contractor->workspace == getActiveWorkSpace()) {
                return view('property-management::property-contractors.edit', compact('property_contractor'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('property contractors edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $property_contractor                        = PropertyContractor::find($id);
            $property_contractor->name                  = $request->name;
            $property_contractor->mobile_no             = $request->mobile_no;
            $property_contractor->start_date            = $request->start_date;
            $property_contractor->end_date     = $request->end_date;
            $property_contractor->service_type         = $request->service_type;
            $property_contractor->workspace             = getActiveWorkSpace();
            $property_contractor->created_by            = creatorId();
            $property_contractor->update();
            event(new UpdatePropertyContractor($request,$property_contractor));

            return redirect()->route('property-contractors.index')->with('success', __('The Property Contractors has been updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('property contractors delete')) {
            $property_contractor = PropertyContractor::find($id);
            if ($property_contractor->created_by == creatorId()  && $property_contractor->workspace == getActiveWorkSpace()) {
                event(new DestroyPropertyContractor($property_contractor));

                $property_contractor->delete();
                return redirect()->route('property-contractors.index')->with('success', __('The Property Contractors has been deleted successfully'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
