<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Profile;

use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Pretend user is authenticated
     * @param string $method parameter to know if we create or just simulate a user
     * @param ?string $type parameter to know which type of use we want
     */
    private function pretendAuth(string $method = 'make', ?string $type = null): User
    {
        $user = match($type) {
            'standard', null    => User::factory()->count(1)->$method(),
            'admin'             => User::factory()->count(1)->admin()->$method(),
        };
        $user = $user->pop();
        $this->actingAs($user);

        return $user;
    }

    /**
     * Test if a non authenticated user can acess the public route 'api/profiles'
     */
    public function test_profiles_endpoint_is_public(): void
    {
        $response = $this->get('api/profiles');

        $response->assertStatus(200);
    }

    /**
     * Test if a non authenticated user can acess the public route 'api/profiles'
     * without the hidden fields
     */
    public function test_profiles_endpoint_is_missing_fields(): void
    {
        $response = $this->get('api/profiles');

        $res_array = (array)json_decode($response->content());

        // If the json has the 'result' key, check if the hidden 'status' key is missing (field only for authenticated users)
        if($this->assertArrayHasKey('result', $res_array)) {
            !$this->assertArrayHasKey('result.status', $res_array);
        }

        $response->assertStatus(200);
    }

    /**
     * Test if an authenticated user can access the public route 'api/profiles'
     * with the hidden fields
     */
    public function test_profiles_endpoint_has_hidden_fields(): void
    {
        $user = $this->pretendAuth('create');

        // Create a profile to make sure to have at least 1 result
        Profile::factory()->count(1)->for($user)->create();

        $response = $this->get('api/profiles');

        $res_array = (array)json_decode($response->content());

        // If the json has the 'result' key, check if the hidden 'status' key is there
        if($this->assertArrayHasKey('result', $res_array)) {
            $this->assertArrayHasKey('result.status', $res_array);
        }

        $response->assertStatus(200);
    }

    /**
     * Test that a STANDARD user cannot create a profile on POST 'api/profiles'
     */
    public function test_standard_user_cannot_create_profile(): void
    {
        $user = $this->pretendAuth('create');
        // Create a profile to make sure to have at least 1 result
        $profile = (Profile::factory()->count(1)->make())->pop();

        $response = $this->post('api/profiles', [
            'last_name'     => $profile->last_name,
            'first_name'    => $profile->first_name,
            'status'        => $profile->status->value,
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test that an ADMIN user can create a profile on POST 'api/profiles'
     */
    public function test_admin_user_can_create_profile(): void
    {
        $user = $this->pretendAuth('create', 'admin');
        // Create a profile to make sure to have at least 1 result
        $profile = (Profile::factory()->count(1)->make())->pop();

        $response = $this->post('api/profiles', [
            'last_name'     => $profile->last_name,
            'first_name'    => $profile->first_name,
            'status'        => $profile->status->value,
        ]);

        $res_array = (array)json_decode($response->content());

        // If the json has the 'result' key, check if 'last_name' is in the result
        if($this->assertArrayHasKey('result', $res_array)) {
            $this->assertArrayHasKey('result.last_name', $res_array);
        }

        $response->assertStatus(201);
    }

    /**
     * Test that a profile can be updated on PUT 'api/profiles/{id}'
     */
    public function test_profile_is_updated(): void
    {
        $user = $this->pretendAuth('create', 'admin');

        // Create a profile to make sure to have at least 1 result
        $profile = (Profile::factory()->count(1)->for($user)->create())->pop();

        $response = $this->put('api/profiles/'.$profile->id, [
            'last_name'  => 'Mercury',
            'first_name' => 'Freddie',
        ]);

        $res_array = (array)json_decode($response->content());

        // If the json has the 'result' key, check if 'content' is in the result
        if($this->assertArrayHasKey('result', $res_array)) {
            $this->assertArrayHasKey('result.content', $res_array);
            // Test if the profile has been updated
            $this->assertStringContainsString('Mercury', $res_array['result']->last_name);
            $this->assertStringContainsString('Freddie', $res_array['result']->first_name);
        }

        $response->assertStatus(200);
    }

    /**
     * Test that a profile can be deleted on DELETE 'api/profiles/{id}'
     */
    public function test_profile_is_deleted(): void
    {
        $user = $this->pretendAuth('create', 'admin');

        // Create a profile to make sure to have at least 1 result
        $profile = (Profile::factory()->count(1)->for($user)->create())->pop();

        $response = $this->delete('api/profiles/'.$profile->id);

        $res_array = (array)json_decode($response->content());

        // If the json has the 'result' key, check if 'content' is in the result
        if($this->assertArrayHasKey('result', $res_array)) {
            // Test if the comment has been updated
            $this->assertStringContainsString('Profile has been deleted', $res_array['result']);
        }

        $response->assertStatus(200);
    }

}