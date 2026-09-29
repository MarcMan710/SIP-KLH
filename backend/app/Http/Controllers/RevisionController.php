<?php

namespace App\Http\Controllers;

use App\Http\Requests\Assessment\RevisionRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\RevisionResource;
use App\Models\Project;
use App\Services\RevisionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// Handle revision-related API operations.
//
// Operations:
// - request revision
// - view revision history
// - resubmit revised application
//
// Penilai can request revisions.
//
// Pemohon can update and resubmit an application
// when its status is REVISION_REQUIRED.
//
// Delegate workflow operations to RevisionService.
class RevisionController extends Controller
{
    public function __construct(
        protected RevisionService $revisionService
    ) {}

    /**
     * Get revision requests for a project.
     */
    public function index(Request $request, Project $project): JsonResponse
    {
        if ($request->user()->cannot('view', $project)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view revisions for this project.',
            ], 403);
        }

        $perPage = (int) $request->query('per_page', 20);
        $revisions = $this->revisionService->getProjectRevisions($project, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Revisions retrieved successfully',
            'data' => RevisionResource::collection($revisions)->response()->getData(true),
        ]);
    }

    /**
     * Request a revision (Penilai only).
     */
    public function store(RevisionRequest $request, Project $project): JsonResponse
    {
        $revision = $this->revisionService->requestRevision(
            $project,
            $request->user(),
            $request->input('notes')
        );

        return response()->json([
            'success' => true,
            'message' => 'Revision requested successfully',
            'data' => new RevisionResource($revision),
        ], 201);
    }

    /**
     * Resubmit a revision-required project (Pemohon only).
     */
    public function resubmit(Request $request, Project $project): JsonResponse
    {
        if ($request->user()->cannot('resubmit', $project)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot resubmit this project. Only the project owner can resubmit a revision-required project.',
            ], 403);
        }

        $resubmitted = $this->revisionService->resubmitProject($project, $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Project resubmitted successfully for review',
            'data' => new ProjectResource($resubmitted),
        ]);
    }
}
