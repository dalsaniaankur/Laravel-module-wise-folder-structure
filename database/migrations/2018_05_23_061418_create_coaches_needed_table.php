<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachesNeededTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_coaches_needed', function (Blueprint $table) {
            $table->increments('coaches_needed_id');
            $table->integer('member_id');
            $table->string('contact_first_name')->default(' ');
            $table->string('contact_last_name')->default(' ');
            $table->string('url_key')->unique();
            $table->string('phone_number')->default(' ');
            $table->string('email')->default(' ');
            $table->integer('is_subscribe_newsletter')->default(0);
            $table->integer('team_id')->unsigned();
            $table->string('position_id')->default(' ');
            $table->string('age_group_id')->default(' ');
            $table->string('experience_id')->default(' ');
            $table->text('description');
            $table->integer('image_id');
            $table->integer('is_active');
            $table->integer('is_send_email_to_user');
            $table->timestamps();

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
        Schema::dropIfExists('sbc_coaches_needed');
    }
}
