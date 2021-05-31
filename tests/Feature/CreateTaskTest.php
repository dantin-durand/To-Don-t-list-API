<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateTaskTest extends TestCase
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

    public function test_invalid_token()
    {
        $task = [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
            'done' => false
        ];
        $response = $this->postJson('/api/tasks', $task);
        $response->assertStatus(401)->assertJsonStructure(['message']);
    }

    public function test_empty_input()
    {
        $user = $this->createUser();
        $user->createToken('ios')->plainTextToken;

        $response = $this->actingAs($user)->postJson('/api/tasks');
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

        $response = $this->actingAs($user)->postJson('/api/tasks');
        $response->assertStatus(422)->assertJsonStructure(['message', 'errors']);
    }

    public function test_create_task()
    {
        $user = $this->createUser();
        $user->createToken('ios')->plainTextToken;

        $task = [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
            'done' => false
        ];

        $response = $this->actingAs($user)->postJson('/api/tasks', $task);

        $response->assertStatus(201)
            ->assertJsonStructure([
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
