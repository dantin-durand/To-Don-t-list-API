<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EditTaskTest extends TestCase
{
    public function createUser()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->faker->password(8)),
        ];

        return User::create($userData);
    }

    public function createTask($user_id, $numberGen)
    {
        for ($i = 0; $i < $numberGen; $i++) {

            $taskData = [
                'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
                'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
                'done' => false,
                'user_id' => $user_id,
            ];
            Task::create($taskData);
        }
        return;
    }

    public function test_invalid_token()
    {
        $task = [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
            'done' => false
        ];

        $response = $this->putJson('/api/tasks/1', $task);
        $response->assertStatus(401)->assertJsonStructure(['message']);
    }

    public function test_task_do_not_exist()
    {
        $user = $this->createUser();
        $user->createToken('ios')->plainTextToken;

        $task = [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
            'done' => false
        ];

        $response = $this->actingAs($user)->putJson('/api/tasks/1', $task);

        $response->assertStatus(404)->assertJsonStructure(['error']);
    }

    public function test_unauthorized_edit_task()
    {
        $user = $this->createUser();
        $user2 = $this->createUser();

        $user->createToken('ios')->plainTextToken;
        $this->createTask($user2->id, 3);

        $task = [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
            'done' => false
        ];

        $response = $this->actingAs($user)->putJson('/api/tasks/1', $task);

        $response->assertStatus(403)->assertJsonStructure(['error']);
    }

    public function test_empty_inputs()
    {
        $user = $this->createUser();
        $user->createToken('ios')->plainTextToken;
        $this->createTask($user->id, 1);

        $response = $this->actingAs($user)->putJson('/api/tasks/1');
        $response->assertStatus(422)->assertJsonStructure(['message', 'errors']);
    }

    public function test_invalid_input()
    {
        $user = $this->createUser();
        $user->createToken('ios')->plainTextToken;

        $task = [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
            'done' => 'false'
        ];

        $response = $this->actingAs($user)->putJson('/api/tasks/1', $task);
        $response->assertStatus(422)->assertJsonStructure(['message', 'errors']);
    }

    public function test_edit_task_with_success()
    {
        $user = $this->createUser();

        $user->createToken('ios')->plainTextToken;
        $this->createTask($user->id, 3);

        $task = [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
            'done' => false
        ];

        $response = $this->actingAs($user)->putJson('/api/tasks/1', $task);

        $response->assertStatus(200)->assertJsonStructure([
            'id',
            'created_at',
            'updated_at',
            'body',
            'done',
            'user' => [
                'id',
                'created_at',
                'updated_at',
                'name',
                'email'
            ]
        ]);
    }
}
