<?php

namespace Tests;

use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseTransactions;


class CreateTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function test_saves_a_valid_task()
    {
        $this->call('POST', '/tasks', [
            'title' => 'Not very urgent',
            'description' => "Due in five days",
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(5)->toDateString(),
        ]);


        $this->seeStatusCode(201);
        $this->seeJson([
            'title' => 'Not very urgent',
            'description' => "Due in five days",
            'status' => 'pending',
        ]);

        // Task was insterted to db
        $this->seeInDatabase('tasks', ['title' => 'Not very urgent']);
    }

    public function test_rejects_an_invalid_task()
    {
        $response = $this->post('/tasks', []);

        $this->seeStatusCode(422);
        $response->seeJsonStructure(["title"]);
    }
}
