<?php
namespace App\Classes\Helpers\Team;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $show_advertise  = array('1' =>'Yes','0'=>'No'); 
	protected $image_module_id = 6;
	protected $age_group_module_id = 2;
	protected $mileRadius  = array( '30' => '30',
								    '60' => '60',
								    '120' => '120',
								    '240' => '240',
								    '480' => '480');

	protected $reservation_category_for = 'team';

	protected $page_title = 'Teams';
	protected $meta_title = 'Teams';
	protected $meta_keywords = 'Teams';
	protected $meta_description = 'This page created for Teams';
	protected $meta_image = '';

    protected $approval_status_list  = array( 'approved' => 'Approved', 'pending' => 'Pending', 'rejected' => 'Rejected');

    protected $csv_export_folder_path = '/exports/team/';
    protected $csv_import_folder_path = '/imports/team/';
    protected $csv_import_results_folder_path = '/import_results/team/';

    protected $administratorConfigurationObj;

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }
	
	public function getConfigPerPageRecord(){
		
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

	public function getReservationCategoryFor()
	{
		return $this->reservation_category_for;
	}

    public function getApprovalStatusList(){

        return $this->approval_status_list;
    }
    public function getModuleId()
	{
		return $this->image_module_id;
	}
	
	public function getShowAdvertise()
	{
		return $this->show_advertise;
	}
	public function getMileRadius()
	{
		return $this->mileRadius;
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
    public function getDefaultApprovalStatus(){

        $dbConfig = $this->administratorConfigurationObj->getValueByKey('default_approval_status');
        $status = (empty($dbConfig)) ?  (\Config::get('user-configuration.default_approval_status.value')) :  $dbConfig->value;

        if($status == '1' || $status == 1){
            return 'approved';
        }

        if($status == '2' || $status == 2){
            return 'pending';
        }
        return 'pending';
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

    public function checkStatusReference($statusId){
        $list = array_flip($this->approval_status_list);
        return isset($list[$statusId]) ? $list[$statusId] : '';
    }
}


