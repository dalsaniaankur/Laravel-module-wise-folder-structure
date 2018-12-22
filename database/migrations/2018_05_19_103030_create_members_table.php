<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_members', function (Blueprint $table) {
            $table->increments('member_id');
            $table->string('email',100)->unique();
            $table->string('password')->default(' ');
            $table->string('first_name',100)->default(' ');
            $table->string('last_name',100)->default(' ');
            $table->string('url_key')->unique();
            $table->string('description_id')->default(' ');
            $table->string('affiliation')->default(' ');
            $table->text('address_1');
            $table->text('address_2');
            $table->integer('state_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->string('zip',5)->default(' ');
            $table->string('phone')->default(' ');
            $table->string('remember_token')->default(' ');
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
        Schema::dropIfExists('sbc_members');
    }
}
