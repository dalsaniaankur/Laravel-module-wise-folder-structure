<?php
use Illuminate\Database\Seeder;

class BatsOrThrowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
	public function run()
 	{
		$items = [	//Lookup For Player Experience
					['module_id'=>5, 'name' =>'R/R',],
					['module_id'=>5, 'name' =>'L/L',],
					['module_id'=>5, 'name' =>'R/L',],
					['module_id'=>5, 'name' =>'L/R',],
				];
     
        foreach ($items as $item) {
             \App\Classes\Models\BatsOrThrows\BatsOrThrows::create($item);
        }
    }
}
