<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   		$this->call([
		    AdministratorSeeder::class,
		    SecurityModuleTableSeeder::class,
		    StateSeeder::class,
		    FocusSeeder::class,
		    AgeGroupSeeder::class,
		    PositionSeeder::class,
		    ServicesSeeder::class,
		    ExperienceSeeder::class,
		    DescriptionTableSeeder::class,
		    BatsOrThrowsSeeder::class,
		    TypeofcamporclinicSeeder::class,
			UserToSecurityTableSeeder::class
	    //  EmailTemplateTableSeeder::class,
		
		]);
	}
}
