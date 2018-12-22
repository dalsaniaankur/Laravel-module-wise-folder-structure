<?php
namespace App\Classes\Helpers\Instructor;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $team_coach=array('1' =>'Yes','0'=>'No');
	protected $show_advertise=array('1' =>'Yes','0'=>'No'); 
	protected $image_module_id = 2;
    protected $csv_export_folder_path = '/exports/instructor/';
    protected $csv_import_folder_path = '/imports/instructor/';
    protected $csv_import_results_folder_path = '/import_results/instructor/';

	protected $administratorConfigurationObj;

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }
	
	public function getConfigPerPageRecord()
	{
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

	public function getShowAdvertise()
	{
		return $this->show_advertise;
	}

	public function getTeamCoach()
	{
		return $this->team_coach;
	}

	public function getModuleId()
	{
		return $this->image_module_id;
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