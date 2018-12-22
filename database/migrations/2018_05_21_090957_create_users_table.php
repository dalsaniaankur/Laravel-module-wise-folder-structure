<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('first_name',100)->default(' ');
            $table->string('last_name',100)->default(' ');
         	$table->string('email', 100)->unique();
			$table->string('password',255);
         	$table->string('remember_token')->nullable();
		    $table->string('profile_picture',255)->nullable();
			$table->smallInteger('status')->default(1);
			$table->integer('parent_id')->default(0);
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
        Schema::dropIfExists('sbc_users');
    }
}
