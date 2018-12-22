<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcTournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_tournament', function (Blueprint $table) {
            $table->increments('tournament_id');
            $table->integer('tournament_organization_id')->unsigned();
            $table->string('url_key')->unique();
            $table->integer('submitted_by_id');
            $table->string('tournament_name', 100)->default(' ');
            $table->string('competition_level_id')->default(' ');
            $table->string('contact_name')->default(' ');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('address_1')->default(' ');
            $table->string('address_2')->default(' ');
            $table->string('stadium_or_field_name')->default(' ');
            $table->integer('state_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->integer('zip');
            $table->integer('guaranteed_games');
            $table->integer('hotel_required');
            $table->string('phone_number')->default(' ');
            $table->string('email',100)->default(' ');
            $table->string('longitude')->default(' ');
            $table->string('latitude')->default(' ');
            $table->string('event_website_url')->default(' ');
            $table->string('age_group_id')->default(' ');
            $table->string('field_surface')->default(' ');
            $table->text('information');
            $table->timestamps();

            $table->foreign('state_id')->references('state_id')->on('sbc_state')->onDelete('cascade');
            $table->foreign('city_id')->references('city_id')->on('sbc_city')->onDelete('cascade');
            $table->foreign('tournament_organization_id')->references('tournament_organization_id')->on('sbc_tournament_organization')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sbc_tournament');
    }
}
