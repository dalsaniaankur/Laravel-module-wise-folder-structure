<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgeGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_age_group', function (Blueprint $table) {
            $table->increments('age_group_id');
            $table->string('name',100)->default(' ');
            $table->integer('short_order');
            $table->string('status',1)->default('1');
            $table->integer('module_id');
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
        Schema::dropIfExists('sbc_age_group');
    }
}
