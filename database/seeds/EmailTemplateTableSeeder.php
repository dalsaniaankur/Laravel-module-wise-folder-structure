<?php

use Illuminate\Database\Seeder;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $items = [
            ['entity_type' => 'Player','subject' => 'Player', 'template_content' => '', ],
            ['entity_type' => 'Parent', 'subject' => 'Player', 'template_content' => '', ],
            ['entity_type' => 'Coach', 'subject' => 'Player', 'template_content' => '', ],
            ['entity_type' => 'Other', 'subject' => 'Player', 'template_content' => '', ],
            ['entity_type' => 'Instructor', 'subject' => 'Player', 'template_content' => '', ],
            ['entity_type' => 'Academy', 'subject' => 'Player', 'template_content' => '', ],
            ['entity_type' => 'Team', 'subject' => 'Player', 'template_content' => '', ],
            ['entity_type' => 'Organizations', 'subject' => 'Player', 'template_content' => '', ],
            ['entity_type' => 'CoachesNeeded', 'subject' => 'Player', 'template_content' => '', ],
            ['entity_type' => 'PlayersLookingForTeam','subject' => 'Player', 'template_content' => '', ],
		    
        ];
        foreach ($items as $item) {
            \App\State\State::create($item);
        }
    }
}




