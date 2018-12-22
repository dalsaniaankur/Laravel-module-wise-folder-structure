<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcAgegroupPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_agegroup_position', function (Blueprint $table) {
            $table->increments('agegroup_position_id');
            $table->integer('tryout_id')->unsigned();
            $table->integer('age_group_id')->unsigned();
            $table->integer('position_id')->unsigned();
            $table->timestamps();

            $table->foreign('age_group_id')->references('age_group_id')->on('sbc_age_group')->onDelete('cascade');
            $table->foreign('tryout_id')->references('tryout_id')->on('sbc_tryout')->onDelete('cascade');
            $table->foreign('position_id')->references('position_id')->on('sbc_position')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sbc_agegroup_position');
    }
}
