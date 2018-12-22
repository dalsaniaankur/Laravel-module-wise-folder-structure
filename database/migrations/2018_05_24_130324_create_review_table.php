<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_review', function (Blueprint $table) {
            $table->increments('review_id');
            $table->integer('member_id');
            $table->string('member_first_name')->default(' ');
            $table->string('member_last_name')->default(' ');
            $table->string('type')->comment('instructor or acadamic or team')->default(' ');
            $table->integer('review_for_id');
            $table->string('review_for_first_name')->default(' ');
            $table->string('review_for_last_name')->default(' ');
            $table->text('review_summary');
            $table->text('review_detail');
            $table->integer('is_active')->default(0);
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
        Schema::dropIfExists('sbc_review');
    }
}