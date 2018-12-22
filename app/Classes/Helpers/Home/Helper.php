<?php
namespace App\Classes\Helpers\Home;

use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;

class Helper{
	
	protected $reservation_category_for = 'home';

	protected $page_title = 'Softball Connected';
	protected $meta_title = 'Softball Connected';
	protected $meta_keywords = 'Softball Connected';
	protected $meta_description = 'Softball Connected';
	protected $meta_image = '';

	protected $administratorConfigurationObj;

	public function __construct(){
        
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }
	
	public function getReservationCategoryFor(){

		return $this->reservation_category_for;
	}

	public function getMileRadius(){
		
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('default_mile_radius_for_top_search_bar');
		return (empty($dbConfig)) ? (\Config::get('user-configuration.default_mile_radius_for_top_search_bar.value')) :  $dbConfig->value;
	}

	public function getFacebookUrl(){
		
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('facebook_url');
		return (empty($dbConfig)) ?  (\Config::get('user-configuration.facebook_url.value')) :  $dbConfig->value;
	}

	public function getInstagramUrl(){

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('instagram_url');
		return (empty($dbConfig)) ?  (\Config::get('user-configuration.instagram_url.value')) :  $dbConfig->value;
	}

	public function getTwitterUrl(){
		
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('twitter_url');
		return (empty($dbConfig)) ?  (\Config::get('user-configuration.twitter_url.value')) :  $dbConfig->value;
	}

	public function getInstagramAccessToken(){

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('instagram_access_token');
		return (empty($dbConfig)) ?  (\Config::get('user-configuration.instagram_access_token.value')) :  $dbConfig->value;
	}

	public function getInstagramFeedCount(){

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('instagram_feed_count');
		return (empty($dbConfig)) ?  (\Config::get('user-configuration.instagram_feed_count.value')) :  $dbConfig->value;
	}

	public function getBlogFeedCountForHomePage(){

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('blog_feed_count_for_home_page');
		return (empty($dbConfig)) ?  (\Config::get('user-configuration.blog_feed_count_for_home_page.value')) :  $dbConfig->value;
	}

	public function getPageTitle($title =''){

		if(!empty(trim($this->page_title))){
			return $this->page_title;
		}
		
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('page_title');
		return $dbConfig->value;
		
	}

	public function getMetaTitle(){

		if(!empty(trim($this->meta_title))){
			return $this->meta_title;
		}
		
		$dbConfig = $this->administratorConfigurationObj->getValueByKey('meta_title');
		return $dbConfig->value;
		
	}

	public function getMetaKeywords(){

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
}