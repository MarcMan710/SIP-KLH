<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\SubmitProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// Handle project/application REST API requests.
//
// Operations:
// - list projects
// - create project
// - show project details
// - update draft project
// - delete draft project
// - submit project
//
// Use ProjectService for business logic.
//
// Use ProjectPolicy to verify ownership and authorization.
//
// Use pagination for project listings.
//
// Support filtering and searching by relevant fields.
//
// Return ProjectResource rather than exposing database
// models directly.
class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {}

    /**
     * List projects with pagination and filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 20);
        $filters = $request->only(['status', 'search']);

        $projects = $this->projectService->getProjects($request->user(), $filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Projects retrieved successfully',
            'data' => ProjectResource::collection($projects)->response()->getData(true),
        ]);
    }

    /**
     * Create a new draft project.
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->createProject($request->user(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully as DRAFT',
            'data' => new ProjectResource($project),
        ], 201);
    }

    /**
     * Display the specified project with its relationships.
     */
    public function show(Request $request, Project $project): JsonResponse
    {
        if ($request->user()->cannot('view', $project)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to view this project.',
            ], 403);
        }

        $project->load([
            'user:id,name,email',
            'documents.user:id,name,email',
            'assessments.assessor:id,name,email',
            'revisions.requester:id,name,email',
            'activityLogs.user:id,name,email,role',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project retrieved successfully',
            'data' => new ProjectResource($project),
        ]);
    }

    /**
     * Update an editable project.
     */
    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $updatedProject = $this->projectService->updateProject($project, $request->user(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => new ProjectResource($updatedProject),
        ]);
    }

    /**
     * Delete a draft project.
     */
    public function destroy(Request $request, Project $project): JsonResponse
    {
        if ($request->user()->cannot('delete', $project)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete this project because it is not in Draft status or you are not the owner.',
            ], 403);
        }

        $this->projectService->deleteProject($project);

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
            'data' => null,
        ]);
    }

    /**
     * Submit draft project for assessment.
     */
    public function submit(SubmitProjectRequest $request, Project $project): JsonResponse
    {
        $submitted = $this->projectService->submitProject($project, $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Project submitted for assessment successfully',
            'data' => new ProjectResource($submitted),
        ]);
    }
}
