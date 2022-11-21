<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\Category;
use Illuminate\Testing\Fluent\AssertableJson;

class VideoModelTest extends TestCase
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
     * Call the creation of a video with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_create_video_call_with_valid_token(): void
    {
        $this->login();
        $videoData = [
            "category" => 3,
            "title" => "Susan Bell T1",
            "description" => "Labore sit nihil qui voluptates repellat quis. Optio est sint minus voluptas sequi necessitatibus et non. Laudantium doloribus occaecati assumenda cupiditate.",
            "url" => "http://www.google.pt",
        ];
        $videos = Video::get();
        $this->assertCount(300, $videos);

        $response = $this->post('/videos', $videoData, ['Authorization: Bearer ' . $this->token]);

        $videos = Video::get();
        $this->assertCount(301, $videos);

        $response->assertStatus(201)
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
                ])
            );

        $array = json_decode($response->getContent(), true);
        $this->assertStringContainsString("Susan Bell T1", $array['video'][0]['title']);

    }

    /**
     * Call the creation of a video with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_update_video_call_with_valid_token(): void
    {
        $this->login();
        $videoData = [
            "title" => "Susan Bellagio T1",
            "description" => "Lorem ipsum",
        ];

        $response = $this->json('put', '/videos/301', $videoData, ['Authorization: Bearer ' . $this->token]);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['status', 'video', 'message'])
            )->assertJson(fn (AssertableJson $json) =>
                $json->whereAllType([
                    'status' => 'string',
                    'message' => 'string',
                    'video' => 'array',
                    'video.id' => 'integer',
                    'video.title' => 'string',
                    'video.description' => 'string',
                ])
            );

        $array = json_decode($response->getContent(), true);
        $this->assertStringContainsString("Lorem ipsum", $array['video']['description']);

    }

    /**
     * Call the creation of a category with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_delete_video_call_with_valid_token(): void
    {
        $this->login();
        $categories = Video::get();
        $this->assertCount(301, $categories);

        $video = Video::where("title", "Susan Bellagio T1")
            ->where("description", "Lorem ipsum")
            ->first();

        $response = $this->json('delete', '/videos/' . $video->id, ['Authorization: Bearer ' . $this->token]);

        $categories = Video::get();
        $this->assertCount(300, $categories);

        $response->assertStatus(200)
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
