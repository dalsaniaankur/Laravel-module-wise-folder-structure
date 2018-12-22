<?php
namespace App\Classes\Helpers\ShowcaseOrProspect;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $image_module_id = 13;
	protected $types =array('1'=>'Showcase','2'=>'Prospect Camp');
	protected $open_or_invites=array('1'=>'Open','2'=>'Invite');	
	protected $default_type = 1;
	protected $image_upload_path = '/images/showcase_organization';
	protected $reservation_category_for = 'showcases';

	protected $mileRadius  = array( '30' => '30',
								    '60' => '60',
								    '120' => '120',
								    '240' => '240',
								    '480' => '480');

	protected $open_or_invites_for_front =array('0'=>'All', '1'=>'Open', '2'=>'Invite');

	protected $page_title = 'Showcases';
	protected $meta_title = 'Showcases';
	protected $meta_keywords = 'Showcases';
	protected $meta_description = 'This page created for Showcases';
	protected $meta_image = '';

    protected $approval_status_list  = array( 'approved' => 'Approved', 'pending' => 'Pending', 'rejected' => 'Rejected');

    protected $csv_export_folder_path = '/exports/showcase_or_prospect/';
    protected $csv_import_folder_path = '/imports/showcase_or_prospect/';
    protected $csv_import_results_folder_path = '/import_results/showcase_or_prospect/';


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
    public function getApprovalStatusList(){

        return $this->approval_status_list;
    }
    public function getTypes()
	{
		return $this->types;
	}
	public function getReservationCategoryFor()
	{
		return $this->reservation_category_for;
	}
	public function getOpenOrInvites()
	{
		return $this->open_or_invites;
	}

	public function getModuleId()
	{
		return $this->image_module_id;
	}

	public function getDefaultType()
	{
		return $this->default_type;
	}
	
	public function getImageUploadPath()
	{
		return $this->image_upload_path;
	}

	public function getMileRadius()
	{
		return $this->mileRadius;
	}

	public function getOpenOrInvitesForFront()
	{
		return $this->open_or_invites_for_front;
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
    public function getTypeNameById($id){
        $list = $this->types;
        return $list[$id];
    }
    public function getOpenOrInviteNameById($id){
        $list = $this->open_or_invites;
        return $list[$id];
    }

    public function getTypeIdByName($name){
        $list = array_flip($this->types);
        return isset($list[$name]) ? $list[$name] : '';
    }
    public function getOpenOrInviteIdByName($name){
        $list = array_flip($this->open_or_invites);
        return isset($list[$name]) ? $list[$name] : '';
    }

    public function checkStatusReference($statusId){
        $list = array_flip($this->approval_status_list);
        return isset($list[$statusId]) ? $list[$statusId] : '';
    }

}