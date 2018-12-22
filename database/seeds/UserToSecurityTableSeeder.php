<?php

use Illuminate\Database\Seeder;

class UserToSecurityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i=1;
		$user_id=1;
		for($i=1;$i<=101;$i++){
		   $item=array();
		   $item['user_id']=1;
		   $item['link_id']=$i;
		  
           \App\Classes\Models\User\UserToSecurityModule::create($item);
        }
    }
}
