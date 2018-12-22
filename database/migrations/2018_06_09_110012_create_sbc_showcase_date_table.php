<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcShowcaseDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_showcase_date', function (Blueprint $table) {

            $table->increments('showcase_date_id');
            $table->integer('showcase_or_prospect_id')->unsigned();
            $table->date('date')->nullable();
            $table->integer('sort_order');
            $table->timestamps();

            $table->foreign('showcase_or_prospect_id')->references('showcase_or_prospect_id')->on('sbc_showcase_or_prospect')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sbc_showcase_date');
    }
}
