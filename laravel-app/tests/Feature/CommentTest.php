<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Comment;
use App\Models\Profile;
use App\Models\User;

use Tests\TestCase;

class CommentTest extends TestCase
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
     * Test that a comment can be created on POST 'api/profiles/{id}/comments'
     */
    public function test_comment_is_created(): void
    {
        $user = $this->pretendAuth('create');

        // Create a profile to make sure to have at least 1 result
        $profile = (Profile::factory()->count(1)->for($user)->create())->pop();

        $response = $this->post('api/profiles/'.$profile->id.'/comments', [
            'content'   => 'Test Create Comment',
        ]);

        $res_array = (array)json_decode($response->content());

        // If the json has the 'result' key, check if 'content' is in the result
        if($this->assertArrayHasKey('result', $res_array)) {
            $this->assertArrayHasKey('result.content', $res_array);
            // Test if the comment has been updated
            $this->assertStringContainsString('Test Create Comment', $res_array['result']->content);
        }

        $response->assertStatus(201);
    }

    /**
     * Test that a comment can be updated on PUT 'api/comments/{id}'
     */
    public function test_comment_is_updated(): void
    {
        $user = $this->pretendAuth('create', 'admin');

        // Create a profile to make sure to have at least 1 result
        $profile = (Profile::factory()->count(1)->for($user)->create())->pop();

        // Create a comment to make sure to have at least 1
        $comment = (Comment::factory()->count(1)->for($user)->for($profile)->create())->pop();

        $response = $this->put('api/comments/'.$comment->id, [
            'content'   => 'Test Update Comment',
        ]);

        $res_array = (array)json_decode($response->content());

        // If the json has the 'result' key, check if 'content' is in the result
        if($this->assertArrayHasKey('result', $res_array)) {
            $this->assertArrayHasKey('result.content', $res_array);
            // Test if the comment has been updated
            $this->assertStringContainsString('Test Update Comment', $res_array['result']->content);
        }

        $response->assertStatus(200);
    }

    /**
     * Test that a comment can be deleted on DELETE 'api/comments/{id}'
     */
    public function test_comment_is_deleted(): void
    {
        $user = $this->pretendAuth('create', 'admin');

        // Create a profile to make sure to have at least 1 result
        $profile = (Profile::factory()->count(1)->for($user)->create())->pop();

        // Create a comment to make sure to have at least 1
        $comment = (Comment::factory()->count(1)->for($user)->for($profile)->create())->pop();

        $response = $this->delete('api/comments/'.$comment->id);

        $res_array = (array)json_decode($response->content());

        // If the json has the 'result' key, check if 'content' is in the result
        if($this->assertArrayHasKey('result', $res_array)) {
            // Test if the comment has been updated
            $this->assertStringContainsString('Comment has been deleted', $res_array['result']);
        }

        $response->assertStatus(200);
    }

}