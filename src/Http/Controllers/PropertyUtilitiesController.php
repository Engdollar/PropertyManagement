<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\DataTables\PropertyUtilitiesDataTable;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyUtilities;
use Workdo\PropertyManagement\Events\CreatePropertyUtilities;
use Workdo\PropertyManagement\Events\DestroyPropertyUtilities;
use Workdo\PropertyManagement\Events\UpdatePropertyUtilities;

class PropertyUtilitiesController extends Controller
{
    public function index(PropertyUtilitiesDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('property utilities manage')) {
            return $dataTable->render('property-management::property-utilities.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('property utilities create')) {
            $properties = Property::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
            return view('property-management::property-utilities.create', compact('properties'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('property utilities create')) {
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
            $property_utility                        = new PropertyUtilities();
            $property_utility->property_id         = $request->property_id;
            $property_utility->utility_type            = $request->utility_type;
            $property_utility->reading_date              = $request->reading_date;
            $property_utility->amount_due     = $request->amount_due;
            $property_utility->current_reading         = $request->current_reading;
            $property_utility->previous_reading         = $request->previous_reading;
            $property_utility->workspace             = getActiveWorkSpace();
            $property_utility->created_by            = creatorId();
            $property_utility->save();
            event(new CreatePropertyUtilities($request,$property_utility));

            return redirect()->route('property-utilities.index')->with('success', __('The Property Utilities has been created successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function show($id)
    {
        if (Auth::user()->isAbleTo('property utilities show')) {
            $property_utility = PropertyUtilities::with('property')->find($id);
            return view('property-management::property-utilities.show', compact('property_utility'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('property utilities edit')) {
            $property_utility = PropertyUtilities::find($id);
            if ($property_utility->created_by == creatorId() && $property_utility->workspace == getActiveWorkSpace()) {
                $properties = Property::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
                return view('property-management::property-utilities.edit', compact('property_utility', 'properties'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('property utilities edit')) {
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
            $property_utility                        = PropertyUtilities::find($id);
            $property_utility->property_id         = $request->property_id;
            $property_utility->utility_type            = $request->utility_type;
            $property_utility->reading_date              = $request->reading_date;
            $property_utility->amount_due     = $request->amount_due;
            $property_utility->current_reading         = $request->current_reading;
            $property_utility->previous_reading         = $request->previous_reading;
            $property_utility->workspace             = getActiveWorkSpace();
            $property_utility->created_by            = creatorId();
            $property_utility->update();
            event(new UpdatePropertyUtilities($request,$property_utility));

            return redirect()->route('property-utilities.index')->with('success', __('The Property Utilities has been updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function destroy($id)
    {

        if (Auth::user()->isAbleTo('property utilities delete')) {
            $property_utility = PropertyUtilities::find($id);
            if ($property_utility->created_by == creatorId()  && $property_utility->workspace == getActiveWorkSpace()) {
                event(new DestroyPropertyUtilities($property_utility));

                $property_utility->delete();
                return redirect()->route('property-utilities.index')->with('success', __('The Property Utilities has been deleted successfully'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
