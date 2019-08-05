<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;
use App\BibSubject;
use App\Subject;
use App\Bib;

$factory->define(BibSubject::class, function (Faker $faker) {

    $subject_ids = Subject::all()->pluck('id');
    $bib_ids = Bib::all()->pluck('id');

    return [
        'subject_id' => $faker->randomElement($subject_ids),
        'bib_id' => $faker->unique()->randomElement($bib_ids),
    ];
});
