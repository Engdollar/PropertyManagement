<?php

namespace Workdo\PropertyManagement\Http\Controllers;


use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\WorkSpace;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyImages;
use Workdo\PropertyManagement\Entities\PropertyListImages;
use Workdo\PropertyManagement\Entities\PropertyLists;
use Workdo\PropertyManagement\Entities\PropertyTenantRequest;
use Workdo\PropertyManagement\Entities\PropertyUnit;

class PropertyFrontController extends Controller
{

    public function copylink($slug, $lang = null)
    {
        $workspace       = WorkSpace::where('slug', $slug)->first();
        if ($workspace) {
            $moduleName =  'PropertyManagement';

            $status = module_is_active($moduleName, $workspace->created_by);
            if ($status == true) {
                $propertylists = PropertyLists::where('workspace', $workspace->id)->where('created_by',  $workspace->created_by)->where('status','0')->with(['property','unit_id'])->get();
                $currantLang = session()->get('lang')?session()->get('lang'):'en';

                $languages = languages();
                \App::setLocale($currantLang);
                $lang = $currantLang;

                return view('property-management::frontend.index',compact('workspace','slug','lang','propertylists'));
            }
            else {
                abort(404);
            }

        }else {
            abort(404);
        }
    }

    public function changeLanquageStore($slug, $lang)
    {
        session(['lang' => $lang]);

        return redirect()->back()->with('success', __('Language change successfully.'));
    }


    public function property_details($slug, $property_id, $unit_id, $lang = null)
    {
        try {
            
            $property = PropertyLists::where('property_id', $property_id)->where('unit', $unit_id)->with(['property','unit_id'])->first();

            $workspace       = WorkSpace::where('slug', $slug)->first();

            $propertylists = PropertyLists::where('workspace', $workspace->id)
                ->where('created_by', $workspace->created_by)
                ->where('status','0')->with(['property','unit_id'])
                ->get();

            if (!empty($property)) {
                $property_unit_images = PropertyListImages::where('property_id', $property->property_id)->where('unit_id',$property->unit)->get();
                $property_images = PropertyImages::where('property_id', '=', $property_id)->get();
                $currantLang = session()->get('lang')?session()->get('lang'):'en';

                $languages = languages();
                \App::setLocale($currantLang);
                $lang = $currantLang;
                return view('property-management::frontend.property_detail', compact('workspace', 'slug', 'lang', 'propertylists', 'property','property_images','property_unit_images'));
            } else {
                return redirect()->back()->with('error', __('Property not avaliable'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Property not avaliable'));
        }
    }

    public function Checkoutticket(Request $request, $slug, $property_id)
    {
        try {
            $workspace       = WorkSpace::where('slug', $slug)->first();
            $property = PropertyLists::where('workspace', $workspace->id)->where('created_by', $workspace->created_by)->where('property_id', $request->property_id)->where('unit', $request->unit_id)->first();

            $unit = PropertyUnit::where('id',$property->unit)->first();
            $amout = Property::where('id',$property->property_id)->first();
            $currantLang = session()->get('lang')?session()->get('lang'):'en';
            $maintenance_charge = !empty($amout->maintenance_charge) ? $amout->maintenance_charge : 0;
            
            $total_amount         = !empty($unit->rent) ? $unit->rent + $maintenance_charge : 0 + $maintenance_charge;

            $languages = languages();
            \App::setLocale($currantLang);
            $lang = $currantLang;
            return view('property-management::frontend.checkout', compact('slug', 'workspace', 'property','total_amount'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Property not avaliable'));
        }
    }

    public function store(Request $request, $slug)
    {
        $workspace = WorkSpace::where('slug', $slug)->first();
        if (!$workspace) {
            return redirect()->back()->with('error', __('Workspace not found.'));
        }
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:180',
                'email' => ['required',
                                    Rule::unique('users')->where(function ($query) use ($workspace) {
                                    return $query->where('created_by', $workspace->created_by)->where('workspace_id',$workspace->id);
                                })
                    ],
                'mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }


        if ($request->payment_option == 'Offline') {

            $tenant_request                  = new PropertyTenantRequest();
            $tenant_request->name            = $request->name;
            $tenant_request->email           = $request->email;
            $tenant_request->mobile_no       = $request->mobile_number;
            $tenant_request->property_id     = $request->property;
            $tenant_request->unit_id         = $request->unit;
            $tenant_request->total_amount    = $request->price;
            $tenant_request->workspace       = $workspace->id;
            $tenant_request->created_by      = $workspace->created_by;
            $tenant_request->save();

            $msg =  __('Booking Request Send Successfully.');
            return redirect()->back()->with('success', $msg);
        }
    }
}
