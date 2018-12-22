<?php
namespace App\Classes\Helpers\BannerAds;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $image_module_id = 17;
	protected $image_upload_path = 'images/banner_ads';
	
	protected $type  = array( 'image' => 'Image', /*'Flash' => 'Flash', 'Custom' => 'Custom'*/); 
	protected $position  = array( 'top' => 'Top', 'side' => 'Side'); 

	protected $administratorConfigurationObj;

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }

	public function getConfigPerPageRecord()
	{
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

	public function getTypeDropdown()
	{
		return $this->type;
	}

	public function getPositionDropdown()
	{
		return $this->position;
	}
	public function getModuleId()
	{
		return $this->image_module_id;
	}
	public function getImageUploadPath()
	{
		return $this->image_upload_path;
	}
}