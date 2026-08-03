<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Exception;

class ReportController extends Controller
{
    use AuthorizesRequests;

    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        try {
            $report = $this->reportService->generateReport('summary');
            $revenue = $this->reportService->calculateTotalRevenue();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Report list endpoint.',
                    'data' => [
                        'report' => $report,
                        'total_revenue' => $revenue
                    ]
                ]);
            }
            
            $user = Auth::user();
            if ($user && $user->hasRole('organizer')) {
                return view('organizer.reports.index', compact('report', 'revenue'));
            } elseif ($user && $user->hasRole('admin')) {
                return view('admin.reports.index', compact('report', 'revenue'));
            }

            return back()->withErrors(['error' => 'Unauthorized access.']);
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
