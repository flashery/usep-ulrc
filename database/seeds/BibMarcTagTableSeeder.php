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
                if ($marc_tag->non_marc_tag === 'ISBN') {
                    $data[$marc_tag->id] = ['value' => $bib_provider->ISBN()];
                    // print_r([$marc_tag->non_marc_tag, $data]);
                    // dd('ISBN');
                }
                if ($marc_tag->non_marc_tag === 'Call Number') {
                    $data[$marc_tag->id] = ['value' => $bib_provider->callNumber($faker)];
                    // print_r([$marc_tag->non_marc_tag, $data]);

                    // dd('Call Number');
                }
                if ($marc_tag->non_marc_tag === 'Title') {
                    $data[$marc_tag->id] = ['value' => $bib_provider->title()];
                    // print_r([$marc_tag->non_marc_tag, $data]);

                    // dd('Title');
                }
                if ($marc_tag->non_marc_tag === 'Volume') {
                    $data[$marc_tag->id] = ['value' => $bib_provider->volume(1)];

                    // print_r([$marc_tag->non_marc_tag, $data]);

                    // dd('Volume');
                }
                // print_r($data);
                $bib->marc_tags()->sync($data);
            }
        }
    }
}
