<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use App\Models\BankTransferPayment;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\DataTables\PropertyInvoiceDataTable;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyInvoice;
use Workdo\PropertyManagement\Entities\PropertyInvoicePayment;
use Workdo\PropertyManagement\Entities\PropertyUnit;
use Workdo\PropertyManagement\Entities\Tenant;
use Workdo\PropertyManagement\Events\CreatePropertyInvoicePayment;

class PropertyInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PropertyInvoiceDataTable $dataTable)
    {
        if(Auth::user()->isAbleTo('property invoice manage'))
        {
            return $dataTable->render('property-management::propertyinvoice.index');
        }
        else
        {
            return redirect()->back()->with('error', __('permission Denied'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if(Auth::user()->isAbleTo('property invoice show'))
        {
            try {
                $id = \Illuminate\Support\Facades\Crypt::decrypt($id);
            } catch (\Throwable $th) {
                return redirect('login');
            }
            $invoice = PropertyInvoice::find($id);
            $bank_transfer_payments = BankTransferPayment::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->where('type','propertyinvoice')->where('request',$invoice->id)->get();
            $tenant = Tenant::find($invoice->user_id);
            if($tenant == null){
                return redirect()->back()->with('error', 'Tenant Deleted');
            }else{
                $tenant_details = User::find($tenant->user_id);

                $property = Property::find($invoice->property_id);
                $unit = PropertyUnit::find($invoice->unit_id);

                return view('property-management::propertyinvoice.view', compact('invoice','bank_transfer_payments','tenant','tenant_details','property','unit'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('permission Denied'));
        }
    }

    public function statusUpdate(Request $request, $id)
    {
        $invoice    = PropertyInvoice::find($id);
        if($request->status == 'Paid' && $invoice->status != 'Paid'){
            $invoice_payment                       = new PropertyInvoicePayment();
            $invoice_payment->invoice_id           = $invoice->id;
            $invoice_payment->user_id              = $invoice->user_id;
            $invoice_payment->date                 = date('Y-m-d');
            $invoice_payment->amount               = isset($invoice->total_amount) ? $invoice->total_amount : 0;
            $invoice_payment->payment_type         = __('Manually');
            $invoice_payment->receipt              = '';
            $invoice_payment->save();
        }
        $invoice->status = $request->status;
        $invoice->save();

        $type = "propertyinvoice";
        event(new CreatePropertyInvoicePayment($request,$type,$invoice));

        $return =  response()->json(
            [
                'success' => __('The status has been changed successfully.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''),
            ]
        );
        return $return;
    }

    public function pdf($id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect('login');
        }
        $invoice = PropertyInvoice::find($id);
        if($invoice)
        {
            // $bank_transfer_payments = BankTransferPayment::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->where('type','propertyinvoice')->where('request',$invoice->id)->get();
            $tenant = Tenant::find($invoice->user_id);
            $tenant_details = User::find($tenant->user_id);

            $property = Property::find($invoice->property_id);
            $unit = PropertyUnit::find($invoice->unit_id);

            $settings = getCompanyAllSetting($invoice->created_by,$invoice->workspace);
            //Set your logo
            $company_logo = get_file(sidebar_logo());
            $img  = $company_logo;

            $color      = '#ffffff';
            $font_color = '#000000';
            return view('property-management::propertyinvoice.pdf', compact('invoice', 'color', 'tenant', 'tenant_details', 'property', 'unit', 'img', 'font_color', 'settings'));
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
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if(Auth::user()->isAbleTo('property invoice delete'))
        {
            $invoice = PropertyInvoice::find($id);
            if($invoice){
                $invoice->delete();
                return redirect()->route('property-invoice.index')->with('success', __('The invoice has been deleted'));
            }else{
                return redirect()->back()->with('error', __('permission Denied'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('permission Denied'));
        }
    }
}
