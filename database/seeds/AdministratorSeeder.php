<?php

use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
		    ['user_id'=> 1, 'first_name' => 'Billy','last_name'=>'Becher','profile_picture'=>'images/administrator/billy.jpg','email' => 'billy@bigleaguecreative.com', 'password' => '$2y$10$Uu9fX5nIc/HA5H8nlx3VBO4WV67QmBd4.Ud8gn9I/kXAweWkhiylu', 'remember_token' => '',],
        ];
        foreach ($items as $item) {
            \App\Classes\Models\Administrator\Administrator::create($item);
        }
    }
}
