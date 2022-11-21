<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\User;

class CategoryTest extends TestCase
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
     * Test login with invalid credentials and waits for a 401 response: unauthorized
     *
     * @return void
     */
    public function test_if_the_api_returns_401_result_if_invalid_credentials_are_provided(): void
    {
        $loginData = ['email' => 'xptoi@info.pt', 'password' => 'passwoasdrd'];
        $response = $this->json('POST', '/apilogin', $loginData, ['Accept' => 'application/json']);
        $response->assertStatus(401);
    }

    /**
     * Test login with invalid credentials and waits for a 401 response: unauthorized
     *
     * @return void
     */
    public function test_if_the_api_returns_401_result_if_no_credentials_are_provided(): void
    {
        $response = $this->post('/apilogin');
        $response->assertStatus(401);
    }

    /**
     * Call the category with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_category_call_with_valid_token(): void
    {
        $this->login();
        $response = $this->get('/categories', [], ['Authorization: Bearer ' . $this->token]);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['status', 'categories', 'message', 'count'])
            )->assertJson(fn (AssertableJson $json) =>
                $json->whereAllType([
                    'status' => 'string',
                    'message' => 'string',
                    'categories' => 'array',
                    'categories.0.id' => 'integer',
                    'categories.0.title' => 'string',
                    'categories.0.color' => 'string',
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
    public function test_the_api_category_call_with_valid_token_and_valid_category(): void
    {
        $this->login();
        $response = $this->get('/categories/1', [], ['Authorization: Bearer ' . $this->token]);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['status', 'category', 'message'])
            )->assertJson(fn (AssertableJson $json) =>
                $json->whereAllType([
                    'status' => 'string',
                    'message' => 'string',
                    'category' => 'array',
                    'category.0.id' => 'integer',
                    'category.0.title' => 'string',
                    'category.0.color' => 'string',
                    'category.0.created_at' => 'string',
                    'category.0.updated_at' => 'string'
                ])
            );
    }

    /**
     * Call the category, with an invalid category id, with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_category_call_with_valid_token_and_invalid_category(): void
    {
        $this->login();
        $response = $this->get('/categories/99', [], ['Authorization: Bearer ' . $this->token]);
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
     * Call the videos inside a category, with a category id, with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_category_with_videos_call_with_valid_token_and_valid_category(): void
    {
        $this->login();
        $response = $this->get('/categories/1/videos', [], ['Authorization: Bearer ' . $this->token]);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['status', 'videos', 'message', 'count'])
            )->assertJson(fn (AssertableJson $json) =>
                $json->whereAllType([
                    'status' => 'string',
                    'message' => 'string',
                    'videos' => 'array',
                    'count' => 'integer',
                    'videos.0.id' => 'integer',
                    'videos.0.category' => 'array',
                    'videos.0.category.id' => 'integer',
                    'videos.0.category.title' => 'string',
                    'videos.0.category.color' => 'string',
                    'videos.0.category.created_at' => 'string',
                    'videos.0.category.updated_at' => 'string',
                    'videos.0.title' => 'string',
                    'videos.0.description' => 'string',
                    'videos.0.url' => 'string',
                    'videos.0.created_at' => 'string',
                    'videos.0.updated_at' => 'string'
                ])
            );
    }

    /**
     * Call the videos inside a category, with an invalid category id, with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_category_with_videos_call_with_valid_token_and_invalid_category(): void
    {
        $this->login();
        $response = $this->get('/categories/99/videos', [], ['Authorization: Bearer ' . $this->token]);
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

    // public function test_if_the_api_returns_401_result_if_no_token_is_provided()
    // {
    //     $this->withoutExceptionHandling().
    //     $response = $this->post('/categories');
    //     $response->assertStatus(401);
    // }


}
