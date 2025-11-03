<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\DataTables\PropertyInspectionDataTable;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyInspection;
use Workdo\PropertyManagement\Events\CreatePropertyInspection;
use Workdo\PropertyManagement\Events\DestroyPropertyInspection;
use Workdo\PropertyManagement\Events\UpdatePropertyInspection;

class PropertyInspectionController extends Controller
{


    public function index(PropertyInspectionDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('property inspections manage')) {
            return $dataTable->render('property-management::property-inspections.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('property inspections create')) {
            $properties = Property::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
            return view('property-management::property-inspections.create', compact('properties'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('property inspections create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_id' => 'required',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $property_inspection                        = new PropertyInspection();
            $property_inspection->property_id         = $request->property_id;
            $property_inspection->inspection_date            = $request->inspection_date;
            $property_inspection->inspector_name              = $request->inspector_name;
            $property_inspection->inspection_result     = $request->inspection_result;
            $property_inspection->comments         = $request->comments;
            $property_inspection->workspace             = getActiveWorkSpace();
            $property_inspection->created_by            = creatorId();
            $property_inspection->save();
            event(new CreatePropertyInspection($request,$property_inspection));

            return redirect()->route('property-inspections.index')->with('success', __('The Property Inspection has been created successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function show($id)
    {
        if (Auth::user()->isAbleTo('property inspections show')) {
            $property_inspection = PropertyInspection::with('property')->find($id);
            return view('property-management::property-inspections.show', compact('property_inspection'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('property inspections edit')) {
            $property_inspection = PropertyInspection::find($id);
            if ($property_inspection->created_by == creatorId() && $property_inspection->workspace == getActiveWorkSpace()) {
                $properties = Property::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
                return view('property-management::property-inspections.edit', compact('property_inspection', 'properties'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('property inspections edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $property_inspection                        = PropertyInspection::find($id);
            $property_inspection->property_id         = $request->property_id;
            $property_inspection->inspection_date            = $request->inspection_date;
            $property_inspection->inspector_name              = $request->inspector_name;
            $property_inspection->inspection_result     = $request->inspection_result;
            $property_inspection->comments         = $request->comments;
            $property_inspection->workspace             = getActiveWorkSpace();
            $property_inspection->created_by            = creatorId();
            $property_inspection->update();
            event(new UpdatePropertyInspection($request,$property_inspection));

            return redirect()->route('property-inspections.index')->with('success', __('The Property Inspection has been updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function destroy($id)
    {

        if (Auth::user()->isAbleTo('property inspections delete')) {
            $property_inspection = PropertyInspection::find($id);
            if ($property_inspection->created_by == creatorId()  && $property_inspection->workspace == getActiveWorkSpace()) {
                event(new DestroyPropertyInspection($property_inspection));

                $property_inspection->delete();
                return redirect()->route('property-inspections.index')->with('success', __('The Property Inspection has been deleted successfully'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
