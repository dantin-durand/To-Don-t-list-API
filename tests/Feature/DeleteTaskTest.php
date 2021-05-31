<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DeleteTaskTest extends TestCase
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
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401)->assertJsonStructure(['message']);
    }

    public function test_task_do_not_exist()
    {
        $user = $this->createUser();
        $user->createToken('ios')->plainTextToken;

        $response = $this->actingAs($user)->deleteJson('/api/tasks/1');

        $response->assertStatus(404)->assertJsonStructure(['error']);
    }

    public function test_unauthorized_delete_task()
    {
        $user = $this->createUser();
        $user2 = $this->createUser();

        $user->createToken('ios')->plainTextToken;
        $this->createTask($user2->id, 3);

        $response = $this->actingAs($user)->deleteJson('/api/tasks/1');

        $response->assertStatus(403)->assertJsonStructure(['error']);
    }

    public function test_delete_task_with_success()
    {
        $user = $this->createUser();

        $user->createToken('ios')->plainTextToken;
        $this->createTask($user->id, 3);

        $response = $this->actingAs($user)->deleteJson('/api/tasks/1');

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
