<?php

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
        $this->call(DcsTableSeeder::class);
        $this->call(BibTableSeeder::class);
        $this->call(BibMarcTagTableSeeder::class);
        $this->call(BibSubjectTableSeeder::class);
    }
}
