<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcAgeGroupEntryFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_age_group_entry_fee', function (Blueprint $table) {
            $table->increments('age_group_entry_fee_id');
            $table->integer('age_group_id')->unsigned();
            $table->integer('tournament_id')->unsigned();
            $table->double('entry_fee',8,2)->default('0.00');
            $table->timestamps();

            $table->foreign('age_group_id')->references('age_group_id')->on('sbc_age_group')->onDelete('cascade');
            $table->foreign('tournament_id')->references('tournament_id')->on('sbc_tournament')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sbc_age_group_entry_fee');
    }
}
