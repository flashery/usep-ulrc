<?php

use Illuminate\Database\Seeder;

class MarcTagsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('marc_tags')->delete();
        
        \DB::table('marc_tags')->insert(array (
            0 => 
            array (
                'id' => 2,
                'marc_tag' => 112,
                'non_marc_tag' => 'ISBN',
                'show_as_default' => 1,
                'sequence_number' => 0,
                'created_at' => '2019-07-03 12:58:12',
                'updated_at' => '2019-07-08 14:51:13',
            ),
            1 => 
            array (
                'id' => 4,
                'marc_tag' => 114,
                'non_marc_tag' => 'Call Number',
                'show_as_default' => 1,
                'sequence_number' => NULL,
                'created_at' => '2019-07-07 15:15:39',
                'updated_at' => '2019-07-22 14:05:24',
            ),
            2 => 
            array (
                'id' => 5,
                'marc_tag' => 111,
                'non_marc_tag' => 'Title',
                'show_as_default' => 1,
                'sequence_number' => NULL,
                'created_at' => '2019-07-14 03:27:25',
                'updated_at' => '2019-07-14 03:27:25',
            ),
            3 => 
            array (
                'id' => 6,
                'marc_tag' => 113,
                'non_marc_tag' => 'Volume',
                'show_as_default' => 1,
                'sequence_number' => NULL,
                'created_at' => '2019-07-27 08:34:54',
                'updated_at' => '2019-07-27 08:48:20',
            ),
        ));
        
        
    }
}