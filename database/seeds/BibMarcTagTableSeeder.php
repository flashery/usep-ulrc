<?php

use Illuminate\Database\Seeder;
use App\Bib;
use App\MarcTag;
use Faker\Factory as Faker;
use App\FakerProviders\BibProvider;

class BibMarcTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $faker->addProvider(new BibProvider($faker));

        $bib_provider = new BibProvider($faker);

        $bibs = Bib::all();

        $marc_tags = MarcTag::all();

        foreach ($bibs as $bib) {

            $data = [];
            foreach ($marc_tags as $marc_tag) {
                if ($marc_tag->marc_tag === 112) {
                    $data[$marc_tag->id] = ['value' => $bib_provider->ISBN()];
                }
                if ($marc_tag->marc_tag === 114) {
                    $data[$marc_tag->id] = ['value' => $bib_provider->callNumber($faker)];
                }
                if ($marc_tag->marc_tag === 111) {
                    $data[$marc_tag->id] = ['value' => $bib_provider->title()];
                }
                if ($marc_tag->marc_tag === 113) {
                    $data[$marc_tag->id] = ['value' => $bib_provider->volume()];
                }

                if ($marc_tag->marc_tag === 115) {
                    $data[$marc_tag->id] = ['value' => $bib_provider->titles()];
                }

                if ($marc_tag->marc_tag === 116) {
                    $data[$marc_tag->id] = ['value' => $bib_provider->dateOfPublication()];
                }

                $bib->marc_tags()->sync($data);
            }
        }
    }
}
