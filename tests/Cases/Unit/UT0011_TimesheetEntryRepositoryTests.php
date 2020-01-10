<?php

namespace Tests\Unit;

use App\Repositories\TaskRepository;
use App\Repositories\TimesheetEntryRepository;
use App\Repositories\UserRepository;
use App\Task;
use App\TimesheetEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0011_TimesheetEntryRepositoryTests extends TestCase
{
    /** @test */
    public function createTimesheetEntryWithoutAnyDetails()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0004@test.com');
        TimesheetEntryRepository::create([
            'user_id' => $user->id,
            'description' => ''
        ]);
        $this->assertDatabaseHas('timesheet_entries', [
            'user_id' => $user->id,
            'description' => ''
        ]);
        $timesheetEntry = TimesheetEntry::query()
            ->where('user_id', $user->id)
            ->where('description', '')
            ->first();
        $this->assertNotNull($timesheetEntry->started_at);
        $this->assertNull($timesheetEntry->ended_at);
    }

    /** @test */
    public function createManualTimesheetEntry()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0005@test.com');
        TimesheetEntryRepository::create([
            'user_id' => $user->id,
            'description' => 'UT0011-0001',
            'ended_at' => Carbon::now(),
            'started_at' => Carbon::now()->subHour(),
        ]);
        $this->assertDatabaseHas('timesheet_entries', [
            'user_id' => $user->id,
            'description' => 'UT0011-0001'
        ]);
        $timesheetEntry = TimesheetEntry::query()
            ->where('user_id', $user->id)
            ->where('description', 'UT0011-0001')
            ->first();
        $this->assertNotNull($timesheetEntry->started_at);
        $this->assertNotNull($timesheetEntry->ended_at);
    }

    /** @test */
    public function createTimesheetEntryAgainstWorkspace()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0004@test.com');
        $workspace = $user->ownedWorkspaces[0];
        TimesheetEntryRepository::create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'description' => 'UT0011-0002',
        ]);
        $this->assertDatabaseHas('timesheet_entries', [
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'description' => 'UT0011-0002'
        ]);
        $timesheetEntry = TimesheetEntry::query()
            ->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->where('description', 'UT0011-0002')
            ->first();
        $this->assertNotNull($timesheetEntry->started_at);
        $this->assertNull($timesheetEntry->ended_at);
    }

    /** @test */
    public function createTimesheetEntryAgainstProject()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0001@test.com');
        $workspace = $user->ownedWorkspaces[0];
        $project = $workspace->projects[0];
        TimesheetEntryRepository::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'description' => 'UT0011-0003',
        ]);
        $this->assertDatabaseHas('timesheet_entries', [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'description' => 'UT0011-0003'
        ]);
        $timesheetEntry = TimesheetEntry::query()
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->where('description', 'UT0011-0003')
            ->first();
        $this->assertNotNull($timesheetEntry->started_at);
        $this->assertNull($timesheetEntry->ended_at);
    }

    /** @test */
    public function createTimesheetEntryAgainstTask()
    {
        Log::info(__METHOD__);

        $task = Task::where('name', 'UT0009-0001')->first();
        $project = $task->project;
        $workspace = $project->workspace;
        $user = $workspace->ownerUser;
        TimesheetEntryRepository::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'description' => 'UT0011-0004',
        ]);
        $this->assertDatabaseHas('timesheet_entries', [
            'project_id' => $project->id,
            'task_id' => $task->id,
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'description' => 'UT0011-0004'
        ]);
        $timesheetEntry = TimesheetEntry::query()
            ->where('project_id', $project->id)
            ->where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->where('description', 'UT0011-0004')
            ->first();
        $this->assertNotNull($timesheetEntry->started_at);
        $this->assertNull($timesheetEntry->ended_at);
    }

    /** @test */
    public function updateTimesheetEntryDescription()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0004@test.com');
        $timesheetEntry = TimesheetEntry::query()
            ->where('user_id', $user->id)
            ->where('description', '')
            ->first();
        TimesheetEntryRepository::update($timesheetEntry, [
            'description' => 'UT0011-0005'
        ]);
        $this->assertDatabaseHas('timesheet_entries', [
            'user_id' => $user->id,
            'description' => 'UT0011-0005'
        ]);
    }

    /** @test */
    public function updateTimesheetEntryStartedAt()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0004@test.com');
        $timesheetEntry = TimesheetEntry::query()
            ->where('user_id', $user->id)
            ->where('description', 'UT0011-0005')
            ->first();
        $newStartedAt = Carbon::now();
        $this->assertNotEquals($newStartedAt, $timesheetEntry->started_at);
        TimesheetEntryRepository::update($timesheetEntry, [
            'started_at' => $newStartedAt
        ]);
        $this->assertDatabaseHas('timesheet_entries', [
            'user_id' => $user->id,
            'description' => 'UT0011-0005',
            'started_at' => $newStartedAt
        ]);
    }

    /** @test */
    public function updateTimesheetEntryEndedAt()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0004@test.com');
        $timesheetEntry = TimesheetEntry::query()
            ->where('user_id', $user->id)
            ->where('description', 'UT0011-0005')
            ->first();
        $newEndedAt = Carbon::now()->addMinute();
        $this->assertNotEquals($newEndedAt, $timesheetEntry->started_at);
        TimesheetEntryRepository::update($timesheetEntry, [
            'ended_at' => $newEndedAt
        ]);
        $this->assertDatabaseHas('timesheet_entries', [
            'user_id' => $user->id,
            'description' => 'UT0011-0005',
            'ended_at' => $newEndedAt
        ]);
    }

    /** @test */
    public function archiveTimesheetEntry()
    {
        Log::info(__METHOD__);

        $timesheetEntry = TimesheetEntry::query()
            ->where('description', 'UT0011-0005')
            ->first();
        TimesheetEntryRepository::archive($timesheetEntry);
        $this->assertSoftDeleted('timesheet_entries', [
            'description' => 'UT0011-0005',
        ]);
    }

    /** @test */
    public function restoreTimesheetEntry()
    {
        Log::info(__METHOD__);

        $timesheetEntry = TimesheetEntry::withTrashed()
            ->where('description', 'UT0011-0005')
            ->first();
        $this->assertNotNull($timesheetEntry->deleted_at);
        TimesheetEntryRepository::restore($timesheetEntry);
        $timesheetEntry = TimesheetEntry::query()
            ->where('description', 'UT0011-0005')
            ->first();
        $this->assertNull($timesheetEntry->deleted_at);
    }

    /** @test */
    public function deleteTimesheetEntry()
    {
        Log::info(__METHOD__);

        $timesheetEntry = TimesheetEntry::withTrashed()
            ->where('description', 'UT0011-0005')
            ->first();
        TimesheetEntryRepository::delete($timesheetEntry);
        $this->assertDatabaseMissing('timesheet_entries', [
            'description' => 'UT0011-0005'
        ]);
    }
}
