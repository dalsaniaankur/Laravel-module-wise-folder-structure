<?php
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
 	{
		$items = [	
					//Academies
					['module_id'=>3, 'name' =>'Pitching',],
					['module_id'=>3, 'name' =>'Hitting',],
					['module_id'=>3, 'name' =>'Catching',],
					['module_id'=>3, 'name' =>'Batting Cages',],
					['module_id'=>3, 'name' =>'Team Practices',],
					['module_id'=>3, 'name' =>'Field Rentals',],
					['module_id'=>3, 'name' =>'Pitching Tunnels',],
					['module_id'=>3, 'name' =>'Fitness Training',],
					['module_id'=>3, 'name' =>'Parties',],
					
					//Camp/Clinic
					['module_id'=>12, 'name' =>'Fielding',],
					['module_id'=>12, 'name' =>'Catching',],
					['module_id'=>12, 'name' =>'Hitting',],
					['module_id'=>12, 'name' =>'Pitching',],
					['module_id'=>12, 'name' =>'Skills',],
					['module_id'=>12, 'name' =>'Other',],
				];
     
        foreach ($items as $item) {
             \App\Classes\Models\Services\Services::create($item);
        }
    }
}
