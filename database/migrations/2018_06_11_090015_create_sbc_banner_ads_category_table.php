<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcBannerAdsCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_banner_ads_category', function (Blueprint $table) {
            $table->increments('banner_ads_category_id');
            $table->string('reservation_category_for')->default(' ');
            $table->string('name')->default(' ');
            $table->integer('sort');
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
        Schema::dropIfExists('sbc_banner_ads_category');
    }
}
