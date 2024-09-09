<?php

namespace Tests;

use App\Models\Task;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DeleteTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function test_delete_task_correctly()
    {
        $task = Task::factory()->create();

        $response = $this->call('delete', "/tasks/$task->id");

        $this->assertEquals(200, $response->status());
        $this->seeJson([
            'message' => 'Task deleted successfully'
        ]);

        // Check if the task was deleted
        $this->notSeeInDatabase('tasks', ['id' => $task->id]);
    }

    public function test_delete_missing_task()
    {
        $response = $this->call('delete', "/tasks/99999");

        // Check response status
        $this->assertEquals(404, $response->status());

        // Check JSON response
        $this->seeJson([
            'message' => 'Task not found'
        ]);
    }
}
