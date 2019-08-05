<?php

use Illuminate\Database\Seeder;
use App\BibSubject;
use App\Subject;
use App\Bib;
use Faker\Factory as Faker;

class BibSubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $subject_ids = Subject::all()->pluck('id');
        $bib_ids = Bib::all()->pluck('id');
        // dd($subject_ids);
        // $user = factory(BibSubject::class, 1000)->create();
        foreach ($bib_ids as $bib_id) {
            $data = [
                'subject_id' => $faker->randomElement($subject_ids),
                'bib_id' => $bib_id,
            ];
            BibSubject::create($data);
        }
    }
}
