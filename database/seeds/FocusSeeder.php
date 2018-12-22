<?php
use Illuminate\Database\Seeder;

class FocusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
 	{
		$items = [	
					['focus_id'=>1,'module_id'=>'2','name' => 'Pitching'],
					['focus_id'=>2,'module_id'=>'2','name' => 'Hitting'],
					['focus_id'=>3,'module_id'=>'2','name' => 'Catching'],
					['focus_id'=>4,'module_id'=>'2','name' => 'Skills Development'],
				];
	    foreach ($items as $item) {
            \App\Classes\Models\Focus\Focus::create($item);
        }
    }
}
