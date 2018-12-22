<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_email_template', function (Blueprint $table) {
            $table->increments('email_template_id');
            $table->string('subject')->default(' ');
            $table->string('entity')->default(' ');
            $table->text('template_content');
            $table->dateTime('last_email_sent_date')->nullable();
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
        Schema::dropIfExists('sbc_email_template');
    }
}
