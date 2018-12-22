<?php
namespace App\Classes\Helpers\EmailTemplate;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $entityType = array(
                                'academy'=>'Academy',
                                'coach'=>'Coach',
                                'coaches_needed'=>'CoachesNeeded',
                                'instructor'=>'Instructor',
                                'organizations'=>'Organizations',
                                'other'=>'Other',
                                'parent'=>'Parent',
                                'player' =>'Player',
                                'players_looking_for_team'=>'PlayersLookingForTeam',
								'team'=>'Team');

	protected $administratorConfigurationObj;

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }

	public function getConfigPerPageRecord(){

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

	public function getEntityTypeDropDown(){

		return $this->entityType;
	}
}