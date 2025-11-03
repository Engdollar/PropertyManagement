<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\PropertyManagement\Events\CreateExpenseTracking;
use Workdo\PropertyManagement\DataTables\ExpenseTrackingDataTable;
use Workdo\PropertyManagement\Entities\ExpenseTracking;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Events\DestroyExpenseTracking;
use Workdo\PropertyManagement\Events\UpdateExpenseTracking;

class ExpenseTrackingController extends Controller
{


    public function index(ExpenseTrackingDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('expenses tracking manage')) {
            return $dataTable->render('property-management::expenses-tracking.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->isAbleTo('expenses tracking create')) {
            $properties = Property::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
            return view('property-management::expenses-tracking.create', compact('properties'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('expenses tracking create')) {
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
            $expense_tracking                        = new ExpenseTracking();
            $expense_tracking->property_id         = $request->property_id;
            $expense_tracking->amount            = $request->amount;
            $expense_tracking->category              = $request->category;
            $expense_tracking->expense_date     = $request->expense_date;
            $expense_tracking->description         = $request->description;
            $expense_tracking->workspace             = getActiveWorkSpace();
            $expense_tracking->created_by            = creatorId();
            $expense_tracking->save();
            event(new CreateExpenseTracking($request,$expense_tracking));   

            return redirect()->route('expenses-tracking.index')->with('success', __('The Expense Tracking has been created successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function show($id)
    {
        if (Auth::user()->isAbleTo('expenses tracking show')) {
            $expense_tracking = ExpenseTracking::with('property')->find($id);
            return view('property-management::expenses-tracking.show', compact('expense_tracking'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('expenses tracking edit')) {
            $expense_tracking = ExpenseTracking::find($id);
            if ($expense_tracking->created_by == creatorId() && $expense_tracking->workspace == getActiveWorkSpace()) {
                $properties = Property::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
                return view('property-management::expenses-tracking.edit', compact('expense_tracking', 'properties'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {

        if (Auth::user()->isAbleTo('expenses tracking edit')) {
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
            $expense_tracking                        = ExpenseTracking::find($id);
            $expense_tracking->property_id         = $request->property_id;
            $expense_tracking->amount            = $request->amount;
            $expense_tracking->category              = $request->category;
            $expense_tracking->expense_date     = $request->expense_date;
            $expense_tracking->description         = $request->description;
            $expense_tracking->workspace             = getActiveWorkSpace();
            $expense_tracking->created_by            = creatorId();
            $expense_tracking->update();
            event(new UpdateExpenseTracking($request,$expense_tracking));

            return redirect()->route('expenses-tracking.index')->with('success', __('The Expense Tracking has been updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function destroy($id)
    {

        if (Auth::user()->isAbleTo('expenses tracking delete')) {
            $expense_tracking = ExpenseTracking::find($id);
            if ($expense_tracking->created_by == creatorId()  && $expense_tracking->workspace == getActiveWorkSpace()) {
                event(new DestroyExpenseTracking($expense_tracking));

                $expense_tracking->delete();
                return redirect()->route('expenses-tracking.index')->with('success', __('The Expense Tracking has been deleted successfully'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function showDescription($id)
    {
        if (Auth::user()->isAbleTo('expenses tracking manage')) {
            $id       = \Crypt::decrypt($id);
            $expense_tracking = ExpenseTracking::where('id', $id)->where('created_by',creatorId())->where('workspace', getActiveWorkSpace())->first();
            return view('property-management::expenses-tracking.description', compact('expense_tracking'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
}
