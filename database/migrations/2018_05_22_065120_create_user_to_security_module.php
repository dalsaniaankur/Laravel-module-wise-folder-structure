<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserToSecurityModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sbc_user_to_security_module', function (Blueprint $table) {
			$table->increments('user_to_security_module_id');
			$table->integer('link_id');
			$table->integer('user_id');
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
        Schema::dropIfExists('sbc_user_to_security_module');
     }
}
