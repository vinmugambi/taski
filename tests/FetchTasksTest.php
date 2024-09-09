<?php

namespace Tests;

use App\Models\Task;
use Laravel\Lumen\Testing\DatabaseTransactions;


class FetchTasksTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_many_tasks()
    {
        Task::factory()->count(5)->create();

        $response = $this->call('GET', '/tasks');
        $this->assertEquals(200, $response->status());
    }

    public function test_filter_tasks_by_status()
    {
        Task::factory()->create(['status' => 'pending']);
        Task::factory()->create(['status' => 'completed']);

        $response = $this->call('GET', '/tasks?status=pending');
        $response->assertStatus(200);
        $tasks = json_decode($response->getContent(), true);

        // dd($tasks);

        // Only tasks with 'pending' status are returned
        foreach ($tasks['data'] as $task) {
            $this->assertEquals('pending', $task['status']);
        }
    }

    public function test_filter_tasks_by_due_date()
    {
        Task::factory()->create(['due_date' => '2024-09-15']);
        Task::factory()->create(['due_date' => '2024-09-20']);

        $response = $this->call('GET', '/tasks?due_date=2024-09-15');

        $response->assertStatus(200);

        // Verify that every task returned has the '2024-09-15' due date
        $tasks = json_decode($response->getContent(), true);
        foreach ($tasks['data'] as $task) {
            $this->assertEquals('2024-09-15', $task['due_date']);
        }
    }

    public function test_task_pagination()
    {
        Task::factory()->count(15)->create();

        $response = $this->call('GET', '/tasks?per_page=5');

        $response->assertStatus(200);

        // Ensure pagination structure
        $this->seeJsonStructure([
            'current_page',
            'data' => [
                '*' => ['id', 'title', 'description', 'status', 'due_date', 'created_at', 'updated_at']
            ],
            'first_page_url',
            'from',
            'last_page',
            'next_page_url',
            'path',
            'per_page',
            'total'
        ]);

        // Assert that only 5 tasks are returned per page
        $tasks = json_decode($response->getContent(), true);
        $this->assertCount(5, $tasks['data']);
    }

    public function test_search_tasks_by_title()
    {
        Task::factory()->create(['title' => 'Meeting with John']);
        Task::factory()->create(['title' => 'Complete assignment']);

        $response = $this->call('GET', '/tasks?search=Meeting');

        $response->assertStatus(200);

        // Ensure the correct task is returned
        $tasks = json_decode($response->getContent(), true);
        foreach ($tasks['data'] as $task) {
            $this->assertStringContainsString('Meeting', $task['title']);
        }
    }
}
