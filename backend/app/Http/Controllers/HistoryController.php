<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssessmentResource;
use App\Http\Resources\HistoryResource;
use App\Models\Project;
use App\Services\HistoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// Handle project and assessment history endpoints.
//
// Allow Pemohon to view the history of their own applications.
//
// Allow Penilai to view relevant assessment histories.
//
// Support pagination because history data may become very large.
//
// Sort records by newest activity first.
//
// Return HistoryResource for a consistent response structure.
class HistoryController extends Controller
{
    public function __construct(
        protected HistoryService $historyService
    ) {}

    /**
     * Get audit/activity history for a specific project.
     */
    public function projectHistory(Request $request, Project $project): JsonResponse
    {
        if ($request->user()->cannot('view', $project)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this project history.',
            ], 403);
        }

        $perPage = (int) $request->query('per_page', 20);
        $logs = $this->historyService->getProjectHistory($project, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Project activity history retrieved successfully',
            'data' => HistoryResource::collection($logs)->response()->getData(true),
        ]);
    }

    /**
     * Get overall assessment history (Penilai or Pemohon scoped).
     */
    public function assessmentHistory(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 20);
        $filters = $request->only(['action', 'project_id', 'assessor_id']);

        $assessments = $this->historyService->getAssessmentHistory($request->user(), $filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Assessment history retrieved successfully',
            'data' => AssessmentResource::collection($assessments)->response()->getData(true),
        ]);
    }
}
