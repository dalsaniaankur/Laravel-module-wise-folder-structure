<?php
namespace App\Classes\Helpers\Tournament;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $image_module_id = 14;
	protected $reservation_category_for = 'tournaments';
	
	protected $mileRadius  = array( '30' => '30',
								    '60' => '60',
								    '120' => '120',
								    '240' => '240',
								    '480' => '480');  

	protected $competitionLevellist  = array( '1' => 'Open',
                                              '2' => 'Major - AAA',
                                              '3' => 'AA-A',
											  '4' => 'B',
                                              '0' => 'All');

    protected $hotel_required_dropdown  = array( -1 => '-- All --',
                                                1 => 'Yes',
                                                0 => 'No');

    protected $short_by  = array( 'academy_name' => 'Academy Name' );

    protected $short_order  = array( 'ASC' => 'ASC',
                                     'DESC' => 'DESC' );

	protected $fieldSurface = array( '0' => '--Select--','Grass' => 'Grass','Artificial' => 'Artificial' );
    protected $guaranteed_games_list = array( 0 => 'None',
                                              1 => '1',
                                              2 => '2',
                                              3 => '3',
                                              4 => '4',
                                              5 => '5',
                                              6 => '6',
                                              7 => '7',
                                              8 => '8',
                                              9 => '9',
                                              10 => '10');
	
	protected $fieldSurfaceForSearch = array( '0' => '--All--','Artificial' => 'Artificial','Grass' => 'Grass' );

	protected $page_title = 'Tournaments';
	protected $meta_title = 'Tournaments';
	protected $meta_keywords = 'Tournaments';
	protected $meta_description = 'This page created for Tournaments';
	protected $meta_image = '';

    protected $csv_export_folder_path = '/exports/tournament/';
    protected $csv_import_folder_path = '/imports/tournament/';
    protected $csv_import_results_folder_path = '/import_results/tournament/';

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
	public function getReservationCategoryFor()
	{
		return $this->reservation_category_for;
	}

    public function getCompetitionLevellist(){
	    $list = $this->competitionLevellist; 
		return $list;
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
	public function getCompetitionLevellistById($competitionLevelIdArray){
		
		return array_filter( $this->competitionLevellist, 
			function ($key) use ($competitionLevelIdArray) { 
				return in_array($key, $competitionLevelIdArray); 
			},ARRAY_FILTER_USE_KEY);
		
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

    public function getCompetitionLevelIdByName($name){
        $competitionLevelList = $this->competitionLevellist;
        $competitionLevelId=0;
        foreach ($competitionLevelList as $key => $value){
            if($value == $name){
                $competitionLevelId = $key;
            }
        }
        return $competitionLevelId;
    }
    public function getGuaranteedGamesList(){
        return $this->guaranteed_games_list;
    }

    public function getHotelRequiredDropDown(){
        return $this->hotel_required_dropdown;
    }

    public function getShortByDropDown(){
	    return $this->short_by;
    }

    public function getShortOrderDropDown(){
        return $this->short_order;
    }
}


