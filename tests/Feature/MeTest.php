<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MeTest extends TestCase
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
        $response = $this->postJson('/api/auth/me');
        $response->assertStatus(401)->assertJsonStructure(['message']);
    }
    public function test_get_user_informations()
    {
        $user = $this->createUser();
        $user->createToken('ios')->plainTextToken;

        $response = $this->actingAs($user)->postJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'created_at', 'updated_at', 'name', 'email'])
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]);
    }
}
