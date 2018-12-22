<?php

namespace App\Http\Controllers\Tournament;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Models\Tournament\Tournament;
use App\Classes\Helpers\Tournament\Helper;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\TournamentOrganization\TournamentOrganization;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;
use App\Classes\Helpers\Location;
use App\Classes\Models\Members\Members;
use App\Classes\Models\MemberModulePermission\MemberModulePermission;
use DB;

class IndexController extends Controller{

    protected $tournamentObj;
    protected $_helper;
    protected $stateObj;
    protected $cityObj;
    protected $tournamentOrganizationObj;
    protected $agegroupObj;
    protected $bannerAdsCategoryObj;
    protected $locationObj;
    protected $memberObj;
    protected $memberModulePermission;
    
    public function __construct(Tournament $tournament){

        $this->tournamentObj = $tournament;
        $this->stateObj = new State();
        $this->tournamentOrganizationObj = new TournamentOrganization();
        $this->_helper = new Helper();
        $this->agegroupObj = new AgeGroup();
        $this->bannerAdsCategoryObj = new BannerAdsCategory();
        $this->cityObj = new City();
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
                if(! in_array("tournaments", $memberModuleList)){
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
        if(!empty($request->get('city')) && $request->get('city') != '0'){
            
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
        $tournament_organization_id = !empty($request->get('tournament_organization_id')) ? $request->get('tournament_organization_id') : 0;
        $age_group_id = !empty($request->get('age_group_id')) ? $request->get('age_group_id') : [];
        $competition_level_id = !empty($request->get('competition_level_id')) ? $request->get('competition_level_id') : [];
        $competition_level_id = $this->tournamentObj->unsetArrayByValue($competition_level_id, 0 );
        $entry_fee = !empty($request->get('entry_fee')) ? $request->get('entry_fee') : '';
        $tournament_name = !empty($request->get('tournament_name')) ? $request->get('tournament_name') : '';
        $start_date = !empty($request->get('start_date')) ? $request->get('start_date') : '';
        $end_date = !empty($request->get('end_date')) ? $request->get('end_date') : '';
        $field_surface = !empty($request->get('field_surface')) ? $request->get('field_surface') : '';
        $mileRadius = !empty($request->get('mileRadius')) ? $request->get('mileRadius') : $this->_helper->getDefaultMileRadius();
        $sortedBy = !empty($request->get('sortedBy')) ? $request->get('sortedBy') : 'tournament_name';
        $sortedOrder = !empty($request->get('sortedOrder')) ? $request->get('sortedOrder') : 'ASC';
        $guaranteed_games = !empty($request->get('guaranteed_games')) ? $request->get('guaranteed_games') : 0;
        $hotel_required = isset($data['hotel_required']) ? $data['hotel_required'] : -1;

        $search_city = $city_id;
        $search_state = $state_id;
        if($mileRadius > 0 && !empty($latitude) && !empty($longitude)){
           $search_city =0;
           $search_state =0;
        }
        $tournamentTableName = $this->tournamentObj->getTable();
        $tournamentOrganizationTableName = $this->tournamentOrganizationObj->getTable();

        $selectColoumn = [ $tournamentTableName.'.*',
                            $tournamentOrganizationTableName.'.name'];
        $tournament = $this->tournamentObj->list($search='',$page, $tournament_organization_id, $search_state, $search_city, $mileRadius, $isFront=1, $latitude, $longitude, $age_group_id, $competition_level_id , $entry_fee, $start_date, $end_date, $field_surface, $tournament_name, $sortedBy, $sortedOrder, $per_page=0, $selectColoumn, $submitted_by_id,$guaranteed_games, $hotel_required);
        $totalRecordCount = $this->tournamentObj->listTotalCount($search, $tournament_organization_id,$search_state,$search_city, $mileRadius, $isFront=1, $latitude, $longitude, $age_group_id, $competition_level_id, $entry_fee, $start_date, $end_date, $field_surface, $tournament_name, $sortedBy, $sortedOrder, $submitted_by_id,$guaranteed_games, $hotel_required);

        /* For Pagination */
        if(!empty($city)) { $data['city'] = $city; }else{ unset($data['city']); }
        if(!empty($state)) { $data['state'] = $state; }else{ unset($data['state']); }

        if(isset($data['g-recaptcha-response'])){unset($data['g-recaptcha-response']);}

        $queryString = $this->tournamentObj->getQueryString($data);
        $basePath=\Request::url().$queryString;
        
        $paging=$this->tournamentObj->preparePagination($totalRecordCount,$basePath);
        $_data = $this->tournamentObj->convertDataToHtml($tournament);
        
        $stateList = array();
        $stateList = $this->stateObj->getStateDropdownWithAddOption();    

        if($city_id > 0){
            $cityList  = $this->cityObj->getCityDropdownByCityIdForFront($city_id);  
        }else{
            $cityList  = array('0' => 'All');        
        }
    
        $mileRadiusList = $this->_helper->getMileRadius();
        $tournamentOrganization = $this->tournamentOrganizationObj->getTournamentOrganizationDropdownWithAllOption();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $competitionLevellist = $this->_helper->getCompetitionLevellist();
        $fieldSurface = $this->_helper->getFieldSurfaceForSearch();
        $reservationCategoryFor = $this->_helper->getReservationCategoryFor();
        $guaranteedGamesList = $this->_helper->getGuaranteedGamesList();
        $hotelRequiredDropDown = $this->_helper->getHotelRequiredDropDown();

        $topBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'top');
        $sideBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'side');

        $pageTitle = $this->_helper->getPageTitle();
        $metaTitle = $this->_helper->getMetaTitle();
        $metaKeyword = $this->_helper->getMetaKeywords();
        $metaDescription = $this->_helper->getMetaDescription();
        $metaImage = $this->_helper->getMetaetaImage();

        return view('tournament.index',compact('_data','paging','mileRadius','mileRadiusList','stateList','cityList','city','state','tournamentOrganization','agegroup','competitionLevellist','fieldSurface','entry_fee','sortedBy','sortedOrder','topBannerAds','sideBannerAds','pageTitle','metaTitle','metaKeyword','metaDescription','metaImage','tournament','guaranteedGamesList','hotelRequiredDropDown'));
    }

    public function getDetailsPage($url_kry){ 
        $tournament = $this->tournamentObj->getDetailPageByUrlKey($url_kry);
        if(empty($tournament)){ return abort(404); }
        $competitionLevelIdArray = $this->tournamentObj->getCSVToArray($tournament->competition_level_id);
        $competitionLevelList = $this->_helper->getCompetitionLevellistById($competitionLevelIdArray);

        $reservationCategoryFor = $this->_helper->getReservationCategoryFor();
        $topBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'top');
        $sideBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'side');

        $pageTitle = $this->_helper->getPageTitle($tournament->tournament_name);
        $metaTitle = $this->_helper->getMetaTitle($tournament->tournament_name);
        $metaKeyword = $this->_helper->getMetaKeywords($tournament->tournament_name);
        $metaDescription = $this->_helper->getMetaDescription();
        $metaImage = $this->_helper->getMetaetaImage();
        
        return view('tournament.details',compact('tournament','competitionLevelList','topBannerAds','sideBannerAds','pageTitle','metaTitle','metaKeyword','metaDescription','metaImage'));
    }
}
