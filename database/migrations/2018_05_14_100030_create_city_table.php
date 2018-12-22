<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_city', function (Blueprint $table) {
            $table->increments('city_id');
            $table->string('city')->default(' ');
            $table->string('state')->default(' ');
            $table->string('country')->default(' ');
            $table->string('latitude')->default(' ');
			$table->string('longitude')->default(' ');
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
        Schema::dropIfExists('sbc_city');
    }
}
