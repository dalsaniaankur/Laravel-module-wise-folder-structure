<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sbc_team_group', function (Blueprint $table) {
			$table->increments('team_group_id');
			$table->string('name', 100)->default(' ');
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
        Schema::dropIfExists('sbc_team_group');
     }
}
