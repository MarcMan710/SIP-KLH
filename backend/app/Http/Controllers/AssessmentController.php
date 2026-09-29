<?php

namespace App\Http\Controllers;

use App\Http\Requests\Assessment\AssessmentRequest;
use App\Http\Resources\AssessmentResource;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\AssessmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// Handle assessor-related API operations.
//
// Operations:
// - list applications requiring assessment
// - view application for assessment
// - submit assessment
// - approve application
// - reject application
//
// Only Penilai users can perform assessment actions.
//
// Delegate workflow logic to AssessmentService.
//
// Record every important action in the activity log.
//
// Prevent invalid status transitions.
class AssessmentController extends Controller
{
    public function __construct(
        protected AssessmentService $assessmentService
    ) {}

    /**
     * List applications pending assessment for Penilai.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 20);
        $filters = $request->only(['status', 'search']);

        $projects = $this->assessmentService->getPendingAssessments($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Pending assessments retrieved successfully',
            'data' => ProjectResource::collection($projects)->response()->getData(true),
        ]);
    }

    /**
     * View assessment details for a project.
     */
    public function show(Request $request, Project $project): JsonResponse
    {
        if ($request->user()->cannot('view', $project)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this project assessment.',
            ], 403);
        }

        $project->load([
            'user:id,name,email',
            'documents',
            'assessments.assessor:id,name,email',
            'revisions.requester:id,name,email',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project assessment details retrieved successfully',
            'data' => new ProjectResource($project),
        ]);
    }

    /**
     * Review or update notes on project assessment.
     */
    public function review(AssessmentRequest $request, Project $project): JsonResponse
    {
        $assessment = $this->assessmentService->reviewProject(
            $project,
            $request->user(),
            $request->input('notes')
        );

        return response()->json([
            'success' => true,
            'message' => 'Assessment review recorded successfully',
            'data' => new AssessmentResource($assessment),
        ]);
    }

    /**
     * Approve project application.
     */
    public function approve(Request $request, Project $project): JsonResponse
    {
        if ($request->user()->cannot('approve', $project)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to approve this project.',
            ], 403);
        }

        $assessment = $this->assessmentService->approveProject(
            $project,
            $request->user(),
            $request->input('notes')
        );

        return response()->json([
            'success' => true,
            'message' => 'Project approved successfully',
            'data' => new AssessmentResource($assessment),
        ]);
    }

    /**
     * Reject project application.
     */
    public function reject(Request $request, Project $project): JsonResponse
    {
        if ($request->user()->cannot('reject', $project)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to reject this project.',
            ], 403);
        }

        $assessment = $this->assessmentService->rejectProject(
            $project,
            $request->user(),
            $request->input('notes')
        );

        return response()->json([
            'success' => true,
            'message' => 'Project rejected successfully',
            'data' => new AssessmentResource($assessment),
        ]);
    }
}
