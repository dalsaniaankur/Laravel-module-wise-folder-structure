<?php
namespace App\Classes\Helpers\CoachesNeeded;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $show_advertise = array('1' =>'Yes','0'=>'No'); 
	protected $image_module_id = 4;

	protected $administratorConfigurationObj;

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }
	
	public function getConfigPerPageRecord()
	{
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

	public function getShowAdvertise()
	{
		return $this->show_advertise;
	}
	
	public function getModuleId()
	{
		return $this->image_module_id;
	}
}