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
        // If the bundled production dump is present, it is the source of truth —
        // import it instead of building data from the individual seeders.
        if (SqlDumpSeeder::dumpExists()) {
            $this->call(SqlDumpSeeder::class);

            return;
        }

        // Fallback: no dump available (e.g. a clean local setup) — build a fresh
        // dataset from the individual seeders.
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
