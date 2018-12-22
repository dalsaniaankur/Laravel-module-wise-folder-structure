<?php
namespace App\Classes\Helpers\ShowcaseOrganization;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $image_module_id = 8;
    protected $csv_export_folder_path = '/exports/showcase_organization/';
    protected $csv_import_folder_path = '/imports/showcase_organization/';
    protected $csv_import_results_folder_path = '/import_results/showcase_organization/';

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


