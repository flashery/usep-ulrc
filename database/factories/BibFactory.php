<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Bib;
use Faker\Generator as Faker;

$factory->define(Bib::class, function (Faker $faker) {
    return ['views'=> $faker->numberBetween($min = 0, $max = 4)];
});
