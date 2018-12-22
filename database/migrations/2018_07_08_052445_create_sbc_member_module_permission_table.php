<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbcMemberModulePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sbc_member_module_permission', function (Blueprint $table) {
            $table->increments('member_module_permission_id');
            $table->integer('member_id')->unsigned();
            $table->string('module_name')->default(' ');
            $table->timestamps();

            $table->foreign('member_id')->references('member_id')->on('sbc_members')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sbc_member_module_permission');
    }
}
