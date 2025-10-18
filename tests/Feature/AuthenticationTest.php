<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration via API
     */
    public function test_user_can_register_via_api()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'first_name',
                        'last_name',
                        'email',
                        'phone',
                        'created_at',
                    ],
                    'token',
                    'token_type',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);
    }

    /**
     * Test user login via API
     */
    public function test_user_can_login_via_api()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user',
                    'token',
                    'token_type',
                ],
            ]);
    }

    /**
     * Test user login with invalid credentials
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test get authenticated user
     */
    public function test_authenticated_user_can_get_profile()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->getJson('/api/v1/auth/me', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                ],
            ]);
    }

    /**
     * Test user logout
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson('/api/v1/auth/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logout successful',
            ]);

        // Verify token is deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    /**
     * Test user can change password
     */
    public function test_user_can_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson('/api/v1/auth/change-password', [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Password changed successfully',
            ]);

        // Verify new password works
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    /**
     * Test user can update profile
     */
    public function test_user_can_update_profile()
    {
        $user = User::factory()->create([
            'first_name' => 'Old',
            'last_name' => 'Name',
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->putJson('/api/v1/auth/profile', [
            'first_name' => 'New',
            'last_name' => 'Name',
            'phone' => '+9876543210',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Profile updated successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'New',
            'last_name' => 'Name',
            'phone' => '+9876543210',
        ]);
    }

    /**
     * Test web login
     */
    public function test_user_can_login_via_web()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test web registration
     */
    public function test_user_can_register_via_web()
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'password' => 'password12345678',
            'password_confirmation' => 'password12345678',
            'terms' => true,
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
}
