<?php
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
 	{
		$items = [	['state_id'=>1, 'code' =>'AL', 'name'=>'Alabama',],
					['state_id'=>2, 'code' =>'AK', 'name'=> 'Alaska',],
					['state_id'=>3, 'code' =>'AS', 'name'=>'American Samoa',],
					['state_id'=>4, 'code' =>'AZ', 'name'=>'Arizona',],
					['state_id'=>5, 'code' =>'AR', 'name'=>'Arkansas',],
					['state_id'=>6, 'code' =>'AF', 'name'=>'Armed Forces Africa',],
					['state_id'=>7, 'code' =>'AA', 'name'=>'Armed Forces Americas',],
					['state_id'=>8, 'code' =>'AC', 'name'=>'Armed Forces Canada',],
					['state_id'=>9, 'code' =>'AE', 'name'=>'Armed Forces Europe',],
					['state_id'=>10, 'code' =>'AM', 'name'=>'Armed Forces Middle East',],
					['state_id'=>11, 'code' =>'AP', 'name'=>'Armed Forces Pacific',],
					['state_id'=>12, 'code' =>'CA', 'name'=>'California',],
					['state_id'=>13, 'code' =>'CO', 'name'=>'Colorado',],
					['state_id'=>14, 'code' =>'CT', 'name'=>'Connecticut',],
					['state_id'=>15, 'code' =>'DE', 'name'=>'Delaware',],
					['state_id'=>16, 'code' =>'DC', 'name'=>'District of Columbia',],
					['state_id'=>17, 'code' => 'FM', 'name'=>'Federated States Of Micronesia',],
					['state_id'=>18, 'code' => 'FL', 'name'=>'Florida',],
					['state_id'=>19, 'code' => 'GA', 'name'=>'Georgia',],
					['state_id'=>20, 'code' => 'GU', 'name'=>'Guam',],
					['state_id'=>21, 'code' => 'HI', 'name'=>'Hawaii',],
					['state_id'=>22, 'code' => 'ID', 'name'=>'Idaho',],
					['state_id'=>23, 'code' => 'IL', 'name'=>'Illinois',],
					['state_id'=>24, 'code' => 'IN', 'name'=>'Indiana',],
					['state_id'=>25, 'code' => 'IA', 'name'=>'Iowa',],
					['state_id'=>26, 'code' => 'KS', 'name'=>'Kansas',],
					['state_id'=>27, 'code' => 'KY', 'name'=>'Kentucky',],
					['state_id'=>28, 'code' => 'LA', 'name'=>'Louisiana',],
					['state_id'=>29, 'code' => 'ME', 'name'=>'Maine',],
					['state_id'=>30, 'code' => 'MH', 'name'=>'Marshall Islands',],
					['state_id'=>31, 'code' => 'MD', 'name'=>'Maryland',],
					['state_id'=>32, 'code' => 'MA', 'name'=>'Massachusetts',],
					['state_id'=>33, 'code' => 'MI', 'name'=>'Michigan',],
					['state_id'=>34, 'code' => 'MN', 'name'=>'Minnesota',],
					['state_id'=>35, 'code' => 'MS', 'name'=>'Mississippi',],
					['state_id'=>36, 'code' => 'MO','name'=>'Missouri',],
					['state_id'=>37, 'code' => 'MT','name'=> 'Montana',],
					['state_id'=>38, 'code' => 'NE','name'=> 'Nebraska',],
					['state_id'=>39, 'code' => 'NV','name'=> 'Nevada',],
					['state_id'=>40, 'code' => 'NH','name'=> 'New Hampshire',],
					['state_id'=>41, 'code' => 'NJ','name'=> 'New Jersey',],
					['state_id'=>42, 'code' => 'NM','name'=> 'New Mexico',],
					['state_id'=>43, 'code' => 'NY','name'=> 'New York',],
					['state_id'=>44, 'code' => 'NC','name'=> 'North Carolina',],
					['state_id'=>45, 'code' => 'ND','name'=> 'North Dakota',],
					['state_id'=>46, 'code' => 'MP','name'=> 'Northern Mariana Islands',],
					['state_id'=>47, 'code' => 'OH','name'=> 'Ohio',],
					['state_id'=>48, 'code' => 'OK', 'name'=>'Oklahoma',],
					['state_id'=>49, 'code' => 'OR', 'name'=>'Oregon',],
					['state_id'=>50, 'code' => 'PW','name'=> 'Palau',],
					['state_id'=>51, 'code' => 'PA','name'=> 'Pennsylvania',],
					['state_id'=>52, 'code' => 'PR','name'=> 'Puerto Rico',],
					['state_id'=>53, 'code' => 'RI', 'name'=>'Rhode Island',],
					['state_id'=>54, 'code' => 'SC', 'name'=>'South Carolina',],
					['state_id'=>55, 'code' => 'SD', 'name'=>'South Dakota',],
					['state_id'=>56, 'code' => 'TN', 'name'=>'Tennessee',],
					['state_id'=>57, 'code' => 'TX', 'name'=>'Texas',],
					['state_id'=>58, 'code' =>'UT', 'name'=>'Utah',],
					['state_id'=>59, 'code' => 'VT', 'name'=>'Vermont',],
					['state_id'=>60, 'code' => 'VI', 'name'=>'Virgin Islands',],
					['state_id'=>61, 'code' => 'VA', 'name'=>'Virginia',],
					['state_id'=>62, 'code' => 'WA', 'name'=>'Washington',],
					['state_id'=>63, 'code' => 'WV', 'name'=>'West Virginia',],
					['state_id'=>64, 'code' => 'WI', 'name'=>'Wisconsin',],
					['state_id'=>65, 'code' => 'WY', 'name'=>'Wyoming',],
				];
     
        foreach ($items as $item) {
            \App\Classes\Models\State\State::create($item);
        }
    }
}
