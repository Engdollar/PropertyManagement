<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\DataTables\PropertyUnitDataTable;
use Workdo\PropertyManagement\Entities\Tenant;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyInvoice;
use Workdo\PropertyManagement\Entities\PropertyLists;
use Workdo\PropertyManagement\Entities\PropertyUnit;
use Workdo\PropertyManagement\Events\CreatePropertyUnit;
use Workdo\PropertyManagement\Events\DestroyPropertyUnit;
use Workdo\PropertyManagement\Events\UpdatePropertyUnit;

class PropertyUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PropertyUnitDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('property unit manage')) {
            return $dataTable->render('property-management::unit.index');
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
        if (Auth::user()->isAbleTo('property unit create')) {
            $properties = Property::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $properties->prepend(__('Select Property'), '');
            $amenities = ['Balcony' => __('Balcony'), 'Parking' => __('Parking'), 'Laundry' => __('Laundry')];
            if (module_is_active('CustomField')) {
                $customFields =  \Workdo\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Units')->get();
            } else {
                $customFields = null;
            }
            return view('property-management::unit.create', compact('properties', 'amenities', 'customFields'));
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
        if (Auth::user()->isAbleTo('property unit create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_id' => 'required',
                    'unit_name' => 'required',
                    'bedroom' => 'required',
                    'baths' => 'required',
                    'kitchen' => 'required',
                    'amenities' => 'required',
                    'rent_type' => 'required',
                    'rent' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $unit                     = new PropertyUnit();
            $unit->property_id        = $request->property_id;
            $unit->name               = $request->unit_name;
            $unit->bedroom            = $request->bedroom;
            $unit->baths              = $request->baths;
            $unit->kitchen            = $request->kitchen;
            $unit->amenities          = implode(",", array_filter($request->amenities));
            $unit->rent_type          = $request->rent_type;
            $unit->rent               = $request->rent;
            $unit->utilities_included = $request->utilities_included;
            $unit->description        = $request->description;
            $unit->workspace          = getActiveWorkSpace();
            $unit->created_by         = creatorId();
            $unit->save();
            if (module_is_active('CustomField')) {
                \Workdo\CustomField\Entities\CustomField::saveData($unit, $request->customField);
            }
            event(new CreatePropertyUnit($request, $unit));

            return redirect()->route('property-unit.index')->with('success', __('The unit has been created successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (Auth::user()->isAbleTo('property unit show')) {
            $id       = \Crypt::decrypt($id);
            $unit = PropertyUnit::find($id);
            if (module_is_active('CustomField')) {
                $unit->customField = \Workdo\CustomField\Entities\CustomField::getData($unit, 'PropertyManagement', 'Units');
                $customFields      = \Workdo\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Units')->get();
            } else {
                $customFields = null;
            }
            return view('property-management::unit.show', compact('unit', 'customFields'));
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
        if (Auth::user()->isAbleTo('property unit edit')) {
            $unit = PropertyUnit::find($id);
            if ($unit->created_by == creatorId() && $unit->workspace == getActiveWorkSpace()) {
                $properties = Property::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
                $properties->prepend(__('Select Property'), '');
                $amenities = ['Balcony' => __('Balcony'), 'Parking' => __('Parking'), 'Laundry' => __('Laundry')];
                $unit->amenities = explode(',', $unit->amenities);
                if (module_is_active('CustomField')) {
                    $unit->customField = \Workdo\CustomField\Entities\CustomField::getData($unit, 'PropertyManagement', 'Units');
                    $customFields             = \Workdo\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'PropertyManagement')->where('sub_module', 'Units')->get();
                } else {
                    $customFields = null;
                }
                return view('property-management::unit.edit', compact('unit', 'properties', 'amenities', 'customFields'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
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
        if (Auth::user()->isAbleTo('property unit edit')) {
            $unit = PropertyUnit::find($id);
            if ($unit->created_by == creatorId() && $unit->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'property_id' => 'required',
                        'unit_name' => 'required',
                        'bedroom' => 'required',
                        'baths' => 'required',
                        'kitchen' => 'required',
                        'amenities' => 'required',
                        'rent_type' => 'required',
                        'rent' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
                $unit->property_id        = $request->property_id;
                $unit->name               = $request->unit_name;
                $unit->bedroom            = $request->bedroom;
                $unit->baths              = $request->baths;
                $unit->kitchen            = $request->kitchen;
                $unit->amenities          = implode(",", array_filter($request->amenities));
                $unit->rent_type          = $request->rent_type;
                $unit->rent               = $request->rent;
                $unit->utilities_included = $request->utilities_included;
                $unit->description        = $request->description;
                $unit->save();
                if (module_is_active('CustomField')) {
                    \Workdo\CustomField\Entities\CustomField::saveData($unit, $request->customField);
                }
                event(new UpdatePropertyUnit($request, $unit));

                return redirect()->route('property-unit.index')->with('success', __('The unit details are updated successfully'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
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
        if (Auth::user()->isAbleTo('property unit delete')) {
            $unit = PropertyUnit::find($id);
            $tenant_invoice = PropertyInvoice::where('unit_id',$id)->first();
            $property_lists = PropertyLists::where('unit',$id)->first();
            if ($unit->created_by == creatorId() && $unit->workspace == getActiveWorkSpace()) {
                if (empty($tenant_invoice) && empty($property_lists)){
                    if (module_is_active('CustomField')) {
                        $customFields = \Workdo\CustomField\Entities\CustomField::where('module', 'PropertyManagement')->where('sub_module', 'Units')->get();
                        foreach ($customFields as $customField) {
                            $value = \Workdo\CustomField\Entities\CustomFieldValue::where('record_id', '=', $unit->id)->where('field_id', $customField->id)->first();
                            if (!empty($value)) {
                                $value->delete();
                            }
                        }
                    }
                    event(new DestroyPropertyUnit($unit));

                    $unit->delete();

                    return redirect()->route('property-unit.index')->with('success', __('The unit has been deleted'));
                } else {
                    return redirect()->back()->with('error', __('Please delete'.(!empty($tenant_invoice) ? ' Invoice' : '').(!empty($property_lists) ? ' or Property Listing' : '').'. related record of this Unit.'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
