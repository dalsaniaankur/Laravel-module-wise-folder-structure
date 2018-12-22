<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_instructors', function (Blueprint $table){
            $table->increments('instructor_id');
            $table->integer('member_id');
            $table->string('title')->default(' ');
            $table->string('first_name',100)->default(' ');
            $table->string('last_name',100)->default(' ');
            $table->string('url_key')->unique();
            $table->string('address_1')->default(' ');
            $table->string('address_2')->default(' ');
            $table->integer('state_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->string('zip')->default(' ');
            $table->string('phone_number')->default(' ');
            $table->string('email',100)->default(' ');
            $table->integer('is_subscribe_newsletter')->default(0);
            $table->string('website_url')->default(' ');
            $table->string('blog_url')->default(' ');
            $table->string('affilated_academy')->default(' ');
            $table->string('age_group_id')->default(' ');
            $table->string('focus_id')->default(' ');
            $table->integer('is_team_coach')->default(0);
            $table->text('profile_description');
            $table->text('notable_coaching_achievements');
            $table->string('facebook_url')->default(' ');
            $table->string('twitter_url')->default(' ');
            $table->string('youtube_video_id_1')->default(' ');
            $table->string('youtube_video_id_2')->default(' ');
            $table->string('article_url_1')->default(' ');
            $table->string('article_url_2')->default(' ');
            $table->integer('is_show_advertise')->default(0);
            $table->string('longitude')->default(' ');
            $table->string('latitude')->default(' ');
            $table->integer('image_id');
            $table->integer('is_active')->default(0);
            $table->integer('is_send_email_to_user')->default(0);
            $table->timestamps();

            $table->foreign('state_id')->references('state_id')->on('sbc_state')->onDelete('cascade');
            $table->foreign('city_id')->references('city_id')->on('sbc_city')->onDelete('cascade');

        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
    */
    public function down()
    {
        Schema::dropIfExists('sbc_instructors');
    }
}
