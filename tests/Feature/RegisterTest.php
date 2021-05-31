<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_empty_input()
    {
        $response = $this->postJson('/api/auth/register');
        $response->assertStatus(422)->assertJsonStructure(['message', 'errors']);
    }

    public function test_invalid_input()
    {
        $data = [
            'email' => $this->faker->name,
            'password' => $this->faker->password,
        ];

        $response = $this->postJson('/api/auth/register', $data);
        $response->assertStatus(422)->assertJsonStructure(['message', 'errors']);
    }

    public function test_register_with_success()
    {
        $password = $this->faker->password(8);

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ];

        $formData = [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => $password,
            'device_name' => 'ios',
        ];


        $response = $this->postJson('/api/auth/register', $formData);

        $this->assertDatabaseHas('users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure(['token']);
    }


    public function test_already_registered()
    {

        $formData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(8),
            'device_name' => 'ios',
        ];

        $this->postJson('/api/auth/register', $formData);

        $response = $this->postJson('/api/auth/register', $formData);

        $response->assertStatus(409)
            ->assertJsonStructure(['error']);
    }
}
