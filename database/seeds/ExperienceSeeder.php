<?php
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
 	{
		$items = [	//CoachesNeeded
					['module_id'=>4, 'name' =>'1',],
					['module_id'=>4, 'name' =>'2',],
					['module_id'=>4, 'name' =>'3',],
					['module_id'=>4, 'name' =>'4',],
					['module_id'=>4, 'name' =>'5+',],
					//Lookup For Player Experience
					['module_id'=>5, 'name' =>'1',],
					['module_id'=>5, 'name' =>'2',],
					['module_id'=>5, 'name' =>'3',],
					['module_id'=>5, 'name' =>'4',],
					['module_id'=>5, 'name' =>'5+',],
				];
     
        foreach ($items as $item) {
             \App\Classes\Models\Experience\Experience::create($item);
        }
    }
}
