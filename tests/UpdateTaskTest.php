<?php

namespace Tests;

use App\Models\Task;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UpdateTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function test_update_task_correctly()
    {
        $task = Task::factory()->create();

        $response = $this->call('PUT', "/tasks/$task->id", [
            'status' => 'completed',
            'due_date' => Carbon::now()->addDays(10)->toDateString(),
        ]);

        $this->assertEquals(200, $response->status());
        $this->seeJson([
            'status' => 'completed',
        ]);

        // Check if the task was updated in db
        $this->seeInDatabase('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    public function test_rejects_invalid_requests()
    {
        $task = Task::factory()->create();

        $response = $this->call('PUT', "/tasks/$task->id", [
            'due_date' => Carbon::now()->subDays(1)->toDateString(),
        ]);

        $this->assertEquals(422, $response->status());

        // contains title validation error
        $this->seeJsonStructure(['due_date']);
    }

    public function test_rejects_update_missing_task()
    {
        $response = $this->call('PUT', "/tasks/999999", [
            'title' => 'Updated Task',
            'description' => 'Updated description',
            'status' => 'completed',
            'due_date' => Carbon::now()->addDays(10)->toDateString(),
        ]);

        // Check response status
        $this->assertEquals(404, $response->status());
    }
}
