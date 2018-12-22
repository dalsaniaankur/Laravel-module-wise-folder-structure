<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Models\Team\Team;
use App\Classes\Helpers\Team\Helper;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Helpers\Location;
use DB;
use App\Classes\Models\Members\Members;
use App\Classes\Models\MemberModulePermission\MemberModulePermission;

class IndexController extends Controller{

    protected $teamObj;
    protected $_helper;
    protected $agegroupObj;
    protected $banneAdsCategoryObj;
    protected $bannerAdsCategoryObj;
    protected $stateObj;
    protected $cityObj;
    protected $locationObj;
    protected $memberObj;
    protected $memberModulePermission;

    
    public function __construct(Team $team){

        $this->teamObj = $team;
        $this->_helper = new \App\Classes\Helpers\Team\Helper();
        $this->agegroupObj = new AgeGroup();
        $this->banneAdsCategoryObj = new BannerAdsCategory();
        $this->cityObj = new City();
        $this->stateObj = new State();
        $this->locationObj = new Location();
        $this->memberObj = new Members();
        $this->memberModulePermission = new MemberModulePermission();
    }

    public function index(Request $request, $member_url_key=''){

        $data = $request->all();

        /* Member Filter */
        $submitted_by_id = 0;
        if(!empty($member_url_key)){
            $member = $this->memberObj->checkIsExistMember($member_url_key);
            if(!empty($member->member_id) && $member->member_id > 0) {
                $submitted_by_id = $member->member_id;
                $memberModuleList = $this->memberModulePermission->getMemberModuleListByMemberId($submitted_by_id);
                if(! in_array("teams", $memberModuleList)){
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
        if(empty($request->get('state')) && ($request->get('state')=='') && ( $request->get('city')==''  || $request->get('city')== '0' ) && !empty($data)){

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
        $name = !empty($request->get('name')) ? $request->get('name') : '';
        $age_group_id = !empty($request->get('age_group_id')) ? $request->get('age_group_id') : [];
        $mileRadius = !empty($request->get('mileRadius')) ? $request->get('mileRadius') : $this->_helper->getDefaultMileRadius();
        $sortedBy = !empty($request->get('sortedBy')) ? $request->get('sortedBy') : 'name';
        $sortedOrder = !empty($request->get('sortedOrder')) ? $request->get('sortedOrder') : 'ASC';

        $search_city = $city_id;
        $search_state = $state_id;
        if($mileRadius > 0 && !empty($latitude) && !empty($longitude)){
            $search_city =0;
            $search_state =0;
        }

        $team = $this->teamObj->list($search='',$page, $search_state, $is_active = 1, $search_city, $mileRadius, $isFront=1, $latitude, $longitude, $name, $age_group_id,  $sortedBy, $sortedOrder, $per_page=0, $selectColoumn=array('*'), $submitted_by_id, $approvalStatus );
        $totalRecordCount = $this->teamObj->listTotalCount($search='',$search_state , $is_active = 1, $search_city, $mileRadius, $isFront=1, $latitude, $longitude, $name, $age_group_id, $sortedBy, $sortedOrder, $submitted_by_id, $approvalStatus );
        $_data = $this->teamObj->convertDataToHtml($team);

        /* For Pagination */
        if(!empty($city)) { $data['city'] = $city; }else{ unset($data['city']); }
        if(!empty($state)) { $data['state'] = $state; }else{ unset($data['state']); }

        if(isset($data['g-recaptcha-response'])){unset($data['g-recaptcha-response']);}

        $queryString = $this->teamObj->getQueryString($data);
        $basePath=\Request::url().$queryString;

        $stateList = array();
        $stateList = $this->stateObj->getStateDropdownWithAddOption();    
        
        if($city_id > 0){
            $cityList  = $this->cityObj->getCityDropdownByCityIdForFront($city_id);  
        }else{
            $cityList  = array('0' => 'All');        
        }
        
        $paging = $this->teamObj->preparePagination($totalRecordCount,$basePath);
        $mileRadiusList = $this->_helper->getMileRadius();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $reservationCategoryFor = $this->_helper->getReservationCategoryFor();
        $topBannerAds = $this->banneAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'top');
        $sideBannerAds = $this->banneAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'side');

        $pageTitle = $this->_helper->getPageTitle();
        $metaTitle = $this->_helper->getMetaTitle();
        $metaKeyword = $this->_helper->getMetaKeywords();
        $metaDescription = $this->_helper->getMetaDescription();
        $metaImage = $this->_helper->getMetaetaImage();

        return view('team.index',compact('_data','paging','mileRadiusList','mileRadius','stateList','cityList','city','state','team','agegroup','sortedBy','sortedOrder','topBannerAds','sideBannerAds','pageTitle','metaTitle','metaKeyword','metaDescription','metaImage'));
    }

    public function getDetailsPage($url_kry){
        
        $team = $this->teamObj->getDetailPageByUrlKey($url_kry);
        if(empty($team)){ return abort(404); }

        $reservationCategoryFor = $this->_helper->getReservationCategoryFor();
        $topBannerAds = $this->banneAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'top');
        $sideBannerAds = $this->banneAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'side');

        $pageTitle = $this->_helper->getPageTitle($team->name);
        $metaTitle = $this->_helper->getMetaTitle($team->name);
        $metaKeyword = $this->_helper->getMetaKeywords($team->name);
        $metaDescription = $this->_helper->getMetaDescription();
        $metaImage = $this->_helper->getMetaetaImage();
        
        return view('team.details',compact('team','topBannerAds','sideBannerAds','pageTitle','metaTitle','metaKeyword','metaDescription','metaImage'));
    }
}
