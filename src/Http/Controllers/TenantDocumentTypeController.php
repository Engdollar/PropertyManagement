<?php

namespace Workdo\PropertyManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Workdo\PropertyManagement\DataTables\TenantDocumentTypeDataTable;
use Workdo\PropertyManagement\Entities\TenantDocumentType;
use Workdo\PropertyManagement\Events\CreateDocumentType;
use Workdo\PropertyManagement\Events\UpdateDocumentType;
use Workdo\PropertyManagement\Events\DestroyDocumentType;

class TenantDocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(TenantDocumentTypeDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('tenant document manage')) {
            return $dataTable->render('property-management::document_type.index');
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
        if (Auth::user()->isAbleTo('tenant document create')) {
            return view('property-management::document_type.create');
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
        if (Auth::user()->isAbleTo('tenant document create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:30',
                    'is_required' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $document_type              = new TenantDocumentType();
            $document_type->name        = $request->name;
            $document_type->is_required = $request->is_required;
            $document_type->workspace   = getActiveWorkSpace();
            $document_type->created_by  = creatorId();
            $document_type->save();

            event(new CreateDocumentType($request, $document_type));

            return redirect()->route('tenant-document-type.index')->with('success', __('The document type has been created successfully'));
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
        return redirect()->back();
        return view('property-management::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('tenant document edit')) {
            $document_type = TenantDocumentType::find($id);
            if ($document_type->created_by == creatorId() && $document_type->workspace == getActiveWorkSpace()) {
                return view('property-management::document_type.edit', compact('document_type'));
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
        if (Auth::user()->isAbleTo('tenant document edit')) {
            $document_type = TenantDocumentType::find($id);
            if ($document_type->created_by == creatorId() && $document_type->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:30',
                        'is_required' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
                $document_type->name        = $request->name;
                $document_type->is_required = $request->is_required;
                $document_type->save();

                event(new UpdateDocumentType($request, $document_type));

                return redirect()->route('tenant-document-type.index')->with('success', __('The document type details are updated successfully'));
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
        if (Auth::user()->isAbleTo('tenant document delete')) {
            $document_type = TenantDocumentType::find($id);
            if ($document_type->created_by == creatorId() && $document_type->workspace == getActiveWorkSpace()) {
                event(new DestroyDocumentType($document_type));

                $document_type->delete();

                return redirect()->route('tenant-document-type.index')->with('success', __('The document type has been deleted'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
