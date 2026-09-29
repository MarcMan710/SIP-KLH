<?php

namespace App\Services;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

// Calculate dashboard statistics.
//
// Provide separate statistics for:
// - Pemohon
// - Penilai
//
// Optimize aggregation queries.
//
// Use database-level COUNT and GROUP BY operations
// rather than retrieving all projects into application memory.
//
// Cache frequently requested statistics when appropriate.
//
// Provide chart-ready aggregated data for the Vue frontend.
class DashboardService
{
    /**
     * Get statistics for Pemohon (applicant) dashboard.
     */
    public function getPemohonStats(User $user): array
    {
        $cacheKey = "dashboard_pemohon_{$user->id}";

        return Cache::remember($cacheKey, 60, function () use ($user) {
            $counts = Project::query()
                ->where('user_id', $user->id)
                ->selectRaw('
                    COUNT(*) as total_projects,
                    COUNT(CASE WHEN status = ? THEN 1 END) as draft_count,
                    COUNT(CASE WHEN status = ? THEN 1 END) as submitted_count,
                    COUNT(CASE WHEN status IN (?, ?) THEN 1 END) as under_review_count,
                    COUNT(CASE WHEN status = ? THEN 1 END) as revision_required_count,
                    COUNT(CASE WHEN status = ? THEN 1 END) as approved_count,
                    COUNT(CASE WHEN status = ? THEN 1 END) as rejected_count
                ', [
                    ProjectStatus::DRAFT->value,
                    ProjectStatus::SUBMITTED->value,
                    ProjectStatus::UNDER_REVIEW->value,
                    ProjectStatus::RESUBMITTED->value,
                    ProjectStatus::REVISION_REQUIRED->value,
                    ProjectStatus::APPROVED->value,
                    ProjectStatus::REJECTED->value,
                ])
                ->first();

            return [
                'total_projects' => (int) ($counts->total_projects ?? 0),
                'draft_count' => (int) ($counts->draft_count ?? 0),
                'submitted_count' => (int) ($counts->submitted_count ?? 0),
                'under_review_count' => (int) ($counts->under_review_count ?? 0),
                'revision_required_count' => (int) ($counts->revision_required_count ?? 0),
                'approved_count' => (int) ($counts->approved_count ?? 0),
                'rejected_count' => (int) ($counts->rejected_count ?? 0),
                'chart_data' => [
                    'by_status' => [
                        'Draft' => (int) ($counts->draft_count ?? 0),
                        'Submitted' => (int) ($counts->submitted_count ?? 0),
                        'Under Review' => (int) ($counts->under_review_count ?? 0),
                        'Revision Required' => (int) ($counts->revision_required_count ?? 0),
                        'Approved' => (int) ($counts->approved_count ?? 0),
                        'Rejected' => (int) ($counts->rejected_count ?? 0),
                    ],
                ],
            ];
        });
    }

    /**
     * Get statistics for Penilai (assessor) dashboard.
     */
    public function getPenilaiStats(User $user): array
    {
        $cacheKey = "dashboard_penilai_{$user->id}";

        return Cache::remember($cacheKey, 60, function () {
            $counts = Project::query()
                ->selectRaw('
                    COUNT(*) as total_applications,
                    COUNT(CASE WHEN status IN (?, ?, ?) THEN 1 END) as pending_assessments,
                    COUNT(CASE WHEN status = ? THEN 1 END) as revision_requests,
                    COUNT(CASE WHEN status = ? THEN 1 END) as approved_applications,
                    COUNT(CASE WHEN status = ? THEN 1 END) as rejected_applications
                ', [
                    ProjectStatus::SUBMITTED->value,
                    ProjectStatus::UNDER_REVIEW->value,
                    ProjectStatus::RESUBMITTED->value,
                    ProjectStatus::REVISION_REQUIRED->value,
                    ProjectStatus::APPROVED->value,
                    ProjectStatus::REJECTED->value,
                ])
                ->first();

            return [
                'total_applications' => (int) ($counts->total_applications ?? 0),
                'pending_assessments' => (int) ($counts->pending_assessments ?? 0),
                'revision_requests' => (int) ($counts->revision_requests ?? 0),
                'approved_applications' => (int) ($counts->approved_applications ?? 0),
                'rejected_applications' => (int) ($counts->rejected_applications ?? 0),
                'chart_data' => [
                    'by_status' => [
                        'Pending Review' => (int) ($counts->pending_assessments ?? 0),
                        'Revision Required' => (int) ($counts->revision_requests ?? 0),
                        'Approved' => (int) ($counts->approved_applications ?? 0),
                        'Rejected' => (int) ($counts->rejected_applications ?? 0),
                    ],
                ],
            ];
        });
    }
}
