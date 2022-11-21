<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Testing\Fluent\AssertableJson;

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
     * Call the creation of a category with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_create_category_call_with_valid_token(): void
    {
        $this->login();
        $categoryData = [
            "title" => "Susan Bell",
            "color" => "#696969",
        ];
        $categories = Category::get();
        $this->assertCount(6, $categories);

        $response = $this->post('/categories', $categoryData, ['Authorization: Bearer ' . $this->token]);

        $categories = Category::get();
        $this->assertCount(7, $categories);

        $response->assertStatus(201)
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
                ])
            );
        $array = json_decode($response->getContent(), true);
        $this->assertStringContainsString("Susan Bell", $array['category'][0]['title']);

    }

    /**
     * Call the creation of a category with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_update_category_call_with_valid_token(): void
    {
        $this->login();
        $categoryData = [
            "title" => "Susan Belagio",
            "color" => "#e0e0e0"
        ];

        $response = $this->json('put', '/categories/7', $categoryData, ['Authorization: Bearer ' . $this->token]);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['status', 'category', 'message'])
            )->assertJson(fn (AssertableJson $json) =>
                $json->whereAllType([
                    'status' => 'string',
                    'message' => 'string',
                    'category' => 'array',
                    'category.id' => 'integer',
                    'category.title' => 'string',
                    'category.color' => 'string',
                ])
            );
        $array = json_decode($response->getContent(), true);
        $this->assertStringContainsString("Susan Belagio", $array['category']['title']);

    }


    /**
     * Call the creation of a category with a valid token
     * - check response code
     * - check json structure
     * - check json structure data types
     *
     * @return void
     */
    public function test_the_api_delete_category_call_with_valid_token(): void
    {
        $this->login();
        $category = Category::where("title", "Susan Belagio")
            ->where("color", "#e0e0e0")
            ->first();

        $categories = Category::get();
        $this->assertCount(7, $categories);

        $response = $this->json('delete', '/categories/' . $category->id, ['Authorization: Bearer ' . $this->token]);

        $categories = Category::get();
        $this->assertCount(6, $categories);

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
