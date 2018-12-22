<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_teams', function (Blueprint $table) {
            $table->increments('team_id');
			$table->integer('submitted_by_id');
			$table->string('name', 100)->default(' ');
            $table->string('url_key')->unique();
            $table->string('contact_name', 100)->default(' ');
			$table->string('address_1')->default(' ');
            $table->string('address_2')->default(' ');
            $table->integer('state_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->integer('zip');
            $table->string('phone_number')->default(' ');
			$table->string('email',100)->default(' ');
    		$table->integer('agree_to_recevie_email_updates')->default(0);
            $table->string('website_url')->default(' ');
            $table->string('blog_url')->default(' ');
            $table->string('age_group_id');
            $table->text('about');
            $table->text('achievements');
            $table->text('general_information');
            $table->text('notable_alumni');
			$table->string('facebook_url')->default(' ');
            $table->string('twitter_url')->default(' ');
            $table->string('youtube_video_id_1')->default(' ');
            $table->string('youtube_video_id_2')->default(' ');
            $table->integer('is_show_advertise')->default(0);
            $table->string('longitude')->default(' ');
            $table->string('latitude')->default(' ');
            $table->integer('image_id');
            $table->integer('is_active')->default(1);
            $table->integer('is_send_email_to_user')->default(0);
            $table->string('approval_status')->default(' ');
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
        Schema::dropIfExists('sbc_teams');
     }
}
