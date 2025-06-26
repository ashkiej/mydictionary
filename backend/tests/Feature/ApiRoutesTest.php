<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class ApiRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanRegister()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function testUserCanLoginAndAccessProtectedRoute(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $login = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $login->assertStatus(200);
        $token = $login->json('token');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/user');

        $response->assertStatus(200)
            ->assertJson(['email' => $user->email]);
    }

    public function testDictionarySearchRequiresAuthentication(): void
    {
        $response = $this->getJson('/api/dictionary/search?word=test');
        $response->assertStatus(401);
    }
}
