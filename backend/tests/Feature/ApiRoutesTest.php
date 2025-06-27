<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Favorite;

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
            ->getJson('/api/auth/loadUser');

        $response->assertStatus(200)
            ->assertJson(['user' => ['email' => $user->email]]);
    }

    public function testDictionarySearchRequiresAuthentication(): void
    {
        $response = $this->getJson('/api/dictionary/search?word=test');
        $response->assertStatus(401);
    }

    public function testUserCanCreateFavorite(): void
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
            ->postJson('/api/favorites', [
                'word' => 'test',
                'phonetics' => ['test'],
                'definitions' => ['test'],
                'part_of_speech' => ['test'],
                'example_usage' => ['test'],
                'synonyms' => ['test', 'test'],
                'notes' => 'test',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('favorites', ['user_id' => $user->id, 'word' => 'test']);
    }

    public function testUserCanViewFavorites(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $favorite = Favorite::factory()->create([
            'user_id' => $user->id,
        ]);

        $login = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $login->assertStatus(200);
        $token = $login->json('token');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/favorites');

        $response->assertStatus(200)
            ->assertJson(['data' => [['id' => $favorite->id]]]);
    }

    public function testUserCanUpdateFavorite(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $favorite = $user->favorites()->create([
            'word' => 'distinctio',
            'phonetics' => ['foo'],
            'definitions' => ['bar'],
            'part_of_speech' => ['noun'],
            'example_usage' => ['baz'],
            'synonyms' => ['syn'],
            'notes' => 'note',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/favorites/' . $favorite->id, [
                'notes' => 'updated_note',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('favorites', ['id' => $favorite->id, 'notes' => 'updated_note']);
    }

    public function testUserCanDeleteFavorite(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $favorite = $user->favorites()->create([
            'word' => 'todelete',
            'phonetics' => ['foo'],
            'definitions' => ['bar'],
            'part_of_speech' => ['noun'],
            'example_usage' => ['baz'],
            'synonyms' => ['syn'],
            'notes' => 'note',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/favorites/' . $favorite->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('favorites', ['id' => $favorite->id]);
    }
}
