<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcEnquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_enquiry', function (Blueprint $table) {
            $table->increments('enquiry_id');
            $table->string('entity_type')->default(' ');
            $table->integer('entity_id');
            $table->string('entity_name')->default(' ');
            $table->string('contact_email')->default(' ');
            $table->string('sender_name')->default(' ');
            $table->string('sender_email')->default(' ');
            $table->string('sender_message')->default(' ');
            $table->string('referal_url')->default(' ');
            $table->string('ip_address')->default(' ');
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
        Schema::dropIfExists('sbc_enquiry');
    }
}
