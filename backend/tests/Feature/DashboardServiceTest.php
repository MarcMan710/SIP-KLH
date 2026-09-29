<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_applicant_stats_aggregate_project_statuses_with_one_query(): void
    {
        $user = User::factory()->create();
        Project::factory()->for($user)->draft()->create();
        Project::factory()->for($user)->submitted()->create();

        DB::enableQueryLog();
        DB::flushQueryLog();

        $stats = app(DashboardService::class)->getPemohonStats($user);

        $projectQueries = collect(DB::getQueryLog())
            ->filter(fn (array $query): bool => str_contains($query['query'], 'from "projects"'));

        $this->assertCount(1, $projectQueries);
        $this->assertSame(2, $stats['total_projects']);
        $this->assertSame(1, $stats['draft_count']);
        $this->assertSame(1, $stats['submitted_count']);
        $this->assertSame([
            'Draft' => 1,
            'Submitted' => 1,
            'Under Review' => 0,
            'Revision Required' => 0,
            'Approved' => 0,
            'Rejected' => 0,
        ], $stats['chart_data']['by_status']);
    }
}
