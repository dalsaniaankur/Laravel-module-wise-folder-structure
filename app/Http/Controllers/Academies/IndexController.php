<?php

namespace App\Http\Controllers\Academies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Models\Academies\Academies;
use App\Classes\Helpers\Academies\Helper;
use App\Classes\Models\Services\Services;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Helpers\Location;
use App\Classes\Models\Members\Members;
use App\Classes\Models\MemberModulePermission\MemberModulePermission;
use DB;

class IndexController extends Controller{

    protected $academiesObj;
    protected $_helper;
    protected $servicesObj;
    protected $bannerAdsCategoryObj;
    protected $stateObj;
    protected $cityObj;
    protected $locationObj;
    protected $memberObj;
    protected $memberModulePermission;
    
    public function __construct(Academies $academies){

        $this->academiesObj = $academies;
        $this->_helper = new \App\Classes\Helpers\Academies\Helper();
        $this->servicesObj = new Services();
        $this->bannerAdsCategoryObj = new BannerAdsCategory();
        $this->cityObj = new City();
        $this->stateObj = new State();
        $this->locationObj = new Location();
        $this->memberObj = new Members();
        $this->memberModulePermission = new MemberModulePermission();
    }

    public function index(Request $request, $member_url_key=''){

        $data = $request->all();

        /* Member Filter */
        $member_id = 0;
        if(!empty($member_url_key)){
            $member = $this->memberObj->checkIsExistMember($member_url_key);
            if(!empty($member->member_id) && $member->member_id > 0) {
                $member_id = $member->member_id;
                $memberModuleList = $this->memberModulePermission->getMemberModuleListByMemberId($member_id);
                if(! in_array("academies", $memberModuleList)){
                    return abort(404);
                }
            }else{
                return abort(404);
            }
        }

        $state_id=0;
        $city_id=0;
        $city='';
        $state='';
        $approvalStatus ='approved';
        
        /* check Cookie */
        $locationInfo = $this->locationObj->getCookieLocation();

        /* Set Cookie*/
        if(empty($locationInfo['latitude'])){
            $this->locationObj->setLongAndLatitude();    
        }

        //state found
        if(!empty($request->get('state')) && ($request->get('state')!='')){
            $state = $request->get('state');
            $cookieData = array('city'=>"",
                          'state'=>$state,
                          'latitude'=>"",
                          'longitude'=>"");
          
            $this->locationObj->saveLocation($cookieData);
        }
        
        //city found
        if(!empty($request->get('city')) && $request->get('city') != '0' ){

            $city = $request->get('city');
            $cityResult= $this->cityObj->getCityIdWithStateByName($city, $state);
            $cookieData = array('city'=> $cityResult->city,
                          'state'=> $cityResult->state,
                          'latitude'=> $cityResult->latitude,
                          'longitude'=>$cityResult->longitude);
          
            $this->locationObj->saveLocation($cookieData);
        }
        
        //state city not found  
        if(empty($request->get('state')) && ($request->get('state')=='') && ( $request->get('city')=='' || $request->get('city')== '0' ) && !empty($data)){

            $cookieData = array('city'=>'',
                          'state'=>'',
                          'latitude'=>'',
                          'longitude'=>'');
                          
            $this->locationObj->saveLocation($cookieData);
        }
        
        /* Set Data for Search */
        $locationInfo = $this->locationObj->getCookieLocation();
        $state       = $locationInfo['state'];
        $city        = $locationInfo['city'];
        $latitude    = $locationInfo['latitude'];
        $longitude   = $locationInfo['longitude'];
        $city_id     = $locationInfo['city_id'];
        $state_id    = $locationInfo['state_id'];

        $page = !empty($request->get('page')) ? $request->get('page') : 0;
        $academy_name = !empty($request->get('academy_name')) ? $request->get('academy_name') : '';
        $service_id = !empty($request->get('service_id')) ? $request->get('service_id') : [];
        $mileRadius = !empty($request->get('mileRadius')) ? $request->get('mileRadius') : $this->_helper->getDefaultMileRadius();
        $sortedBy = !empty($request->get('sortedBy')) ? $request->get('sortedBy') : 'academy_name';
        $sortedOrder = !empty($request->get('sortedOrder')) ? $request->get('sortedOrder') : 'ASC';

        $search_city = $city_id;
        $search_state = $state_id;
        if($mileRadius > 0 && !empty($latitude) && !empty($longitude)){
            $search_city =0;
            $search_state =0;
        }
        
        $academies = $this->academiesObj->list($search='',$page, $search_state, $search_city, $mileRadius, $isFront=1 ,$is_active=1, $latitude, $longitude, $academy_name, $service_id, $sortedBy, $sortedOrder, $per_page=0, $selectColoumn=array('*'), $member_id, $approvalStatus);
        $totalRecordCount = $this->academiesObj->listTotalCount($search='',$page, $search_state, $search_city, $mileRadius, $isFront=1 ,$is_active=1, $latitude, $longitude, $academy_name, $service_id,  $sortedBy, $sortedOrder, $member_id, $approvalStatus);
        
        $_data = $this->academiesObj->convertDataToHtml($academies);
        
        /* For Pagination */
        if(!empty($city)) { $data['city'] = $city; }else{ unset($data['city']); }
        if(!empty($state)) { $data['state'] = $state; }else{ unset($data['state']); }

        if(isset($data['g-recaptcha-response'])){unset($data['g-recaptcha-response']);}

        $queryString = $this->academiesObj->getQueryString($data);
        $basePath=\Request::url().$queryString;
        
        $paging = $this->academiesObj->preparePagination($totalRecordCount,$basePath);

        $stateList = array();
        $stateList = $this->stateObj->getStateDropdownWithAddOption();    
        
        if($city_id > 0){
            $cityList  = $this->cityObj->getCityDropdownByCityIdForFront($city_id);  
        }else{
            $cityList  = array('0' => 'All');        
        }

        $mileRadiusList = $this->_helper->getMileRadius();
        $services = $this->servicesObj->getServicesCheckboxListByModuleId($this->_helper->getModuleId());

        $reservationCategoryFor = $this->_helper->getReservationCategoryFor();
        $topBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'top');
        $sideBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'side');

        $pageTitle = $this->_helper->getPageTitle();
        $metaTitle = $this->_helper->getMetaTitle();
        $metaKeyword = $this->_helper->getMetaKeywords();
        $metaDescription = $this->_helper->getMetaDescription();
        $metaImage = $this->_helper->getMetaetaImage();

        return view('academies.index',compact('_data','paging','searchHelper','mileRadiusList','mileRadius','stateList','cityList','city','state','academies','services','sortedOrder','sortedBy','topBannerAds','sideBannerAds','pageTitle','metaTitle','metaKeyword','metaDescription','metaImage'));
    }

    public function getDetailsPage($url_kry){
        
        $academy = $this->academiesObj->getDetailPageByUrlKey($url_kry);
        if(empty($academy)){ return abort(404); }

        $reservationCategoryFor = $this->_helper->getReservationCategoryFor();
        $topBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'top');
        $sideBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'side');

        $pageTitle = $this->_helper->getPageTitle($academy->academy_name);
        $metaTitle = $this->_helper->getMetaTitle($academy->academy_name);
        $metaKeyword = $this->_helper->getMetaKeywords($academy->academy_name);
        $metaDescription = $this->_helper->getMetaDescription();
        $metaImage = $this->_helper->getMetaetaImage();
        
        return view('academies.details',compact('academy','topBannerAds','sideBannerAds','pageTitle','metaTitle','metaKeyword','metaDescription','metaImage'));
    }
}
