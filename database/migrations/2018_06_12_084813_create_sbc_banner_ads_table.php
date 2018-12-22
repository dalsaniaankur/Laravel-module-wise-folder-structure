<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcBannerAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_banner_ads', function (Blueprint $table) {
            $table->increments('banner_ads_id');
            $table->integer('banner_ads_category_id')->unsigned();
            $table->string('title')->default(' ');
            $table->string('type')->default(' ');
            $table->string('position')->default(' ');
            $table->integer('sort');
            $table->string('alt_image_text')->default(' ');
            $table->string('forward_url')->default(' ');
            $table->integer('image_id');
            $table->timestamps();

            $table->foreign('banner_ads_category_id')->references('banner_ads_category_id')->on('sbc_banner_ads_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sbc_banner_ads');
    }
}
