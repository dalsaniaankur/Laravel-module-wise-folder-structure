<?php
namespace App\Classes\Helpers\AgeGroupPosition;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper{

	protected $administratorConfigurationObj;
	protected $image_module_id = 15;

	public function __construct(){
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }

	public function getConfigPerPageRecord(){

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ?  (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

	public function getModuleId()
	{
		return $this->image_module_id;
	}
	
}