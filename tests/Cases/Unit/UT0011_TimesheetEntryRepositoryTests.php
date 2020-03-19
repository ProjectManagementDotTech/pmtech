<?php

namespace Tests\Cases\Unit;

use App\Repositories\TaskRepository;
use App\Repositories\TimesheetEntryRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use App\Task;
use App\TimesheetEntry;
use App\Workspace;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0011_TimesheetEntryRepositoryTests extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
    }

    /** @test */
    public function createTimesheetEntryWithoutAnyDetails()
    {
        Log::info(__METHOD__);

        $workspace = WorkspaceRepository::filter([
            'name' => 'UT0004-0001'
        ])[0];
        $user = $this->userRepository->findByEmail('user0004@test.com');
        TimesheetEntryRepository::create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'description' => ''
        ]);
        $this->assertDatabaseHas('timesheet_entries', [
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'description' => ''
        ]);
        $timesheetEntry = TimesheetEntry::query()
            ->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->where('description', '')
            ->first();
        $this->assertNotNull($timesheetEntry->started_at);
        $this->assertNull($timesheetEntry->ended_at);

        Log::debug('Timesheet Entry #' . $timesheetEntry->id);
        Log::debug('  started_at "' .
            $timesheetEntry->started_at->format('Y-m-d H:i:s') . '"');

        sleep(3);
    }

    /** @test */
    public function createManualTimesheetEntry()
    {
        Log::info(__METHOD__);

        $user = $this->userRepository->findByEmail('user0004@test.com');
        $workspace = WorkspaceRepository::filter([
            'name' => 'UT0004-0001'
        ])[0];
        TimesheetEntryRepository::create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'description' => 'UT0011-0001',
            'ended_at' => Carbon::now()->addSeconds(4),
            'started_at' => Carbon::now(),
        ]);
        $this->assertDatabaseHas('timesheet_entries', [
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'description' => 'UT0011-0001'
        ]);
        $timesheetEntry = TimesheetEntry::query()
            ->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->where('description', 'UT0011-0001')
            ->first();
        $this->assertNotNull($timesheetEntry->started_at);
        $this->assertNotNull($timesheetEntry->ended_at);

        sleep(8);
    }

    /** @test */
    public function createTimesheetEntryAgainstWorkspace()
    {
        Log::info(__METHOD__);

        $user = $this->userRepository->findByEmail('user0004@test.com');
        $workspace = WorkspaceRepository::filter([
            'name' => 'UT0004-0001'
        ])[0];
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

        sleep(3);
    }

    /** @test */
    public function createTimesheetEntryAgainstProject()
    {
        Log::info(__METHOD__);

        $user = $this->userRepository->findByEmail('user0001@test.com');
        $workspace = Workspace::where('name', 'Test0001')->first();
        $project = $workspace->projects[0];
        TimesheetEntryRepository::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
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

        sleep(3);
    }

    /** @test */
    public function createTimesheetEntryAgainstTask()
    {
        Log::info(__METHOD__);

        $task = Task::where('name', 'UT0009-0001')->first();
        $project = $task->project;
        $workspace = $project->workspace;
        $user = $workspace->ownerUser;

        $timesheetEntry = TimesheetEntry::query()
            ->where('user_id', $user->id)
            ->where('description', 'UT0011-0003')
            ->first();
        $this->assertNotNull($timesheetEntry);
        $timesheetEntry->ended_at = Carbon::now()->subSecond();
        $timesheetEntry->save();

        TimesheetEntryRepository::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
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

        sleep(3);
    }

    /** @test */
    public function updateTimesheetEntryDescription()
    {
        Log::info(__METHOD__);

        $user = $this->userRepository->findByEmail('user0004@test.com');
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

        $user = $this->userRepository->findByEmail('user0004@test.com');
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

        sleep(3);
    }

    /** @test */
    public function updateTimesheetEntryEndedAt()
    {
        Log::info(__METHOD__);

        $user = $this->userRepository->findByEmail('user0004@test.com');
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

    protected $userRepository;
}
