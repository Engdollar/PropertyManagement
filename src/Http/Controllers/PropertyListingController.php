<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\PropertyManagement\DataTables\PropertyListingDataTable;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyImages;
use Workdo\PropertyManagement\Entities\PropertyListImages;
use Workdo\PropertyManagement\Entities\PropertyLists;
use Workdo\PropertyManagement\Entities\PropertyUnit;
use Workdo\PropertyManagement\Events\CreatePropertyList;
use Workdo\PropertyManagement\Events\DestroyPropertyList;
use Workdo\PropertyManagement\Events\UpdatePropertyList;

class PropertyListingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PropertyListingDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('property listing manage')) {
            return $dataTable->render('property-management::propertylisting.index');
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
        if (Auth::user()->isAbleTo('property listing create')) {
            $properties = Property::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('name', 'id');
            $units = PropertyUnit::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('name', 'id');
            $status          = PropertyLists::$status;
            return view('property-management::propertylisting.create', compact('properties', 'status', 'units'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {


        if (Auth::user()->isAbleTo('property listing create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_id' => 'required',
                    'unit' => 'required',
                    'list_type' => 'required',
                    'en_suites' => 'required',
                    'total_sq' => 'required',
                    'tax' => 'required',
                    'garage_parking' => 'required',
                    'dining' => 'required',
                    'lounge' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'flag' => 'error',
                    'status' => false,
                    'msg' => $messages->first()
                ]);
            }

            $file_name = [];
                if (!empty($request->multiple_files) && count($request->multiple_files) > 0) {
                    foreach ($request->multiple_files as $key => $file) {
                        $filenameWithExt = $file->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $file->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                        $myRequest = new Request();
                        $myRequest->request->add(['image' => $file]);
                        $myRequest->files->add(['image' => $file]);
                        $uplaod = upload_file($myRequest, 'image', $fileNameToStore, 'property_list', []);

                        if ($uplaod['flag'] == 1) {
                            $file_name[] = $uplaod['url'];
                        } else {
                            return response()->json([
                                'flag' => 'error',
                                'status' => false,
                                'msg' => $uplaod['msg']
                            ]);
                        }
                    }
                }

            $propertylist                     = new PropertyLists();
            $propertylist->property_id        = $request->property_id;
            $propertylist->unit               = $request->unit;
            $propertylist->status             = $request->status;
            $propertylist->rent_type           = $request->rent_type;
            $propertylist->tax                = $request->tax;
            $propertylist->list_type          = $request->list_type;
            $propertylist->rent_amount        = $request->rent_amount;
            $propertylist->en_suites          = $request->en_suites;
            $propertylist->lounge             = $request->lounge;
            $propertylist->garage_parking     = $request->garage_parking;
            $propertylist->dining             = $request->dining;
            $propertylist->total_sq           = $request->total_sq;
            $propertylist->workspace          = getActiveWorkSpace();
            $propertylist->created_by         = creatorId();
            $propertylist->save();


            if (!empty($file_name)) {
                foreach ($file_name as $file) {
                    $PropertyImages = new PropertyListImages();
                    $PropertyImages->property_id = $propertylist->property_id;
                    $PropertyImages->unit_id = $propertylist->unit;
                    $PropertyImages->name = $file;
                    $PropertyImages->workspace = getActiveWorkSpace();
                    $PropertyImages->created_by = creatorId();
                    $PropertyImages->save();
                }
            }
            event(new CreatePropertyList($request, $propertylist));
            return response()->json([
                'flag' => 'success',
                'status' => true,
                'msg' =>  __('The property list has been created successfully')
            ]);

        } else
        {
            return response()->json([
                'flag' => 'error',
                'status' => false,
                'msg' =>  __('Permission denied!')
            ]);

        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {

        if (Auth::user()->isAbleTo('property listing edit')) {
            $propertylist = PropertyLists::with(['propertyListImage'])->find($id);
            $properties = Property::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $status          = PropertyLists::$status;

            if(isset($propertylist)){
                $property = Property::where('id',$propertylist->property_id)->where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->first();
                $security_deposit = isset($property->security_deposit) ? $property->security_deposit : '0';
                $maintenance_charge = isset($property->maintenance_charge) ? $property->maintenance_charge : '0';
                $units = PropertyUnit::where('property_id',$propertylist->property_id)->where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');

                $unit = PropertyUnit::where('id',$propertylist->unit)->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->first();

                $unit_rent = isset($unit->rent) ? $unit->rent : '0';
                $unit_rent_type = isset($unit->rent_type) ? $unit->rent_type : '';
                $bedroom = isset($unit->bedroom) ? $unit->bedroom : '0';
                $baths = isset($unit->baths) ? $unit->baths : '0';
                $kitchen = isset($unit->kitchen) ? $unit->kitchen : '0';
                $utilities_included = isset($unit->utilities_included) ? $unit->utilities_included : '0';

            }else{
                $security_deposit = '0';
                $maintenance_charge = '0';
                $units = [];
                $unit_rent = '0';
                $unit_rent_type = '';
                $bedroom = '';
                $baths = '';
                $kitchen = '';
                $utilities_included = '';
            }

            return view('property-management::propertylisting.edit', ['propertylist' => $propertylist],compact('properties','units','status','maintenance_charge','unit','unit_rent_type','unit_rent','security_deposit','property','bedroom','baths','kitchen','utilities_included'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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

        if (Auth::user()->isAbleTo('property listing edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_id' => 'required',
                    'unit' => 'required',
                    'list_type' => 'required',
                    'en_suites' => 'required',
                    'total_sq' => 'required',
                    'tax' => 'required',
                    'garage_parking' => 'required',
                    'dining' => 'required',
                    'lounge' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'flag' => 'error',
                    'status' => false,
                    'msg' => $messages->first()
                ]);
            }

            $propertylist = PropertyLists::find($id);
            $file_name = [];
            if (!empty($request->multiple_files) && count($request->multiple_files) > 0) {
                foreach ($request->multiple_files as $key => $file) {
                    $filenameWithExt = $file->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $file->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $myRequest = new Request();
                    $myRequest->request->add(['image' => $file]);
                    $myRequest->files->add(['image' => $file]);
                    $uplaod = upload_file($myRequest, 'image', $fileNameToStore, 'property_list', []);
                    if ($uplaod['flag'] == 1) {
                        $file_name[] = $uplaod['url'];
                    } else {
                        return response()->json([
                            'flag' => 'error',
                            'status' => false,
                            'msg' => $uplaod['msg']
                        ]);
                    }
                }
            }


            $propertylist->property_id        = $request->property_id;
            $propertylist->unit               = $request->unit;
            $propertylist->status             = $request->status;
            $propertylist->rent_type          = $request->rent_type;
            $propertylist->tax                = $request->tax;
            $propertylist->list_type          = $request->list_type;
            $propertylist->rent_amount        = $request->rent_amount;
            $propertylist->en_suites          = $request->en_suites;
            $propertylist->lounge             = $request->lounge;
            $propertylist->garage_parking     = $request->garage_parking;
            $propertylist->dining             = $request->dining;
            $propertylist->total_sq           = $request->total_sq;
            $propertylist->workspace          = getActiveWorkSpace();
            $propertylist->created_by         = creatorId();
            $propertylist->save();


            if (!empty($file_name)) {
                foreach ($file_name as $file) {
                    $PropertyImages = new PropertyListImages();
                    $PropertyImages->property_id = $propertylist->property_id;
                    $PropertyImages->unit_id = $propertylist->unit;
                    $PropertyImages->name = $file;
                    $PropertyImages->workspace = getActiveWorkSpace();
                    $PropertyImages->created_by = creatorId();
                    $PropertyImages->save();
                }
            }

            event(new UpdatePropertyList($request,$propertylist));
            return response()->json([
                'flag' => 'success',
                'msg' => __('The property list details are updated successfully')
            ]);

        }else {
            return response()->json([
                'flag' => 'fail',
                'msg' => __('Permission denied')
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('property listing delete')) {
            $propertylist = PropertyLists::find($id);
            event(new DestroyPropertyList($propertylist));
            $propertylist->delete();
            return redirect()->back()->with('success', __('The property list has been deleted'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function propertylistImageDelete($id)
    {
        if (Auth::user()->isAbleTo('property listing delete')) {
            $image = PropertyListImages::find($id);
            $destinationPath = 'storage/uploads/property_list/' . $image->name;
            if (File::exists($destinationPath)) {
                File::delete($destinationPath);
            }
            $image->delete();
            return response()->json([
                'id' => $id,
                'success' => true,
                'message' =>  __('The property list image has been deleted')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' =>  __('Permission denied!')
            ]);
        }
    }


    public function propertydetails(Request $request)
    {
        $property = Property::where('id', $request->id)->first();
        if ($property) {
            $property_image = PropertyImages::where('property_id', $property->id)->get()->pluck('name');
            $propertydetail['property'] = $property;
            $propertydetail['property_image'] = $property_image;
            return response()->json($propertydetail);
        } else {
            return response()->json(['error' => 'Property not found']);
        }
    }


    public function unitdetails(Request $request)
    {
        $unit = PropertyUnit::where('id', $request->id)->first();

        if ($unit) {
            return response()->json($unit);
        } else {
            return response()->json(['error' => 'Unit not found']);
        }
    }
}
