<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatsOrThrowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_bats_or_throw', function (Blueprint $table) {
            $table->increments('bats_or_throw_id');
            $table->string('name')->default(' ');
            $table->string('status',1)->default('1');
            $table->integer('module_id')->comment('1 =>lookup For Player Experience');
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
        Schema::dropIfExists('sbc_lu_bats_or_throw');
    }
}
