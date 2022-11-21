<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\User;

class VideoTest extends TestCase
{
    public string $token = '';

    /**
     * Test login function and retrieves valid bearer token
     *
     * @return void
     */
    private function login() {
        $user = USER::where('id', rand(1,3))->first();
        $loginData = ['email' => $user->email, 'password' => 'password'];
        $response = $this->json('POST', '/apilogin', $loginData, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $this->token = json_decode($response->getContent())->token;
    }

    /**
     * Call the videos with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_videos_call_with_valid_token(): void
    {
        $this->login();
        $response = $this->get('/videos', [], ['Authorization: Bearer ' . $this->token]);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['status', 'videos', 'message', 'count'])
            )->assertJson(fn (AssertableJson $json) =>
                $json->whereAllType([
                    'status' => 'string',
                    'message' => 'string',
                    'videos' => 'array',
                    'videos.0.id' => 'integer',
                    'videos.0.title' => 'string',
                    'videos.0.description' => 'string',
                    'videos.0.url' => 'string',
                    'videos.0.created_at' => 'string',
                    'videos.0.updated_at' => 'string',
                    'count' => 'integer',
                ])
            );
    }

    /**
     * Call the category, with a category id, with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_videos_call_with_valid_token_and_valid_category(): void
    {
        $this->login();
        $response = $this->get('/videos/1', [], ['Authorization: Bearer ' . $this->token]);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['status', 'video', 'message'])
            )->assertJson(fn (AssertableJson $json) =>
                $json->whereAllType([
                    'status' => 'string',
                    'message' => 'string',
                    'video' => 'array',
                    'video.0.id' => 'integer',
                    'video.0.title' => 'string',
                    'video.0.description' => 'string',
                    'video.0.url' => 'string',
                    'video.0.created_at' => 'string',
                    'video.0.updated_at' => 'string',
                ])
            );
    }

    /**
     * Call the video, with an invalid video id, with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_category_call_with_valid_token_and_invalid_category(): void
    {
        $this->login();
        $response = $this->get('/videos/1299', [], ['Authorization: Bearer ' . $this->token]);
        $response->assertStatus(404)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['status', 'message'])
            )->assertJson(fn (AssertableJson $json) =>
                $json->whereAllType([
                    'status' => 'string',
                    'message' => 'string',
                ])
            );
    }

    /**
     * Call the videos, with a search string, with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_videos_call_with_valid_token_and_search_string(): void
    {
        $this->login();
        $response = $this->get('/videos/?search=a', [], ['Authorization: Bearer ' . $this->token]);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['videos', 'count', 'status', 'message'])
            )->assertJson(fn (AssertableJson $json) =>
                $json->whereAllType([
                    'status' => 'string',
                    'message' => 'string',
                    'videos' => 'array',
                    'videos.0.id' => 'integer',
                    'videos.0.title' => 'string',
                    'videos.0.description' => 'string',
                    'videos.0.url' => 'string',
                    'videos.0.created_at' => 'string',
                    'videos.0.updated_at' => 'string',
                    'count' => 'integer',
                ])
            );
    }

    /**
     * Call the video, with an invalid video id, with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_videos_call_with_valid_token_and_impossible_search_string(): void
    {
        $this->login();
        $response = $this->get('/videos?search=1#wD&f', [], ['Authorization: Bearer ' . $this->token]);
        $response->assertStatus(404)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['status', 'message'])
            )->assertJson(fn (AssertableJson $json) =>
                $json->whereAllType([
                    'status' => 'string',
                    'message' => 'string',
                ])
            );
    }

}
