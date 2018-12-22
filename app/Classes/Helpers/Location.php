<?php
namespace App\Classes\Helpers;

use App\Classes\Models\City\City;
use App\Classes\Models\State\State;

class Location{

	protected $IP_API_URL = 'http://pro.ip-api.com/json';
	protected $IP_API_KEY = 'frSdyaY8jBJLu66';
	
	public function getIpApiUrl()
	{
		return $this->IP_API_URL;
	}
	public function getIpApiKey()
	{
		return $this->IP_API_KEY;
	}
	
	public function getConfigDefaultCity()
	{
		$default_city=\Config::get('user-configuration.default_city_for_search.value');
		return $default_city;
	}

	public function getConfigDefaultState()
	{
		$default_state=\Config::get('user-configuration.default_state_for_search.value');
		return $default_state;
	}

	public function setStateId($state_id)
	{
		setcookie('state_id',$state_id, time() + (3600 * 24), "/"); 
		$_COOKIE['state_id'] = $state_id;
	}
	
	public function setCityId($city_id)
	{
		setcookie('city_id',$city_id, time() + (3600 * 24), "/"); 
		$_COOKIE['city_id'] = $city_id;
	}
	
	public function getCityNameByIP()
	{
	    $ip = $_SERVER['REMOTE_ADDR'];
    	//$ip = '172.245.253.30';
		try{
			$url = $this->getIpApiUrl().'/'.(string)$ip.'?key='.$this->getIpApiKey();
			$response = file_get_contents($url);
			$responseArr = (array)json_decode($response);
			if($responseArr['status']=='success'){
				return $responseArr;
			}
		}
		catch(Exception $e){
		}
		return '';
	}	
	
	public function getLatitudeLongitude($city)
	{
		$cityObj = new City();
		return $cityObj->getCityLongitudeLatitudeByName($city);
	}
	
	public function getCookieLocation()
	{
		return (!empty($_COOKIE)) ? $_COOKIE : array();
	}
	
	public function setLongAndLatitude()
	{
		$location = $this->getCityNameByIP();
		$locationInfo = array();
		
		if(isset($location['lat'])&&isset($location['lon']))
		{
			$locationInfo = array('city'=>$location['city'],
								  'state'=>$location['region'], //regionName
								  'latitude'=>$location['lat'],
								  'longitude'=>$location['lon']);
		}
		
		else
		{
			$city = $this->getConfigDefaultCity();
			$latitudeLongitude = $this->getLatitudeLongitude($city);
			$locationInfo = array('city'=> $city,
								  'state'=> $this->getConfigDefaultState(),
								  'latitude'=>$latitudeLongitude->latitude,
								  'longitude'=>$latitudeLongitude->longitude);
		}
		$this->saveLocation($locationInfo);
	}
	
	public function saveLocation($locationInfo)
	{
		$cityObj = new City();
		$stateObj = new State();
		
		$city_id =0;
		$state_id=0;
		
		if(isset($locationInfo['city'])&& $locationInfo['city']!=''){
			$cityResult = $cityObj->getCityIdWithStateByName($locationInfo['city'],$locationInfo['state']);
			$city_id = !empty($cityResult->city_id) ? $cityResult->city_id : 0;
		}
		if($city_id>0){
			setcookie('city',$locationInfo['city'], time() + (3600 * 24), "/"); 
			setcookie('latitude',$locationInfo['latitude'], time() + (3600 * 24),"/"); 
			setcookie('longitude',$locationInfo['longitude'], time() + (3600 * 24), "/"); 
			setcookie('city_id',$city_id, time() + (3600 * 24), "/"); 
		  
			$_COOKIE['city'] = $locationInfo['city'];
			$_COOKIE['latitude'] = $locationInfo['latitude'];
			$_COOKIE['longitude'] = $locationInfo['longitude'];
			$_COOKIE['city_id'] = $city_id;
			
		}else{
			setcookie('city',"", time() + (3600 * 24), "/"); 
			setcookie('latitude',"", time() + (3600 * 24),"/"); 
			setcookie('longitude',"", time() + (3600 * 24), "/"); 
			setcookie('city_id',0, time() + (3600 * 24), "/"); 
			
			$_COOKIE['city'] = "";
			$_COOKIE['latitude'] = "";
			$_COOKIE['longitude'] = "";
			$_COOKIE['city_id'] = $city_id;
		}
	
		if(isset($locationInfo['state'])&& $locationInfo['state']!=''){
			$state_id = $stateObj->getStateByCode($locationInfo['state']);
			$state_id = !empty($state_id->state_id) ? $state_id->state_id : 0;
		}
		if($state_id>0){
			setcookie('state',$locationInfo['state'], time() + (3600 * 24), "/"); 
	 		setcookie('state_id',$state_id, time() + (3600 * 24), "/"); 
			$_COOKIE['state'] = $locationInfo['state'];
			$_COOKIE['state_id'] = $state_id;
		}else{
			setcookie('state',"", time() + (3600 * 24), "/"); 
	 		setcookie('state_id',0, time() + (3600 * 24), "/"); 
			$_COOKIE['state'] = "";
			$_COOKIE['state_id']=0;
		}
	}	
}