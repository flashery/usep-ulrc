<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarcTagsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marc_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('marc_tag');
            $table->string('non_marc_tag');
            $table->boolean('show_as_default');
            $table->integer('sequence_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marc_tags');
    }
}
