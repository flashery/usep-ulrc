<?php

use Illuminate\Database\Seeder;
use App\Bib;

class BibTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(Bib::class, 1000)->create();
    }
}
