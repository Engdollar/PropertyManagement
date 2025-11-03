<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Models\WorkSpace;
use Illuminate\Routing\Controller;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyImages;
use Workdo\PropertyManagement\Entities\PropertyInvoice;
use Workdo\PropertyManagement\Entities\PropertyUnit;
use Workdo\PropertyManagement\Entities\PropertyUtility;
use Workdo\PropertyManagement\Entities\Tenant;
use Workdo\PropertyManagement\Events\CreateProperty;
use Workdo\PropertyManagement\Events\DestroyProperty;
use Workdo\PropertyManagement\Events\UpdateProperty;

class PropertyManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct()
    {
        if (module_is_active('GoogleAuthentication')) {
            $this->middleware('2fa');
        }
    }
    public function index()
    {
        if (Auth::user()->isAbleTo('property manage'))
        {
            if (Auth::user()->type == 'tenant'){
                $tenant = Tenant::where('user_id',Auth::user()->id)->first();
                $properties = Property::where('id',$tenant->property_id)->where('workspace',getActiveWorkSpace())->where('created_by', creatorId());
                $properties = $properties->paginate(11);
            }else{
                $properties = Property::where('workspace',getActiveWorkSpace())->where('created_by', creatorId());
                $properties = $properties->paginate(11);
            }
            return view('property-management::property.index', compact('properties'));
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
        if (Auth::user()->isAbleTo('property create'))
        {
            return view('property-management::property.create');
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
        if (Auth::user()->isAbleTo('property create'))
        {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_name' => 'required|string|max:255',
                    'address' => 'required|string|max:255',
                    'city' => 'required|string|max:100',
                    'state' => 'required|string|max:100',
                    'country' => 'required|string|max:100',
                    'zipcode' => 'required|string|max:20',
                    'latitude' => 'required|numeric|between:-90,90',
                    'longitude' => 'required|numeric|between:-180,180',
                    'security_deposit' => 'required|numeric|min:0',
                    'maintenance_charge' => 'required|numeric|min:0',
                ],
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
                    $uplaod = upload_file($myRequest, 'image', $fileNameToStore, 'property', []);

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

            $property = Property::create(
                [
                    'name' => $request['property_name'],
                    'address' => $request['address'],
                    'country' => $request['country'],
                    'state' => $request['state'],
                    'city' => $request['city'],
                    'pincode' => $request['zipcode'],
                    'latitude' => $request['latitude'],
                    'longitude' => $request['longitude'],
                    'description' => $request['description'],
                    'security_deposit' => $request['security_deposit'],
                    'maintenance_charge' => $request['maintenance_charge'],
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                    ]
                );

            if (!empty($file_name)) {
                foreach ($file_name as $file) {
                    $PropertyImages = new PropertyImages();
                    $PropertyImages->property_id = $property->id;
                    $PropertyImages->name = $file;
                    $PropertyImages->workspace = getActiveWorkSpace();
                    $PropertyImages->created_by = creatorId();
                    $PropertyImages->save();
                }
            }

            event(new CreateProperty($request,$property));
            return response()->json([
                'flag' => 'success',
                'status' => true,
                'msg' =>  __('The property has been created successfully.')
            ]);

        }
        else
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
        if (Auth::user()->isAbleTo('property show'))
        {
            if (Auth::user()->type == 'tenant'){
                $tenant = Tenant::where('user_id',Auth::user()->id)->first();
                if($tenant->property_id == $id){
                    $property = Property::find($id);
                }
                if (isset($property)) {
                    $property_images = PropertyImages::where('property_id', '=', $id)->get();
                    $units = PropertyUnit::where('id',$tenant->unit_id)->where('property_id',$id)->get();
                    return view('property-management::property.show', compact('property','property_images','units'));
                } else {
                    return redirect()->back()->with('error', __('Permission Denied.'));
                }
            }else{
                $property = Property::find($id);
                if (isset($property)) {

                    $property_images = PropertyImages::where('property_id', '=', $id)->get();
                    $units = PropertyUnit::where('property_id',$id)->get();
                    return view('property-management::property.show', compact('property','property_images','units'));
                } else {
                    return redirect()->back()->with('error', __('Permission Denied.'));
                }
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('property edit')) {
            $property = Property::with(['propertyImage'])->find($id);
            return view('property-management::property.edit', ['property' => $property]);
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
        if (Auth::user()->isAbleTo('property edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_name' => 'required|string|max:255',
                    'address' => 'required|string|max:255',
                    'city' => 'required|string|max:100',
                    'state' => 'required|string|max:100',
                    'country' => 'required|string|max:100',
                    'zipcode' => 'required|string|max:20',
                    'latitude' => 'required|numeric|between:-90,90',
                    'longitude' => 'required|numeric|between:-180,180',
                    'security_deposit' => 'required|numeric|min:0',
                    'maintenance_charge' => 'required|numeric|min:0',
                ],
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'flag' => 'error',
                    'status' => false,
                    'msg' => $messages->first()
                ]);
            }

            $property = Property::find($id);
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
                    $uplaod = upload_file($myRequest, 'image', $fileNameToStore, 'property', []);
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

          if (!empty($file_name)) {
                foreach ($file_name as $file) {
                    $PropertyImages = new PropertyImages();
                    $PropertyImages->property_id = $property->id;
                    $PropertyImages->name = $file;
                    $PropertyImages->workspace = getActiveWorkSpace();
                    $PropertyImages->created_by = creatorId();
                    $PropertyImages->save();
                }
            }
            
            $property->update([
                'name' => $request['property_name'],
                'address' => $request['address'],
                'country' => $request['country'],
                'state' => $request['state'],
                'city' => $request['city'],
                'pincode' => $request['zipcode'],
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude'],
                'description' => $request['description'],
                'security_deposit' => $request['security_deposit'],
                'maintenance_charge' => $request['maintenance_charge'],
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ]);

            $property = Property::find($id);
            event(new UpdateProperty($request,$property));
            return response()->json([
                'flag' => 'success',
                'msg' => __('The property details are updated successfully')
            ]);
        } else {
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
        if (Auth::user()->isAbleTo('property delete')) {
            $property = Property::find($id);
            $unit = PropertyUnit::where('property_id',$id)->count();
            if($unit == 0){
                $destinationPath = 'uploads/property/' . $property->image;
                if (File::exists($destinationPath)) {
                    File::delete($destinationPath);
                }
                $files = PropertyImages::where('property_id', $id)->get();
                $destinationImagePath = 'uploads/property/';
                foreach ($files as $key => $file) {
                    if (File::exists($destinationImagePath . $file->name)) {
                        File::delete($destinationImagePath . $file->name);
                    }
                    $file->delete();
                }
                event(new DestroyProperty($property));
                $property->delete();
                return redirect()->back()->with('success', __('The property has been deleted'));
            }else{
                return redirect()->back()->with('error', __('This property has units. Please remove the unit from this property.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function propertyImageDelete($id)
    {
        if (Auth::user()->isAbleTo('property delete')) {
            $image = PropertyImages::find($id);
            $destinationPath = 'storage/uploads/property/' . $image->name;
            if (File::exists($destinationPath)) {
                File::delete($destinationPath);
            }
            $image->delete();
            return response()->json([
                'id' => $id,
                'success' => true,
                'message' =>  __('The property image has been deleted')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' =>  __('Permission denied!')
            ]);
        }
    }

    public function list()
    {
        if (Auth::user()->isAbleTo('property manage'))
        {
            $properties = Property::where('workspace',getActiveWorkSpace())->where('created_by', creatorId())->get();
            return view('property-management::property.list', compact('properties'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function dashboard()
    {
        if(Auth::check())
        {
            if (Auth::user()->isAbleTo('property dashboard manage'))
            {
                $data['invExpLineChartData'] = PropertyUtility::getInvExpLineChartDate();
                if(Auth::user()->type == 'tenant'){
                    $tenant = Tenant::where('user_id',Auth::user()->id)->first();
                    $data['recentInvoice']     = PropertyInvoice::where('user_id',$tenant->id ?? 0)->where('workspace', '=', getActiveWorkSpace())->where('created_by',creatorId())->orderBy('id', 'desc')->limit(5)->get();
                }else{
                    $data['recentInvoice']     = PropertyInvoice::where('workspace', '=', getActiveWorkSpace())->where('created_by',creatorId())->orderBy('id', 'desc')->limit(5)->get();
                }
                $workspace       = WorkSpace::where('id', getActiveWorkSpace())->first();
                return view('property-management::dashboard.dashboard', $data , compact('workspace'));

            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->route('login');
        }
    }




}
