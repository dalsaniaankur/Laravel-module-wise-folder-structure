<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampOrClinicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sbc_camp_or_clinic', function (Blueprint $table) {
			$table->increments('camp_clinic_id');
			$table->integer('submitted_by_id');
			$table->integer('type')->default(1);
			$table->integer('showcase_organization_id')->unsigned();
			$table->string('name', 100)->default(' ');
			$table->string('url_key')->unique();
			$table->date('date')->nullable();
			$table->string('address_1')->default(' ');
			$table->string('address_2')->default(' ');
			$table->integer('state_id')->unsigned();
			$table->integer('city_id')->unsigned();
			$table->integer('zip');
			$table->string('phone_number')->default(' ');
			$table->string('email',100)->default(' ');
			$table->text('description');
			$table->string('age_group_id')->default(' ');
			$table->string('service_id')->default(' ');
			$table->string('type_of_camp_or_clinic_id')->default(' ');
			$table->string('boys_or_girls')->default(' ');
			$table->string('attachment_name_1')->default(' ');
			$table->string('attachment_path_1')->default(' ');
			$table->string('attachment_name_2')->default(' ');
			$table->string('attachment_path_2')->default(' ');
		    $table->string('website_url')->default(' ');
			$table->text('cost_or_notes');
			$table->text('other_information');
		    $table->integer('is_active')->default(1);
            $table->integer('is_send_email_to_user')->default(0);
		
            $table->timestamps();

            $table->foreign('state_id')->references('state_id')->on('sbc_state')->onDelete('cascade');
            $table->foreign('city_id')->references('city_id')->on('sbc_city')->onDelete('cascade');
            $table->foreign('showcase_organization_id')->references('showcase_organization_id')->on('sbc_showcase_organization')->onDelete('cascade');
        });
    }
	/**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
        Schema::dropIfExists('sbc_camp_or_clinic');
     }
}
