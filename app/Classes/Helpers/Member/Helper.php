<?php
namespace App\Classes\Helpers\Member;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $module_list  = array( 'academies' => 'Academies',
                                    'other' => 'Other',
                                    'showcase' => 'Showcase',
                                    'teams' => 'Teams',
                                    'tournaments' => 'Tournaments',
                                    'tryouts' => 'Tryouts',
                                        );

	protected $administratorConfigurationObj;

    public $csv_export_folder_path = '/exports/member/';
    public $csv_import_folder_path = '/imports/member/';
    public $csv_import_results_folder_path = '/import_results/member/';

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }
	
	public function getConfigPerPageRecord()
	{
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ?  (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

	public function getModuleList(){
	    return $this->module_list;
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