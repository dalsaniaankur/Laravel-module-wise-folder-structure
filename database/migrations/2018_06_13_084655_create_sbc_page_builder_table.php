<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcPageBuilderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_page_builder', function (Blueprint $table) {
            $table->increments('page_builder_id');
            $table->string('page_title')->default(' ');
            $table->string('url_key')->unique();
            $table->text('content');
            $table->text('short_content');
            $table->integer('display_banner_ads')->default(0);
            $table->integer('banner_ads_category_id');
            $table->integer('category_id');
            $table->string('filter_table')->default(' ');
            $table->integer('state_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->integer('redius');
            $table->string('image_name')->default(' ');
            $table->string('image_path')->default(' ');
            $table->string('meta_title')->default(' ');
            $table->string('meta_keywords')->default(' ');
            $table->text('meta_description');
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('state_id')->references('state_id')->on('sbc_state')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sbc_page_builder');
    }
}
