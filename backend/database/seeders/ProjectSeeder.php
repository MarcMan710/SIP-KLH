<?php

namespace Database\Seeders;

use App\Enums\AssessmentAction;
use App\Enums\ProjectStatus;
use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\Document;
use App\Models\Project;
use App\Models\Revision;
use App\Models\User;
use Illuminate\Database\Seeder;

// Generate sample projects for development and testing.
//
// Create projects with different statuses:
//
// - Draft
// - Submitted
// - Under Review
// - Revision Required
// - Approved
// - Rejected
//
// Create related documents, assessments,
// revisions, and activity logs where useful.
//
// The document specifies a target scale of
// approximately 10,000 project records and 2,000 users,
// so factories should be capable of generating
// realistic larger datasets for performance testing.
class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pemohon = User::where('email', 'pemohon@sipklh.id')->first()
            ?? User::factory()->pemohon()->create(['email' => 'pemohon@sipklh.id']);

        $penilai = User::where('email', 'penilai@sipklh.id')->first()
            ?? User::factory()->penilai()->create(['email' => 'penilai@sipklh.id']);

        $sampleProjects = [
            [
                'name' => 'Dokumen Kelayakan Lingkungan Pembangunan Pabrik Semen',
                'description' => 'Permohonan dokumen kelayakan lingkungan hidup untuk rencana pembangunan pabrik semen di kawasan industri.',
                'status' => ProjectStatus::DRAFT,
                'submitted_at' => null,
            ],
            [
                'name' => 'Kajian Lingkungan Pembangunan Pelabuhan Logistik',
                'description' => 'Studi kelayakan AMDAL dan pengelolaan limbah maritim untuk pelabuhan logistik terpadu.',
                'status' => ProjectStatus::SUBMITTED,
                'submitted_at' => now()->subDays(2),
            ],
            [
                'name' => 'Dokumen Evaluasi Lingkungan PLTS 50MW',
                'description' => 'Evaluasi dampak lingkungan proyek Pembangkit Listrik Tenaga Surya skala utilitas.',
                'status' => ProjectStatus::UNDER_REVIEW,
                'submitted_at' => now()->subDays(5),
            ],
            [
                'name' => 'AMDAL Kawasan Industri Terpadu Hijau',
                'description' => 'Penyusunan dokumen kelayakan lingkungan zona industri terpadu berwawasan lingkungan.',
                'status' => ProjectStatus::REVISION_REQUIRED,
                'submitted_at' => now()->subDays(10),
            ],
            [
                'name' => 'Dokumen Kelayakan Pengelolaan Limbah B3 Rumah Sakit',
                'description' => 'Instalasi pengolahan dan izin kelayakan lingkungan fasilitas insinerator medis.',
                'status' => ProjectStatus::APPROVED,
                'submitted_at' => now()->subDays(15),
            ],
            [
                'name' => 'Permohonan Reklamasi Pesisir Pantai Marina',
                'description' => 'Kajian dampak lingkungan hidup permohonan izin reklamasi pesisir pantai marina.',
                'status' => ProjectStatus::REJECTED,
                'submitted_at' => now()->subDays(20),
            ],
        ];

        foreach ($sampleProjects as $index => $data) {
            $num = str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);
            $project = Project::updateOrCreate(
                ['project_number' => 'PRJ-'.now()->format('Ymd')."-{$num}"],
                [
                    'user_id' => $pemohon->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'status' => $data['status'],
                    'submitted_at' => $data['submitted_at'],
                ]
            );

            // Add sample document
            Document::create([
                'project_id' => $project->id,
                'user_id' => $pemohon->id,
                'document_type' => 'AMDAL_UTAMA',
                'original_name' => 'Dokumen_AMDAL_'.$project->id.'.pdf',
                'file_path' => "documents/{$project->id}/sample.pdf",
                'mime_type' => 'application/pdf',
                'file_size' => 1024 * 1024 * 2, // 2MB
                'uploaded_at' => now()->subDays(10),
            ]);

            // Activity Log
            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => $pemohon->id,
                'action' => 'PROJECT_CREATED',
                'old_status' => null,
                'new_status' => ProjectStatus::DRAFT->value,
                'description' => "Proyek {$project->project_number} dibuat oleh {$pemohon->name}.",
            ]);

            if ($data['status'] !== ProjectStatus::DRAFT) {
                ActivityLog::create([
                    'project_id' => $project->id,
                    'user_id' => $pemohon->id,
                    'action' => 'PROJECT_SUBMITTED',
                    'old_status' => ProjectStatus::DRAFT->value,
                    'new_status' => ProjectStatus::SUBMITTED->value,
                    'description' => "Proyek {$project->project_number} diajukan untuk penilaian.",
                ]);
            }

            // Status-specific assessment and revision seeding
            if ($data['status'] === ProjectStatus::REVISION_REQUIRED) {
                $assessment = Assessment::create([
                    'project_id' => $project->id,
                    'assessor_id' => $penilai->id,
                    'action' => AssessmentAction::REQUEST_REVISION,
                    'notes' => 'Perbaiki kajian dampak limpasan air hujan dan sertakan sertifikasi laboratorium lingkungan terakreditasi KAN.',
                    'assessed_at' => now()->subDays(8),
                ]);

                Revision::create([
                    'project_id' => $project->id,
                    'assessment_id' => $assessment->id,
                    'requester_id' => $penilai->id,
                    'notes' => 'Perbaiki kajian limpasan air hujan & sertakan akreditasi lab KAN.',
                    'resolved_at' => null,
                ]);

                ActivityLog::create([
                    'project_id' => $project->id,
                    'user_id' => $penilai->id,
                    'action' => 'REVISION_REQUESTED',
                    'old_status' => ProjectStatus::UNDER_REVIEW->value,
                    'new_status' => ProjectStatus::REVISION_REQUIRED->value,
                    'description' => 'Penilai meminta revisi dokumen kelayakan lingkungan.',
                ]);
            } elseif ($data['status'] === ProjectStatus::APPROVED) {
                Assessment::create([
                    'project_id' => $project->id,
                    'assessor_id' => $penilai->id,
                    'action' => AssessmentAction::APPROVE,
                    'notes' => 'Dokumen kelayakan telah memenuhi seluruh baku mutu lingkungan hidup berdasarkan PP No. 22 Tahun 2021.',
                    'assessed_at' => now()->subDays(12),
                ]);

                ActivityLog::create([
                    'project_id' => $project->id,
                    'user_id' => $penilai->id,
                    'action' => 'PROJECT_APPROVED',
                    'old_status' => ProjectStatus::UNDER_REVIEW->value,
                    'new_status' => ProjectStatus::APPROVED->value,
                    'description' => 'Dokumen kelayakan lingkungan hidup telah disetujui.',
                ]);
            } elseif ($data['status'] === ProjectStatus::REJECTED) {
                Assessment::create([
                    'project_id' => $project->id,
                    'assessor_id' => $penilai->id,
                    'action' => AssessmentAction::REJECT,
                    'notes' => 'Rencana reklamasi berada pada zona konservasi terumbu karang yang dilindungi.',
                    'assessed_at' => now()->subDays(18),
                ]);

                ActivityLog::create([
                    'project_id' => $project->id,
                    'user_id' => $penilai->id,
                    'action' => 'PROJECT_REJECTED',
                    'old_status' => ProjectStatus::UNDER_REVIEW->value,
                    'new_status' => ProjectStatus::REJECTED->value,
                    'description' => 'Dokumen kelayakan lingkungan hidup ditolak karena berada di zona konservasi.',
                ]);
            }
        }
    }
}
