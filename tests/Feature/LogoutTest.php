<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LogoutTest extends TestCase
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
        $response = $this->postJson('/api/auth/logout');
        $response->assertStatus(401)->assertJsonStructure(['message']);
    }
    public function test_logout_with_success()
    {
        $user = $this->createUser();
        $token = $user->createToken('ios')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/logout');

        $response->assertStatus(204);
    }
}
