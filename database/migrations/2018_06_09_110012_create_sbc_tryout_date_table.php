<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcTryoutDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_tryout_date', function (Blueprint $table) {

            $table->increments('tryout_date_id');
            $table->integer('tryout_id')->unsigned();
            $table->date('date')->nullable();
            $table->integer('sort_order');
            $table->timestamps();

            $table->foreign('tryout_id')->references('tryout_id')->on('sbc_tryout')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sbc_tryout_date');
    }
}
