<?php

namespace App\FakerProviders;

use Faker\Provider\Base;
use Faker\Factory as Faker;

class BibProvider extends Base
{
    private $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }
    
    public function title($nbWords = 3, $variableNbWords = true)
    {
        $sentence = $this->generator->sentence($nbWords, $variableNbWords);
        return substr($sentence, 0, strlen($sentence) - 1);
    }

    public function ISBN()
    {
        return $this->generator->ean13();
    }

    public function volume()
    {
        return $this->numberBetween($min = 0, $max = 1);
    }

    public function callNumber()
    {
        $faker = Faker::create();

        $dewey_decimal = (str_pad($this->randomNumber(3), 3, '0', STR_PAD_LEFT) . '.' . $this->randomNumber(2));
        $titles = strtoupper($this->randomLetter()) . $this->randomNumber(3);
        $year = $faker->year($max = 'now');

        return $dewey_decimal . " " . $titles . " " . $year;
    }

    public function titles()
    {
        return $this->numberBetween($min = 0, $max = 1);
    }

    public function dateOfPublication()
    {
        $faker = Faker::create();
        return $faker->dateTimeBetween($startDate = '2010-01-01 12:00:00', $endDate = 'now', $timezone = date_default_timezone_get());
    }
}
