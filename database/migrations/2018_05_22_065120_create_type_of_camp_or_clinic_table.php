<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeOfCampOrClinicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	
	public function up()
    {
		Schema::create('sbc_type_of_camp_or_clinic', function (Blueprint $table) {
			$table->increments('type_of_camp_or_clinic_id');
			$table->string('name', 100)->default(' ');
			$table->integer('status')->default(1);
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
        Schema::dropIfExists('sbc_type_of_camp_or_clinic');
     }
}
