<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamToGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('sbc_team_to_group', function (Blueprint $table) {
			$table->increments('team_to_group_id');
			$table->integer('team_id')->unsigned();
			$table->integer('team_group_id')->unsigned();
			$table->integer('sort')->default(0);
		    $table->timestamps();

            $table->foreign('team_group_id')->references('team_group_id')->on('sbc_team_group')->onDelete('cascade');
            $table->foreign('team_id')->references('team_id')->on('sbc_teams')->onDelete('cascade');
            
        });
    }
	/**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
        Schema::dropIfExists('sbc_team_to_group');
     }
}
