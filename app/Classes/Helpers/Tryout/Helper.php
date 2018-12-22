<?php
namespace App\Classes\Helpers\Tryout;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $image_module_id = 16;
	protected $image_upload_path = '/images/tryout';		
	protected $mileRadius  = array( '30' => '30',
								    '60' => '60',
								    '120' => '120',
								    '240' => '240',
								    '480' => '480');  

	protected $competitionLevellist  = array( '1' => 'Major - AAA',
												'2' => 'AA-A',
												'3' => 'B',
								    			);
	
	protected $reservation_category_for = 'tryout';
	protected $fieldSurface = array( '0' => '--Select--','Grass' => 'Grass','Artificial' => 'Artificial' );
	
	protected $fieldSurfaceForSearch = array( '0' => '--All--','Grass' => 'Grass','Artificial' => 'Artificial' );

	protected $page_title = 'Tryouts';
	protected $meta_title = 'Tryouts';
	protected $meta_keywords = 'Tryouts';
	protected $meta_description = 'This page created for Tryouts';
	protected $meta_image = '';

    protected $csv_export_folder_path = '/exports/tryout/';
    protected $csv_import_folder_path = '/imports/tryout/';
    protected $csv_import_results_folder_path = '/import_results/tryout/';

	protected $administratorConfigurationObj;

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }
	
	public function getConfigPerPageRecord()
	{
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

	public function getConfigRecordForTopsearch(){

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_total_record_for_top_search');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_total_record_for_top_search.value')) :  $dbConfig->value;
	}
	public function getDefaultMileRadius(){
		
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_mile_radius');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_mile_radius.value')) :  $dbConfig->value;
	}
	
	public function getModuleId()
	{
		return $this->image_module_id;
	}

	public function getCompetitionLevellist()
	{
		return $this->competitionLevellist;
	}

	public function getReservationCategoryFor()
	{
		return $this->reservation_category_for;
	}
	
	public function getMileRadius()
	{
		return $this->mileRadius;
	}

	public function getFieldSurface()
	{
		return $this->fieldSurface;
	}
	public function getFieldSurfaceForSearch()
	{
		return $this->fieldSurfaceForSearch;
	}
	public function getImageUploadPath()
	{
		return $this->image_upload_path;
	}

	public function getPageTitle($title =''){

		if(!empty(trim($title))){
			return $title .' '. $this->page_title . ' | '. trans('quickadmin.front_title');
		}

		if(!empty(trim($this->page_title))){
			return $this->page_title . ' | '. trans('quickadmin.front_title');
		}
		
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('page_title');
		return $dbConfig->value;
		
	}

	public function getMetaTitle($title =''){

		if(!empty(trim($title))){
			return $title;
		}

		if(!empty(trim($this->meta_title))){
			return $this->meta_title . ' | '. trans('quickadmin.front_title');
		}
		
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('meta_title');
		return $dbConfig->value;
		
	}

	public function getMetaKeywords($metaKeyword = ''){

		if(!empty(trim($metaKeyword))){
			return $metaKeyword;
		}

		if(!empty(trim($this->meta_keywords))){
			return $this->meta_keywords;
		}
		
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('meta_keyword');
		return $dbConfig->value;
		
	}

	public function getMetaDescription(){

		if(!empty(trim($this->meta_description))){
			return $this->meta_description;
		}
		
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('meta_description');
		return $dbConfig->value;
		
	}

	public function getMetaetaImage(){
		
		return $this->meta_image;
	}

    public function getCsvExportFolderPath(){
        return $this->csv_export_folder_path;
    }
    public function getCsvImportFolderPath(){
        return $this->csv_import_folder_path;
    }
    public function getCsvImportResultsFolderPath(){
        return $this->csv_import_results_folder_path;
    }
}


