<?php

namespace App\Http\Controllers\Showcase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrProspect;
use App\Classes\Helpers\ShowcaseOrProspect\Helper;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\TournamentOrganization\TournamentOrganization;
use App\Classes\Models\State\State;
use App\Classes\Models\Position\Position;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;
use App\Classes\Models\City\City;
use App\Classes\Helpers\Location;
use App\Classes\Models\ShowcaseDate\ShowcaseDate;
use App\Classes\Models\Members\Members;
use App\Classes\Models\MemberModulePermission\MemberModulePermission;
use DB;

class IndexController extends Controller{

    protected $showcaseOrProspectObj;
    protected $_helper;
    protected $stateObj;
    protected $tournamentOrganizationObj;
    protected $agegroupObj;
    protected $positionObj;
    protected $showcaseOrganizationObj;
    protected $bannerAdsCategoryObj;
    protected $cityObj;
    protected $locationObj;
    protected $showcaseDateObj;
    protected $memberObj;
    protected $memberModulePermission;

    public function __construct(ShowcaseOrProspect $showcaseOrProspect){

        $this->showcaseOrProspectObj = $showcaseOrProspect;
        $this->_helper = new Helper();
        $this->agegroupObj = new AgeGroup();
        $this->stateObj = new State();
        $this->tournamentOrganizationObj = new TournamentOrganization();
        $this->positionObj = new Position();     
        $this->showcaseOrganizationObj = new ShowcaseOrganization();
        $this->bannerAdsCategoryObj = new BannerAdsCategory();
        $this->cityObj = new City();
        $this->locationObj = new Location();
        $this->showcaseDateObj = new ShowcaseDate();
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
                if(! in_array("showcase", $memberModuleList)){
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
                
        $showcase_organization_id = !empty($request->get('showcase_organization_id')) ? $request->get('showcase_organization_id') : 0;
        $name = !empty($request->get('name')) ? $request->get('name') : '';
        $open_or_invite = !empty($request->get('open_or_invite')) ? $request->get('open_or_invite') : [];
        $age_group_id = !empty($request->get('age_group_id')) ? $request->get('age_group_id') : [];
        $position_id = !empty($request->get('position_id')) ? $request->get('position_id') : [];
        $start_date = !empty($request->get('start_date')) ? $request->get('start_date') : '';
        $end_date = !empty($request->get('end_date')) ? $request->get('end_date') : '';
        $mileRadius = !empty($request->get('mileRadius')) ? $request->get('mileRadius') : $this->_helper->getDefaultMileRadius();
        $sortedBy = !empty($request->get('sortedBy')) ? $request->get('sortedBy') : 'name';
        $sortedOrder = !empty($request->get('sortedOrder')) ? $request->get('sortedOrder') : 'ASC';
        
        $mileRadiusList = $this->_helper->getMileRadius();
        $tournamentOrganization = $this->tournamentOrganizationObj->getTournamentOrganizationDropdownWithAllOption();
        $openOrInvites = $this->_helper->getOpenOrInvitesForFront();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $position = $this->positionObj->getPositionCheckboxListByModuleIdForFrontSide($this->_helper->getModuleId());
        $showcaseOrganization = $this->showcaseOrganizationObj->getAllShowcaseOrganizationDropDownWithAllOption();

        $search_city = $city_id;
        $search_state = $state_id;
        if($mileRadius > 0 && !empty($latitude) && !empty($longitude)){
            $search_city =0;
            $search_state =0;
        }

        $showcase = $this->showcaseOrProspectObj->list($search='',$page, $type=1, $search_state, $search_city, $mileRadius, $isFront=1, $latitude, $longitude, $showcase_organization_id, $open_or_invite, $age_group_id, $position_id, $start_date, $end_date, $name, $sortedBy, $sortedOrder,$is_active=1, $per_page=0, $selectColoumn=array('*'),$submitted_by_id, $approvalStatus );
        $totalRecordCount = $this->showcaseOrProspectObj->listTotalCount($search='',$page, $type=1, $search_state, $search_city, $mileRadius, $isFront=1, $latitude, $longitude, $showcase_organization_id, $open_or_invite, $age_group_id, $position_id, $start_date, $end_date, $name, $sortedBy, $sortedOrder,$is_active=1, $submitted_by_id, $approvalStatus = '');
        $_data = $this->showcaseOrProspectObj->convertDataToHtml($showcase);

        /* For Pagination */
        if(!empty($city)) { $data['city'] = $city; }else{ unset($data['city']); }
        if(!empty($state)) { $data['state'] = $state; }else{ unset($data['state']); }

        if(isset($data['g-recaptcha-response'])){unset($data['g-recaptcha-response']);}

        $queryString = $this->showcaseOrProspectObj->getQueryString($data);
        $basePath=\Request::url().$queryString;
        
        $paging = $this->showcaseOrProspectObj->preparePagination($totalRecordCount,$basePath);

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

        return view('showcases.index',compact('_data','paging','mileRadiusList','mileRadius','stateList','cityList','state','city','tournamentOrganization','openOrInvites','agegroup','position','showcaseOrganization','sortedBy','sortedOrder','topBannerAds','sideBannerAds','pageTitle','metaTitle','metaKeyword','metaDescription','metaImage'));
    }

    public function getDetailsPage($url_kry){
        
        $showcase = $this->showcaseOrProspectObj->getDetailPageByUrlKey($url_kry);
        if(empty($showcase)){ return abort(404); }

        $reservationCategoryFor = $this->_helper->getReservationCategoryFor();
        $topBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'top');
        $sideBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'side');
        $dateList = $this->showcaseDateObj->getDateListByShowcaseOrProspectId($showcase->showcase_or_prospect_id);

        $pageTitle = $this->_helper->getPageTitle($showcase->name);
        $metaTitle = $this->_helper->getMetaTitle($showcase->name);
        $metaKeyword = $this->_helper->getMetaKeywords($showcase->name);
        $metaDescription = $this->_helper->getMetaDescription();
        $metaImage = $this->_helper->getMetaetaImage();
        
        return view('showcases.details',compact('showcase','topBannerAds','sideBannerAds','metaTitle','metaKeyword','metaDescription','metaImage','pageTitle','dateList'));
    }
}
