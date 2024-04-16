<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_response_status_code_is_200(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['view-user']
        );
        $token = Str::random(10);

        $response =  $this->withHeaders(['Authorization' => "Bearer $token"])->get('/api/user');
     
        $response->assertStatus(200);
        
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = '123456'),
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertValid(['name', 'email']);        
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('123456'),
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401);
        $response->assertUnauthorized();
    }

    public function test_user_cannot_login_with_incorrect_email()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = '123456'),
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => 'invalid-email',
            'password' => $password,
        ]);
        $response->assertInvalid(['email']);
        $response->assertStatus(401);
        $response->assertUnauthorized();
    }
    
}
