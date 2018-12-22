<?php

namespace App\Http\Controllers\Tryout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\State\State;
use App\Classes\Models\Position\Position;
use App\Classes\Models\Tryout\Tryout;
use App\Classes\Helpers\Tryout\Helper;
use App\Classes\Models\Team\Team;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;
use App\Classes\Models\City\City;
use App\Classes\Helpers\Location;
use App\Classes\Helpers\AgeGroupPosition\Helper as AgeGroupPositionHelper;
use App\Classes\Models\TryoutDate\TryoutDate;
use DB;
use App\Classes\Models\Members\Members;
use App\Classes\Models\MemberModulePermission\MemberModulePermission;

class IndexController extends Controller{

    protected $_helper;
    protected $stateObj;
    protected $agegroupObj;
    protected $positionObj;
    protected $tryoutObj;
    protected $teamObj;
    protected $bannerAdsCategoryObj;
    protected $cityObj;
    protected $locationObj;
    protected $_ageGroupPositionHelper;
    protected $tryoutDateObj;
    protected $memberObj;
    protected $memberModulePermission;
    
    public function __construct(Tryout $tryout){

        $this->_helper = new Helper();
        $this->tryoutObj = $tryout;
        $this->agegroupObj = new AgeGroup();
        $this->stateObj = new State();
        $this->positionObj = new Position();     
        $this->teamObj = new Team();
        $this->bannerAdsCategoryObj = new BannerAdsCategory();
        $this->cityObj = new City();
        $this->locationObj = new Location();
        $this->_ageGroupPositionHelper = new AgeGroupPositionHelper();
        $this->tryoutDateObj = new TryoutDate();
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
                if(! in_array("tryouts", $memberModuleList)){
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
        if(!empty($request->get('city')) && $request->get('city') !='0' ){

            $city = $request->get('city');
            $cityResult= $this->cityObj->getCityIdWithStateByName($city, $state);
            $cookieData = array('city'=> $cityResult->city,
                          'state'=> $cityResult->state,
                          'latitude'=> $cityResult->latitude,
                          'longitude'=>$cityResult->longitude);
          
            $this->locationObj->saveLocation($cookieData);
        }
        
        //state city not found  
        if(empty($request->get('state')) && ($request->get('state')=='') && ( $request->get('city')=='' || $request->get('city')=='0' ) && !empty($data)){

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
                
        $team_id = !empty($request->get('team_id')) ? $request->get('team_id') : 0;
        $tryout_name = !empty($request->get('tryout_name')) ? $request->get('tryout_name') : '';
        $age_group_id = !empty($request->get('age_group_id')) ? $request->get('age_group_id') : [];
        $position_id = !empty($request->get('position_id')) ? $request->get('position_id') : [];
        $start_date = !empty($request->get('start_date')) ? $request->get('start_date') : '';
        $end_date = !empty($request->get('end_date')) ? $request->get('end_date') : '';
        $mileRadius = !empty($request->get('mileRadius')) ? $request->get('mileRadius') : $this->_helper->getDefaultMileRadius();
        $sortedBy = !empty($request->get('sortedBy')) ? $request->get('sortedBy') : 'tryout_name';
        $sortedOrder = !empty($request->get('sortedOrder')) ? $request->get('sortedOrder') : 'ASC';
        
        $mileRadiusList = $this->_helper->getMileRadius();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());

        $position = $this->positionObj->getPositionCheckboxListByModuleIdForFrontSide($this->_ageGroupPositionHelper->getModuleId());
        $team = $this->teamObj->getAllTeamDropdown();

        $search_city = $city_id;
        $search_state = $state_id;
        if($mileRadius > 0 && !empty($latitude) && !empty($longitude)){
            $search_city =0;
            $search_state =0;
        }

        $tryout = $this->tryoutObj->list($search='',$page, $team_id, $search_state, $search_city, $mileRadius, $isFront=1, $latitude, $longitude, $age_group_id, $position_id,$start_date,$end_date, $tryout_name,  $sortedBy, $sortedOrder, $per_page=0, $selectColoumn=array('*'), $submitted_by_id);
        $totalRecordCount = $this->tryoutObj->listTotalCount($search='', $team_id, $search_state, $search_city, $mileRadius, $isFront=1, $latitude, $longitude, $age_group_id,$position_id,$start_date,$end_date, $tryout_name, $sortedBy, $sortedOrder, $submitted_by_id);
        $_data = $this->tryoutObj->convertDataToHtml($tryout);

        /* For Pagination */
        if(!empty($city)) { $data['city'] = $city; }else{ unset($data['city']); }
        if(!empty($state)) { $data['state'] = $state; }else{ unset($data['state']); }

        if(isset($data['g-recaptcha-response'])){unset($data['g-recaptcha-response']);}

        $queryString = $this->teamObj->getQueryString($data);
        $basePath=\Request::url().$queryString;

        $paging = $this->teamObj->preparePagination($totalRecordCount,$basePath);

        $stateList = array();
        $stateList = $this->stateObj->getStateDropdownWithAddOption();    
        
        if($city_id > 0){
            $cityList  = $this->cityObj->getCityDropdownByCityIdForFront($city_id);  
        }else{
            $cityList  = array('0' => 'All');        
        }
        
        $reservationCategoryFor = $this->_helper->getReservationCategoryFor();
        $topBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'top');
        $sideBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'side');

        $pageTitle = $this->_helper->getPageTitle();
        $metaTitle = $this->_helper->getMetaTitle();
        $metaKeyword = $this->_helper->getMetaKeywords();
        $metaDescription = $this->_helper->getMetaDescription();
        $metaImage = $this->_helper->getMetaetaImage();

        return view('tryout.index',compact('_data','paging','mileRadiusList','mileRadius','stateList','cityList','city','state','agegroup','openOrInvites','position','team','sortedOrder','sortedBy','topBannerAds','sideBannerAds','pageTitle','metaTitle','metaKeyword','metaDescription','metaImage'));
    }

    public function getDetailsPage($url_kry){
        
        $tryout = $this->tryoutObj->getDetailPageByUrlKey($url_kry);
        if(empty($tryout)){ return abort(404); }

        $reservationCategoryFor = $this->_helper->getReservationCategoryFor();
        $topBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'top');
        $sideBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'side');
        $dateList = $this->tryoutDateObj->getDateListByTryoutId($tryout->tryout_id);

        $pageTitle = $this->_helper->getPageTitle($tryout->tryout_name);
        $metaTitle = $this->_helper->getMetaTitle($tryout->tryout_name);
        $metaKeyword = $this->_helper->getMetaKeywords($tryout->tryout_name);
        $metaDescription = $this->_helper->getMetaDescription();
        $metaImage = $this->_helper->getMetaetaImage();
        
        return view('tryout.details',compact('tryout','topBannerAds','sideBannerAds','pageTitle','metaTitle','metaKeyword','metaDescription','metaImage','dateList'));
    }
}
