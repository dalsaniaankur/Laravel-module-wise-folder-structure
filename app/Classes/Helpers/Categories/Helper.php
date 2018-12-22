<?php
namespace App\Classes\Helpers\Categories;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper{
	
	protected $image_upload_path = '/images/categories';
	protected $meta_title = 'Categories Page';
	protected $meta_keywords = 'Categories';
	protected $meta_description = 'This page created for blog categories';

	protected $administratorConfigurationObj;

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }

	public function getConfigPerPageRecord(){

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

	public function getImageUploadPath()
	{
		return $this->image_upload_path;
	}

	public function getPageTitle($title){
		
		if(!empty(trim($title))){
			return $title;
		}

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('page_title');
		return $dbConfig->value;
	}

	public function getDefaultMetaTitle(){
		
		if(!empty(trim($this->meta_title))){
			return $this->meta_title;
		}

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('meta_title');
		return $dbConfig->value;
	}

	public function getDefaultMetaKeywords(){
		
		if(!empty(trim($this->meta_keywords))){
			return $this->meta_keywords;
		}	

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('meta_keyword');
		return $dbConfig->value;
	}

	public function getDefaultMetaDescription(){
		
		if(!empty(trim($this->meta_description))){
			return $this->meta_description;
		}

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('meta_description');
		return $dbConfig->value;
	}
}