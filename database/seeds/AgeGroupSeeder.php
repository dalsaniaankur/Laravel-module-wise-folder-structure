<?php
use Illuminate\Database\Seeder;

class AgeGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
 	{
		$items = [	//Instructors
					['module_id'=>'2','name' => 'Youth','short_order' => '1'],
					['module_id'=>'2','name' => 'High School','short_order' => '2'],
					['module_id'=>'2','name' => 'College','short_order' => '3'],
					['module_id'=>'2','name' => 'Professional ','short_order' => '4'],

                    //CoachesNeeded
					['module_id'=>'4','name' => '8U','short_order' => '1'],
					['module_id'=>'4','name' => '9U','short_order' => '2'],
					['module_id'=>'4','name' => '10U','short_order' => '3'],
					['module_id'=>'4','name' => '11U','short_order' => '4'],
					['module_id'=>'4','name' => '12U','short_order' => '5'],
					['module_id'=>'4','name' => '13U','short_order' => '6'],
					['module_id'=>'4','name' => '14U','short_order' => '7'],
					['module_id'=>'4','name' => '15U','short_order' => '8'],
					['module_id'=>'4','name' => '16U','short_order' => '9'],
					['module_id'=>'4','name' => '17U','short_order' => '10'],
					['module_id'=>'4','name' => '18U','short_order' => '11'],

                    //Lookup For Player Experience
					['module_id'=>'5','name' => '8U','short_order' => '1'],
					['module_id'=>'5','name' => '9U','short_order' => '2'],
					['module_id'=>'5','name' => '10U','short_order' => '3'],
					['module_id'=>'5','name' => '11U','short_order' => '4'],
					['module_id'=>'5','name' => '12U','short_order' => '5'],
					['module_id'=>'5','name' => '13U','short_order' => '6'],
					['module_id'=>'5','name' => '14U','short_order' => '7'],
					['module_id'=>'5','name' => '15U','short_order' => '8'],
					['module_id'=>'5','name' => '16U','short_order' => '9'],
					['module_id'=>'5','name' => '17U','short_order' => '10'],
					['module_id'=>'5','name' => '18U','short_order' => '11'],

                    // Team
					['module_id'=>'6','name' => '7U','short_order' => '1'],
					['module_id'=>'6','name' => '8U','short_order' => '2'],
					['module_id'=>'6','name' => '9U','short_order' => '3'],
					['module_id'=>'6','name' => '10U','short_order' => '4'],
					['module_id'=>'6','name' => '11U','short_order' => '5'],
					['module_id'=>'6','name' => '12U','short_order' => '6'],
					['module_id'=>'6','name' => '13U','short_order' => '7'],
					['module_id'=>'6','name' => '14U','short_order' => '8'],
					['module_id'=>'6','name' => '15U','short_order' => '9'],
					['module_id'=>'6','name' => '16U','short_order' => '10'],
					['module_id'=>'6','name' => '17U','short_order' => '11'],
					['module_id'=>'6','name' => 'College','short_order' => '12'],

                    //Camp/Clinic
					['module_id'=>'12','name' => '6U','short_order' => '1'],
					['module_id'=>'12','name' => '7U','short_order' => '2'],
					['module_id'=>'12','name' => '8U','short_order' => '3'],
					['module_id'=>'12','name' => '9U','short_order' => '4'],
					['module_id'=>'12','name' => '10U','short_order' => '5'],
					['module_id'=>'12','name' => '11U','short_order' => '6'],
					['module_id'=>'12','name' => '12U','short_order' => '7'],
					['module_id'=>'12','name' => '13U','short_order' => '8'],
					['module_id'=>'12','name' => '14U','short_order' => '9'],
					['module_id'=>'12','name' => '15U','short_order' => '10'],
					['module_id'=>'12','name' => '16U','short_order' => '11'],
					['module_id'=>'12','name' => '17U','short_order' => '12'],
					['module_id'=>'12','name' => '18U','short_order' => '13'],

                    //Showcase/Prospect
					['module_id'=>'13','name' => '2018','short_order' => '1'],
					['module_id'=>'13','name' => '2019','short_order' => '2'],
					['module_id'=>'13','name' => '2020','short_order' => '3'],
					['module_id'=>'13','name' => '2021','short_order' => '4'],
					['module_id'=>'13','name' => '2022','short_order' => '5'],
					['module_id'=>'13','name' => '2023','short_order' => '6'],
					['module_id'=>'13','name' => 'Junior College','short_order' => '7'],

					//Tournament
					['module_id'=>'14','name' => '7U','short_order' => '1'],
					['module_id'=>'14','name' => '8U','short_order' => '2'],
					['module_id'=>'14','name' => '9U','short_order' => '3'],
					['module_id'=>'14','name' => '10U','short_order' => '4'],
					['module_id'=>'14','name' => '11U','short_order' => '5'],
					['module_id'=>'14','name' => '12U','short_order' => '6'],
					['module_id'=>'14','name' => '13U','short_order' => '7'],
					['module_id'=>'14','name' => '14U','short_order' => '8'],
					['module_id'=>'14','name' => '15U','short_order' => '9'],
					['module_id'=>'14','name' => '16U','short_order' => '10'],
					['module_id'=>'14','name' => '17U','short_order' => '11'],
					['module_id'=>'14','name' => 'College','short_order' => '12'],

					//Tryout
					['module_id'=>'16','name' => '7U','short_order' => '1'],
					['module_id'=>'16','name' => '8U','short_order' => '2'],
					['module_id'=>'16','name' => '9U','short_order' => '3'],
					['module_id'=>'16','name' => '10U','short_order' => '4'],
					['module_id'=>'16','name' => '11U','short_order' => '5'],
					['module_id'=>'16','name' => '12U','short_order' => '6'],
					['module_id'=>'16','name' => '13U','short_order' => '7'],
					['module_id'=>'16','name' => '14U','short_order' => '8'],
					['module_id'=>'16','name' => '15U','short_order' => '9'],
					['module_id'=>'16','name' => '16U','short_order' => '10'],
					['module_id'=>'16','name' => '17U','short_order' => '11'],
					['module_id'=>'16','name' => '18U','short_order' => '12'],
					['module_id'=>'16','name' => 'College','short_order' => '12'],

				];
	    foreach ($items as $item) {
            \App\Classes\Models\AgeGroup\AgeGroup::create($item);
        }
    }
}
