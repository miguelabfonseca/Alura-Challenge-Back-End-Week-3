<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::Create([
            "title" => "Default category",
            "color" => "#696969",
        ]);
        
        \App\Models\User::factory(3)->create();

        \App\Models\Category::factory(5)->create();

        \App\Models\Video::factory(300)->create();

        \App\Models\User::Create([
            'name' => fake()->name(),
            'email' => 'info@info.pt',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'api_token' => hash('sha256', Str::random(60)),
            'remember_token' => Str::random(10),
        ]);
    }
}
