<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if the login route with GET is unaccessible
     */
    public function test_user_cannot_access_login_get_endpoint(): void
    {
        $response = $this->get('api/login');

        $response->assertStatus(401);
    }

    /**
     * Test if a user can login with POST
     */
    public function test_user_can_login(): void
    {
        $password ='123456';
        $user = User::factory()->count(1)->create(['password' =>  Hash::make($password)]);
        $user = $user->pop();

        $response = $this->post('api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $res_array = (array)json_decode($response->content());

        $this->assertAuthenticatedAs($user);
        $response->assertStatus(200);
    }

    /**
     * Test if an authenticated user can logout with POST
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->count(1)->make();
        $this->actingAs($user->pop());

        $response = $this->post('api/logout');

        $response->assertStatus(200);
    }

    /**
     * Test if a NON authenticated user can NOT logout and gets redirected
     */
    public function test_user_not_logged_in_cannot_logout(): void
    {
        $response = $this->post('api/logout');
        $response->assertStatus(302);
    }
}
