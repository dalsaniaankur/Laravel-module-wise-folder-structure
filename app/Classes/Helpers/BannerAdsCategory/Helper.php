<?php
namespace App\Classes\Helpers\BannerAdsCategory;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper{

	protected $reservation_category_for  = array( 
													'none'        => 'None',
                                                    'academies'   => 'Academies',
													'home'        => 'Home',
                                                    'showcases'   => 'Showcases',
                                                    'team'        => 'Team',
													'tournaments' => 'Tournaments',
													'tryout'      => 'Tryout',
												);
	
	protected $administratorConfigurationObj;

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }

	public function getConfigPerPageRecord()
	{
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

	public function getReservationCategoryForDropdown()
	{
		return $this->reservation_category_for;
	}
}