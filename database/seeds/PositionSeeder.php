<?php
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
 	{
		$items = [	
					//CoachesNeeded
					['module_id'=>4, 'name' =>'Head','short_order' => '1'],
					['module_id'=>4, 'name' =>'Assistant','short_order' => '2'],
					['module_id'=>4, 'name' =>'Pithcing','short_order' => '3'],
					['module_id'=>4, 'name' =>'Other','short_order' => '4'],
				
				 	//Lookup For Player Experience
					['module_id'=>5, 'name' =>'First Base','short_order' => '1'],
					['module_id'=>5, 'name' =>'Second Base','short_order' => '2'],
					['module_id'=>5, 'name' =>'Third Base','short_order' => '3'],
					['module_id'=>5, 'name' =>'Short Stop','short_order' => '4'],
					['module_id'=>5, 'name' =>'Pitcher','short_order' => '5'],
					['module_id'=>5, 'name' =>'Catcher','short_order' => '6'],
					['module_id'=>5, 'name' =>'Left Field','short_order' => '7'],
					['module_id'=>5, 'name' =>'Center Field','short_order' => '8'],
					['module_id'=>5, 'name' =>'Right Field','short_order' => '9'],
					['module_id'=>5, 'name' =>'Mid Infield','short_order' => '10'],
					['module_id'=>5, 'name' =>'Corner Infield','short_order' => '11'],
					
					//Showcase/Prospect             
					['module_id'=>13, 'name' =>'Catcher','short_order' => '1'],
					['module_id'=>13, 'name' =>'Pitcher','short_order' => '2'],
					['module_id'=>13, 'name' =>'Infield','short_order' => '3'],
					['module_id'=>13, 'name' =>'Outfield','short_order' => '4'],

					//Trave Team/Tryout/Age Group             
                    ['module_id'=>15, 'name' =>'Pitcher','short_order' => '1'],
                    ['module_id'=>15, 'name' =>'Catcher','short_order' => '2'],
                    ['module_id'=>15, 'name' =>'1st Base','short_order' => '3'],
                    ['module_id'=>15, 'name' =>'2st Base','short_order' => '4'],
                    ['module_id'=>15, 'name' =>'3st Base','short_order' => '5'],
                    ['module_id'=>15, 'name' =>'Short Stop','short_order' => '6'],
                    ['module_id'=>15, 'name' =>'Left Field','short_order' => '7'],
                    ['module_id'=>15, 'name' =>'Center Field','short_order' => '8'],
                    ['module_id'=>15, 'name' =>'Right Field','short_order' => '9'],

				];
     
        foreach ($items as $item) {
            \App\Classes\Models\Position\Position::create($item);
        }
    }
}
