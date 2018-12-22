<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcTryoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_tryout', function (Blueprint $table) {

            $table->increments('tryout_id');
            $table->integer('team_id')->unsigned();
            $table->integer('submitted_by_id');
            $table->string('url_key')->unique();
            $table->string('tryout_name', 100)->default(' ');
            $table->string('contact_name')->default(' ');
            $table->string('address_1')->default(' ');
            $table->string('address_2')->default(' ');
            $table->string('location')->default(' ');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('state_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->integer('zip');
            $table->string('phone_number')->default(' ');
            $table->string('email',100)->default(' ');
            $table->string('longitude')->default(' ');
            $table->string('latitude')->default(' ');
            $table->string('attachment_name_1')->default('');
            $table->string('attachment_path_1')->default('');
            $table->string('attachment_name_2')->default('');
            $table->string('attachment_path_2')->default('');
            $table->string('age_group_id')->default(' ');
            $table->text('information');
            $table->timestamps();

            $table->foreign('state_id')->references('state_id')->on('sbc_state')->onDelete('cascade');
            $table->foreign('city_id')->references('city_id')->on('sbc_city')->onDelete('cascade');
            $table->foreign('team_id')->references('team_id')->on('sbc_teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sbc_tryout');
    }
}
