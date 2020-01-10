<?php

namespace Tests\Cases\Unit;

use App\Project;
use App\Repositories\TaskRepository;
use App\Task;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Tests\Shared\TestCase;

class UT0009_TaskRepositoryTests extends TestCase
{
    /** @test */
    public function createTask()
    {
        Log::info(__METHOD__);

        $project = Project::where('name', 'UT0007-0001')->first();
        TaskRepository::create([
            'project_id' => $project->id,
            'name' => 'UT0009-0001'
        ]);

        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'name' => 'UT0009-0001',
            'wbs' => '1'
        ]);
    }

    /** @test */
    public function updateTaskName()
    {
        Log::info(__METHOD__);

        $task = Task::where('name', 'UT0009-0001')->first();
        TaskRepository::update($task, [
            'name' => 'UT0009-0002'
        ]);

        $this->assertDatabaseMissing('tasks', [
            'name' => 'UT0009-0001',
            'wbs' => '1'
        ]);
        $this->assertDatabaseHas('tasks', [
            'name' => 'UT0009-0002',
            'wbs' => '1'
        ]);
    }

    /** @test */
    public function updateTaskProjectId()
    {
        Log::info(__METHOD__);

        $task = Task::where('name', 'UT0009-0002')->first();
        $newProjectId = Uuid::uuid4()->toString();
        TaskRepository::update($task, [
            'project_id' => $newProjectId
        ]);

        $this->assertDatabaseMissing('tasks', [
            'project_id' => $newProjectId,
            'name' => 'UT0009-0002',
            'wbs' => '1'
        ]);
        $this->assertDatabaseHas('tasks', [
            'project_id' => $task->project->id,
            'name' => 'UT0009-0002',
            'wbs' => '1'
        ]);
    }

    /** @test */
    public function updateTaskId()
    {
        Log::info(__METHOD__);

        $task = Task::where('name', 'UT0009-0002')->first();
        $newId = Uuid::uuid4()->toString();
        TaskRepository::update($task, [
            'id' => $newId
        ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $newId,
            'name' => 'UT0009-0002',
            'wbs' => '1'
        ]);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'UT0009-0002',
            'wbs' => '1'
        ]);
    }

    /** @test */
    public function getTask()
    {
        Log::info(__METHOD__);

        $temp = Task::where('name', 'UT0009-0002')->first();

        $task = TaskRepository::get($temp->id);

        $this->assertNotNull($task);
        $this->assertEquals($temp->id, $task->id);
        $this->assertEquals($temp->project_id, $task->project_id);
        $this->assertEquals($temp->name, $task->name);
        $this->assertEquals($temp->wbs, $task->wbs);
        $this->assertEquals($temp->created_at, $task->created_at);
        $this->assertEquals($temp->updated_at, $task->updated_at);
        $this->assertEquals($temp->deleted_at, $task->deleted_at);
    }

    /** @test */
    public function archiveTask()
    {
        Log::info(__METHOD__);

        $task = Task::where('name', 'UT0009-0002')->first();

        TaskRepository::archive($task);

        $this->assertSoftDeleted('tasks', [
            'id' => $task->id
        ]);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id
        ]);
    }

    /** @test */
    public function restoreTask()
    {
        Log::info(__METHOD__);

        $task = Task::withTrashed()
            ->where('name', 'UT0009-0002')
            ->first();

        TaskRepository::restore($task);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id
        ]);

        $task = TaskRepository::get($task->id);
        $this->assertEquals(NULL, $task->deleted_at);
        $this->assertEquals('UT0009-0002', $task->name);
    }

    /** @test */
    public function restoreTaskById()
    {
        Log::info(__METHOD__);

        $project = Project::where('name', 'UT0007-0001')->first();
        $task = TaskRepository::create([
            'project_id' => $project->id,
            'name' => 'UT0009-0001'
        ]);

        TaskRepository::archive($task);

        $task = Task::withTrashed()
            ->where('name', 'UT0009-0001')
            ->first();
        $this->assertNotNull($task->deleted_at);

        $restoredTask = TaskRepository::restoreById($task->id);
        $this->assertNull($restoredTask->deleted_at);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'deleted_at' => NULL
        ]);
    }

    /** @test */
    public function deleteTask()
    {
        Log::info(__METHOD__);

        $project = Project::where('name', 'UT0007-0001')->first();
        foreach($project->tasks as $task) {
            if($task->name != 'UT0009-0001') {
                $this->assertDatabaseHas('tasks', [
                    'id' => $task->id,
                    'project_id' => $project->id,
                    'name' => $task->name
                ]);
                TaskRepository::delete($task);
                $this->assertDatabaseMissing('tasks', [
                    'id' => $task->id,
                    'project_id' => $project->id,
                    'name' => $task->name
                ]);
            }
        }

        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'name' => 'UT0009-0001'
        ]);
    }
}
