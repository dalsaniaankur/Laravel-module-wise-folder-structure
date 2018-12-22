<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_academies', function (Blueprint $table) {
            $table->increments('academy_id');
            $table->integer('member_id');
            $table->string('academy_name')->default('');
            $table->string('url_key')->unique();
            $table->string('address_1')->default('');
            $table->string('address_2')->default('');
            $table->string('location')->default('');
            $table->integer('city_id')->unsigned();
            $table->integer('state_id')->unsigned();
            $table->integer('zip');
            $table->string('phone_number')->default('');
            $table->string('email')->default('');
            $table->string('website_url')->default('');
            $table->integer('agree_to_recevie_email_updates');
            $table->integer('is_subscribe_newsletter')->default(0);
            $table->string('service_id')->default('');
            $table->text('about');
            $table->text('objectives');
            $table->text('programs');
            $table->text('alumni');
            $table->string('facebook_url')->default('');
            $table->string('twitter_url')->default('');
            $table->string('instagram_url')->default('');
            $table->string('youtube_video_id_1')->default('');
            $table->string('youtube_video_id_2')->default('');
            $table->string('longitude')->default('');
            $table->string('latitude')->default('');
            $table->integer('image_id');
            $table->integer('is_active')->default(1);
            $table->string('approval_status')->default('');
            $table->integer('is_send_email_to_user')->default(0);
            $table->timestamps();

            $table->foreign('city_id')->references('city_id')->on('sbc_city')->onDelete('cascade');
            $table->foreign('state_id')->references('state_id')->on('sbc_state')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sbc_academies');
    }
}
