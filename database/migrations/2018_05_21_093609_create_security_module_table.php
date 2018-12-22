<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecurityModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_security_module', function (Blueprint $table){
       		$table->increments('link_id');
			$table->string('label',100)->default(' ');
			$table->string('code',100)->default(' ');
			$table->string('link',100)->default(' ');
			$table->integer('level')->default('1');
            $table->integer('is_active')->default(1);
			$table->integer('sort_order')->default('1');
			$table->integer('parent_link_id')->default('0');
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
		Schema::dropIfExists('sbc_security_module');
	}
}
