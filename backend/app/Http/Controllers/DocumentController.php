<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document\UploadDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Models\Project;
use App\Services\DocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// Handle document-related API operations.
//
// Operations:
// - list project documents
// - upload document
// - view document metadata
// - delete document when allowed
//
// Delegate storage and file handling to DocumentService.
//
// Validate permissions before allowing access.
//
// Do not expose internal filesystem paths unnecessarily.
//
// Return DocumentResource for API responses.
class DocumentController extends Controller
{
    public function __construct(
        protected DocumentService $documentService
    ) {}

    /**
     * List documents belonging to a project.
     */
    public function index(Request $request, Project $project): JsonResponse
    {
        if ($request->user()->cannot('view', $project)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view project documents.',
            ], 403);
        }

        $documents = $project->documents()->with('user:id,name,email')->get();

        return response()->json([
            'success' => true,
            'message' => 'Documents retrieved successfully',
            'data' => DocumentResource::collection($documents),
        ]);
    }

    /**
     * Upload supporting document for a project.
     */
    public function store(UploadDocumentRequest $request, Project $project): JsonResponse
    {
        $file = $request->file('file');
        $documentType = $request->input('document_type');

        $document = $this->documentService->uploadDocument($project, $request->user(), $file, $documentType);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully',
            'data' => new DocumentResource($document),
        ], 201);
    }

    /**
     * Delete document.
     */
    public function destroy(Request $request, Document $document): JsonResponse
    {
        if ($request->user()->cannot('delete', $document)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this document.',
            ], 403);
        }

        $this->documentService->deleteDocument($document, $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully',
            'data' => null,
        ]);
    }
}
