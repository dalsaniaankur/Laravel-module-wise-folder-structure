<?php
namespace App\Classes\Helpers\AgeGroup;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper{

	protected $administratorConfigurationObj;

    protected $status  = array( 1 => 'Active',0 => 'Inactive');

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }

	public function getConfigPerPageRecord()
	{
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ?  (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

    public function getStatusDropdown(){
	    return $this->status;
    }
	
}