<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sbc_gallery_images', function (Blueprint $table) {
			$table->increments('gallery_image_id');
			$table->string('title', 100)->default(' ');
			$table->integer('sort')->default(0);
			$table->string('image_alt_text')->default(' ');
			$table->integer('image_id')->unsigned();
			$table->integer('gallery_id')->unsigned();
		    $table->timestamps();

            
            $table->foreign('gallery_id')->references('gallery_id')->on('sbc_gallery')->onDelete('cascade');
        });
    }
	/**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
        Schema::dropIfExists('sbc_gallery_images');
     }
}
