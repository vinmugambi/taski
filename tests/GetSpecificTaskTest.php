<?php

namespace Tests;

use App\Models\Task;
use Laravel\Lumen\Testing\DatabaseTransactions;

class GetSpecificTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function test_existing_task()
    {
        $task = Task::factory()->create();

        $response = $this->call('GET', "/tasks/$task->id");

        // retrieve task
        $this->assertEquals(200, $response->status());
        $this->seeJson([
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
        ]);
    }

    public function test_get_missing_task()
    {
        $response = $this->call('GET', '/tasks/999999');

        // Not found
        $this->assertEquals(404, $response->status());
        $this->seeJson([
            'message' => 'Task not found'
        ]);
    }
}
