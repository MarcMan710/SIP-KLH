<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// Provide dashboard statistics for each role.
//
// Applicant dashboard:
// - total projects
// - draft count
// - submitted count
// - under-review count
// - revision-required count
// - approved count
// - rejected count
//
// Assessor dashboard:
// - total applications
// - pending assessments
// - revision requests
// - approved applications
// - rejected applications
//
// Delegate calculations to DashboardService.
//
// Use caching where appropriate to avoid repeatedly executing
// expensive aggregation queries against large datasets.
//
// Dashboard data may later be consumed by Chart.js or ApexCharts.
class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * Statistics for applicant (Pemohon) dashboard.
     */
    public function pemohon(Request $request): JsonResponse
    {
        $stats = $this->dashboardService->getPemohonStats($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Applicant dashboard statistics retrieved successfully',
            'data' => $stats,
        ]);
    }

    /**
     * Statistics for assessor (Penilai) dashboard.
     */
    public function penilai(Request $request): JsonResponse
    {
        $stats = $this->dashboardService->getPenilaiStats($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Assessor dashboard statistics retrieved successfully',
            'data' => $stats,
        ]);
    }
}
