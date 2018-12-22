<?php
namespace App\Classes\Helpers\CampOrClinic;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $types = array('1'=>'Camp','2'=>'Clinic');
	protected $boys_or_girls = array('1'=>'Boys','2'=>'Girls');
	
	protected $default_type='1';
	protected $image_module_id = 12;
	protected $image_upload_path = '/images/showcase_organization';

	protected $administratorConfigurationObj;

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }
	
	public function getConfigPerPageRecord()
	{

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

	public function getModuleId()
	{
		return $this->image_module_id;
	}

	public function getTypes()
	{
		return $this->types;
	}

	public function getBoysOrGirls()
	{
		return $this->boys_or_girls;
	}
	
	public function getImageUploadPath()
	{
		return $this->image_upload_path;
	}
	
	public function getDefaultType()
	{
		return $this->default_type;
	}
	
}