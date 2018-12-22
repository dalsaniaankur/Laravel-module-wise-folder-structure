<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcBannerTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_banner_tracking', function (Blueprint $table) {
            $table->increments('banner_tracking_id');
            $table->integer('banner_ads_id');
            $table->string('banner_ads_title')->default(' ');
            $table->string('page_url')->default(' ');
            $table->string('ip_address')->default(' ');
            $table->string('banner_redirect_link')->default(' ');
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
        Schema::dropIfExists('sbc_banner_tracking');
    }
}
