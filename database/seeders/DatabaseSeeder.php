<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(TimTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TutorialSeeder::class);
        $this->call(DocumentationSeeder::class);
        $this->call(DivisionSeeder::class);
        $this->call(ActivitySeeder::class);
        $this->call(ActivityDocumentSeeder::class);
        $this->call(CriterionSeeder::class);
    }
}
