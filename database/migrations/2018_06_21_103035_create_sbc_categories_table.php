<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_categories', function (Blueprint $table) {
            $table->increments('category_id');
            $table->string('title')->default(' ');
            $table->integer('parent_category_id');
            $table->integer('banner_ads_category_id');
            $table->string('meta_title')->default(' ');
            $table->string('meta_keyword')->default(' ');
            $table->text('meta_description');
            $table->text('short_description');
            $table->text('description');
            $table->string('image_name')->default(' ');
            $table->string('image_path')->default(' ');
            $table->string('url_key')->unique();
            $table->integer('sort');
            $table->integer('status')->default('1');
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
        Schema::dropIfExists('sbc_categories');
    }
}
