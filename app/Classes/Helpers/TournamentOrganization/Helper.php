<?php
namespace App\Classes\Helpers\TournamentOrganization;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $image_module_id = 10;
	protected $show_advertise  = array('1' =>'Yes','0'=>'No'); 
	protected $mileRadius  = array( 30 => 30,
								    60 => 60,
								    120 => 120,
								    240 => 240,
								    480 => 480); 

	protected $administratorConfigurationObj;

    protected $approval_status_list  = array( 'approved' => 'Approved', 'pending' => 'Pending', 'rejected' => 'Rejected');

    protected $csv_export_folder_path = '/exports/tournament_organization/';
    protected $csv_import_folder_path = '/imports/tournament_organization/';
    protected $csv_import_results_folder_path = '/import_results/tournament_organization/';

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

	public function getShowAdvertise()
	{
		return $this->show_advertise;
	}

	public function getMileRadius()
	{
		return $this->mileRadius;
	}
    public function getApprovalStatusList(){

        return $this->approval_status_list;
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


