<?php

namespace Packages\workdo\PropertyManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Packages\workdo\PropertyManagement\Entities\Property;
use Packages\workdo\PropertyManagement\Entities\PropertyUnit;
use Packages\workdo\PropertyManagement\Entities\PropertyInvoice;
use Packages\workdo\PropertyManagement\Entities\PropertyPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PropertyReportController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('property report manage')) {
            return view('propertymanagement::report.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function generateReport(Request $request)
    {
        try {
            $workspaceId = getActiveWorkSpace();
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
            $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

            // Get all data for the report
            $reportData = [
                'portfolio' => $this->getPropertyPortfolio($workspaceId, $startDate, $endDate),
                'paidInvoices' => $this->getPaidInvoices($workspaceId, $startDate, $endDate),
                'unpaidInvoices' => $this->getUnpaidInvoices($workspaceId, $startDate, $endDate),
                'vacantUnits' => $this->getVacantUnits($workspaceId, $startDate, $endDate),
                'analytics' => $this->getAnalytics($workspaceId, $startDate, $endDate),
                'dateRange' => [
                    'start' => $startDate,
                    'end' => $endDate
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $reportData
            ]);

        } catch (\Exception $e) {
            \Log::error('Property report generation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    private function getPropertyPortfolio($workspaceId, $startDate, $endDate)
    {
        return Property::where('workspace', $workspaceId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($property) {
                $totalUnits = PropertyUnit::where('property_id', $property->id)->count();
                $occupiedUnits = PropertyUnit::where('property_id', $property->id)
                    ->where('status', 'occupied')->count();
                $vacantUnits = PropertyUnit::where('property_id', $property->id)
                    ->where('status', 'vacant')->count();
                $monthlyRent = PropertyUnit::where('property_id', $property->id)->sum('rent');
                
                return [
                    'name' => $property->name,
                    'total_units' => $totalUnits,
                    'occupied_units' => $occupiedUnits,
                    'vacant_units' => $vacantUnits,
                    'monthly_rent' => $monthlyRent,
                    'expected_revenue' => $monthlyRent,
                    'status' => $property->is_active ? 'Active' : 'Inactive'
                ];
            });
    }

    private function getPaidInvoices($workspaceId, $startDate, $endDate)
    {
        return PropertyInvoice::where('workspace', $workspaceId)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->with(['property', 'unit', 'tenant'])
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->invoice_id,
                    'property_name' => $invoice->property->name ?? 'N/A',
                    'unit_number' => $invoice->unit->unit_number ?? 'N/A',
                    'tenant_name' => $invoice->tenant->name ?? 'N/A',
                    'amount' => $invoice->amount,
                    'payment_date' => $invoice->payment_date,
                    'payment_method' => $invoice->payment_method ?? 'N/A',
                    'status' => 'Paid'
                ];
            });
    }

    private function getUnpaidInvoices($workspaceId, $startDate, $endDate)
    {
        return PropertyInvoice::where('workspace', $workspaceId)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->with(['property', 'unit', 'tenant'])
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->invoice_id,
                    'property_name' => $invoice->property->name ?? 'N/A',
                    'unit_number' => $invoice->unit->unit_number ?? 'N/A',
                    'tenant_name' => $invoice->tenant->name ?? 'N/A',
                    'amount' => $invoice->amount,
                    'days_overdue' => Carbon::parse($invoice->due_date)->diffInDays(now()),
                    'late_fee' => $invoice->late_fee ?? 0,
                    'status' => 'Unpaid'
                ];
            });
    }

    private function getVacantUnits($workspaceId, $startDate, $endDate)
    {
        return PropertyUnit::where('workspace', $workspaceId)
            ->where('status', 'vacant')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('property')
            ->get()
            ->map(function ($unit) {
                return [
                    'property_name' => $unit->property->name ?? 'N/A',
                    'unit_number' => $unit->unit_number,
                    'unit_type' => $unit->unit_type ?? 'N/A',
                    'monthly_rent' => $unit->rent,
                    'bedrooms' => $unit->bedrooms ?? 0,
                    'bathrooms' => $unit->bathrooms ?? 0,
                    'days_vacant' => Carbon::parse($unit->updated_at)->diffInDays(now()),
                    'status' => 'Vacant'
                ];
            });
    }

    private function getAnalytics($workspaceId, $startDate, $endDate)
    {
        $totalInvoices = PropertyInvoice::where('workspace', $workspaceId)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->count();

        $paidInvoices = PropertyInvoice::where('workspace', $workspaceId)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->count();

        $unpaidInvoices = PropertyInvoice::where('workspace', $workspaceId)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->where('status', 'unpaid')
            ->count();

        $highRisk = PropertyInvoice::where('workspace', $workspaceId)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->where('status', 'unpaid')
            ->where('due_date', '<', now()->subDays(30))
            ->count();

        return [
            'payment_rate' => $totalInvoices > 0 ? round(($paidInvoices / $totalInvoices) * 100, 2) : 0,
            'total_invoices' => $totalInvoices,
            'paid_invoices' => $paidInvoices,
            'unpaid_invoices' => $unpaidInvoices,
            'high_risk' => $highRisk,
            'medium_risk' => $unpaidInvoices - $highRisk
        ];
    }
}