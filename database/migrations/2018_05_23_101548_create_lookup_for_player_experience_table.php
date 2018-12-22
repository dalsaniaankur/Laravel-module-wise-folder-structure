<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLookupForPlayerExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_lookup_for_player_experience', function (Blueprint $table) {
            $table->increments('lookup_for_player_experience_id');
            $table->integer('member_id');
            $table->string('player_first_name')->default(' ');
            $table->string('player_last_name')->default(' ');
            $table->string('url_key')->unique();
            $table->string('player_phone_number')->default(' ');
            $table->string('player_email')->default(' ');
            $table->string('player_zip')->default(' ');
            $table->integer('is_subscribe_newsletter')->default(0);
            $table->string('age_group_id')->default(' ');
            $table->string('position_id')->default(' ');
            $table->integer('bats_or_throw_id');
            $table->string('experience_id')->default(' ');
            $table->text('comments');
            $table->integer('image_id');
            $table->integer('is_active');
            $table->integer('is_send_email_to_user');
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
        Schema::dropIfExists('sbc_lookup_for_player_experience');
    }
}
