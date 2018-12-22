<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowcaseOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sbc_showcase_organization', function (Blueprint $table) {
			$table->increments('showcase_organization_id');
			$table->integer('submitted_by_id');
			$table->string('name', 100)->default(' ');
            $table->string('url_key')->unique();
			$table->string('contact_name', 100)->default(' ');
			$table->string('address_1')->default(' ');
			$table->string('address_2')->default(' ');
			$table->string('location')->default(' ');
            $table->integer('state_id')->unsigned();
			$table->integer('city_id')->unsigned();
			$table->integer('zip');
			$table->string('phone_number')->default(' ');
			$table->string('email',100)->default(' ');
			$table->string('description')->default(' ');
    		$table->string('attachment_name_1')->default(' ');
			$table->string('attachment_path_1')->default(' ');
			$table->string('attachment_name_2')->default(' ');
			$table->string('attachment_path_2')->default(' ');
			$table->string('attachment_name_3')->default(' ');
			$table->string('attachment_path_3')->default(' ');
		    $table->string('website_url')->default(' ');
			$table->string('facebook_url')->default(' ');
            $table->string('twitter_url')->default(' ');
            $table->string('instagram_url')->default(' ');
            $table->string('youtube_video_id_1')->default(' ');
            $table->string('youtube_video_id_2')->default(' ');
            $table->integer('is_show_advertise')->default(0);
            $table->string('longitude')->default(' ');
            $table->string('latitude')->default(' ');
            $table->integer('image_id');
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('sbc_showcase_organization');
     }
}
