<?php
use Illuminate\Database\Seeder;

class TypeofcamporclinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
 	{
		$items = [	
			      //Camp/Clinic
					['name' =>'Kids',],
					['name' =>'Fitness',],
					['name' =>'Speaker',],
					['name' =>'Recruiting',],
					['name' =>'Coaching',],
					['name' =>'Other',],
				];
     
        foreach ($items as $item) {
             \App\Classes\Models\ShowcaseOrganization\Typeofcamporclinic::create($item);
        }
    }
}
