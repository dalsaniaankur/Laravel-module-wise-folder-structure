<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sbc_gallery', function (Blueprint $table) {
			$table->increments('gallery_id');
			$table->string('name', 100)->default(' ');
			$table->integer('sort')->default(0);
			$table->string('image_alt_text')->default(' ');
			$table->integer('image_id');
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
        Schema::dropIfExists('sbc_gallery');
     }
}
