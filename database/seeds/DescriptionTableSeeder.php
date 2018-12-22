<?php

use Illuminate\Database\Seeder;

class DescriptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [  
                    ['name' => 'Player', 'status' => 1 ],
                    ['name' => 'Parent', 'status' => 1 ],
                    ['name' => 'Coach', 'status' => 1 ],
                    ['name' => 'Other', 'status' => 1 ],
                ];
                
        foreach ($items as $item) {
            \App\Classes\Models\Description\Description::create($item);
        }
    }
}
