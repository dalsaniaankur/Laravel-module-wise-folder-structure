<?php
namespace App\Classes\Helpers\PageBuilder;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper
{
	protected $filter_table = array( 'academy'=>'Academy',
                                    'blog' =>'Blog',
                                    'showcase'=>'Showcase',
                                    'team'=>'Team',
                                    'tournament' =>'Tournament',
                                    'tryout'=>'tryout');
	
	protected $display_banner_ads =array('1'=>'Yes','0'=>'No');

	protected $administratorConfigurationObj;

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }
	
	public function getConfigPerPageRecord()
	{
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_per_page_record');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_per_page_record.value')) :  $dbConfig->value;
	}

	public function getFilterTable()
	{
		return $this->filter_table;
	}

	public function getDisplayBannerAds()
	{
		return $this->display_banner_ads;
	}
	public function getTournamentTableKey(){

		return 'tournament';
	}

	public function getTeamTableKey(){
		
		return 'team';
	}

	public function getShowcaseTableKey(){
		
		return 'showcase';
	}
	public function getAcademyTableKey(){
		
		return 'academy';
	}
	public function getTryoutTableKey(){
		
		return 'tryout';
	}
}