<?php

namespace App\Http\Controllers\PageBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\PageBuilder\PageBuilder;
use App\Classes\Helpers\PageBuilder\Helper;
use App\Classes\Models\Tournament\Tournament;
use App\Classes\Models\Team\Team;
use App\Classes\Models\Tryout\Tryout;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrProspect;
use App\Classes\Models\Academies\Academies;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;
use App\Classes\Models\Categories\Categories;
use App\Classes\Models\TournamentOrganization\TournamentOrganization;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\Services\Services;
use App\Classes\Models\Position\Position;
use App\Classes\Helpers\Tournament\Helper as TournamentHelper;
use App\Classes\Helpers\Team\Helper as TeamHelper;
use App\Classes\Helpers\Tryout\Helper as TryoutHelper;
use App\Classes\Helpers\AgeGroupPosition\Helper as AgeGroupPositionHelper;
use App\Classes\Helpers\Academies\Helper as AcademiesHelper;
use App\Classes\Helpers\ShowcaseOrProspect\Helper as ShowcaseOrProspectHelper;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization;


class IndexController extends Controller{

    protected $_helper;
    protected $stateObj;
    protected $pageBuilderObj;
    protected $tournamentObj;
    protected $teamObj;
    protected $tryoutObj;
    protected $showcaseOrProspectObj;
    protected $academiesObj;
    protected $bannerAdsCategoryObj;
    protected $categoriesObj;
    protected $cityObj;
    protected $tournamentOrganizationObj;
    protected $agegroupObj;
    protected $positionObj;
    protected $servicesObj;
    protected $_tournamentHelper;
    protected $_teamHelper;
    protected $_tryoutHelper;
    protected $_ageGroupPositionHelper;
    protected $_academiesHelper;
    protected $_showcaseOrProspectHelper;
    protected $showcaseOrganizationObj;

    
    public function __construct(PageBuilder $pageBuilder){

        $this->pageBuilderObj = $pageBuilder;
        $this->_helper = new Helper();
        $this->stateObj = new State();
        $this->tournamentObj = new Tournament();
        $this->teamObj = new Team();
        $this->tryoutObj = new Tryout();
        $this->showcaseOrProspectObj = new ShowcaseOrProspect();
        $this->academiesObj = new Academies();
        $this->bannerAdsCategoryObj = new BannerAdsCategory();
        $this->categoriesObj = new Categories();
        $this->cityObj = new City();
        $this->tournamentOrganizationObj = new TournamentOrganization();
        $this->agegroupObj = new AgeGroup();
        $this->servicesObj = new Services();
        $this->positionObj = new Position();
        $this->_tournamentHelper = new TournamentHelper();
        $this->_teamHelper = new TeamHelper();
        $this->_tryoutHelper = new TryoutHelper();
        $this->_ageGroupPositionHelper = new AgeGroupPositionHelper();
        $this->_academiesHelper = new AcademiesHelper();
        $this->_showcaseOrProspectHelper = new ShowcaseOrProspectHelper();
        $this->showcaseOrganizationObj = new ShowcaseOrganization();

    }

    public function index(Request $request, $urlkey){

        $data = $request->all();

        /* Page Builder */
        $page_builder = $this->pageBuilderObj->getPageByUrlKey($urlkey);

        /* 404 */
        if(empty($page_builder)){ return abort(404); }

        /* Parameters */
        $latitude = "";
        $longitude = "";
        $state = $this->stateObj->getStateCodeById($page_builder->state_id);
        $city_id = $page_builder->city_id;
        $state_id = ($city_id > 0) ? 0 : $page_builder->state_id;
        $redius = $page_builder->redius;
        $approvalStatus ='approved';
        $search='';

        /* Common Request */
        $page = !empty($request->get('page')) ? $request->get('page') : 0;
        $sortedOrder = !empty($request->get('sortedOrder')) ? $request->get('sortedOrder') : 'ASC';
        $city = !empty($request->get('city')) ? $request->get('city') : '';

        // City found
        if(!empty($city) && $city != '0'){

            $cityResult= $this->cityObj->getCityIdWithStateByName($city, $state);
            $city_id = $cityResult->city_id;
            $city = $cityResult->city;
            $latitude = $cityResult->latitude;
            $longitude = $cityResult->longitude;
        }

        /* City Search */
        if($city_id > 0){

            $locationInfo = $this->cityObj->getCityStateByCityId($city_id);
            $latitude = !empty($locationInfo->latitude) ? $locationInfo->latitude : ''; 
            $longitude = !empty($locationInfo->longitude) ? $locationInfo->longitude : ''; 
            $state_id = 0;
        }

        /* Common Drop-Down */
        if($city_id > 0){
            $cityList  = $this->cityObj->getCityDropdownByCityIdForFront($city_id);
        }else{
            $cityList  = array('0' => 'All');
        }

        /* Location to Search */
        if(!empty(trim($latitude)) && !empty(trim($longitude)) && $redius > 0){
            $city_id = $state_id = 0;
        }

        $topBannerAds = ($page_builder->banner_ads_category_id >0 && $page_builder->display_banner_ads ==1  ) ? $this->bannerAdsCategoryObj->getBannerAdsCategoryByIdWithAds($page_builder->banner_ads_category_id,'top') : array();
        $sideBannerAds = ($page_builder->banner_ads_category_id >0 && $page_builder->display_banner_ads ==1 ) ? $this->bannerAdsCategoryObj->getBannerAdsCategoryByIdWithAds($page_builder->banner_ads_category_id,'side') : array();

        /* Unset for Pagination  */
        if(isset($data['g-recaptcha-response'])){unset($data['g-recaptcha-response']);}
        if(isset($data['state'])){unset($data['state']);}

        switch ($page_builder->filter_table) {

                case $this->_helper->getTournamentTableKey():

                    /* Request */
                    $tournament_organization_id = !empty($request->get('tournament_organization_id')) ? $request->get('tournament_organization_id') : 0;
                    $age_group_id = !empty($request->get('age_group_id')) ? $request->get('age_group_id') : [];
                    $competition_level_id = !empty($request->get('competition_level_id')) ? $request->get('competition_level_id') : [];
                    $entry_fee = !empty($request->get('entry_fee')) ? $request->get('entry_fee') : '';
                    $tournament_name = !empty($request->get('tournament_name')) ? $request->get('tournament_name') : '';
                    $start_date = !empty($request->get('start_date')) ? $request->get('start_date') : '';
                    $end_date = !empty($request->get('end_date')) ? $request->get('end_date') : '';
                    $field_surface = !empty($request->get('field_surface')) ? $request->get('field_surface') : '';
                    $sortedBy = !empty($request->get('sortedBy')) ? $request->get('sortedBy') : 'tournament_name';
                    $guaranteed_games = !empty($request->get('guaranteed_games')) ? $request->get('guaranteed_games') : 0;
                    $hotel_required = isset($data['hotel_required']) ? $data['hotel_required'] : -1;

                    /* Get Data */
                    $tournamentTableName = $this->tournamentObj->getTable();
                    $tournamentOrganizationTableName = $this->tournamentOrganizationObj->getTable();

                    $selectColoumn = [ $tournamentTableName.'.*',
                        $tournamentOrganizationTableName.'.name'];

                    $tournament = $this->tournamentObj->list($search,$page, $tournament_organization_id, $state_id, $city_id, $redius, $isFront=1, $latitude, $longitude, $age_group_id, $competition_level_id, $entry_fee, $start_date, $end_date,$field_surface, $tournament_name, $sortedBy, $sortedOrder, $per_page=0, $selectColoumn, $submitted_by_id=0,$guaranteed_games, $hotel_required);
                    $totalRecordCount= $this->tournamentObj->listTotalCount($search, $tournament_organization_id, $state_id, $city_id, $redius, $isFront=1, $latitude, $longitude, $age_group_id, $competition_level_id, $entry_fee, $start_date, $end_date,$field_surface, $tournament_name, $sortedBy, $sortedOrder, $submitted_by_id=0,$guaranteed_games, $hotel_required);

                    /* Pagination */
                    $queryString = $this->tournamentObj->getQueryString($data);
                    $basePath=\Request::url().$queryString;
                    $paging=$this->tournamentObj->preparePagination($totalRecordCount,$basePath);

                    /* Look up */
                    $tournamentOrganization = $this->tournamentOrganizationObj->getTournamentOrganizationDropdownWithAllOption();
                    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_tournamentHelper->getModuleId());
                    $competitionLevellist = $this->_tournamentHelper->getCompetitionLevellist();
                    $fieldSurface = $this->_tournamentHelper->getFieldSurfaceForSearch();
                    $reservationCategoryFor = $this->_tournamentHelper->getReservationCategoryFor();
                    $guaranteedGamesList = $this->_tournamentHelper->getGuaranteedGamesList();
                    $hotelRequiredDropDown = $this->_tournamentHelper->getHotelRequiredDropDown();

                    return view('page_builder.tournament',compact('page_builder','tournament','paging','topBannerAds','sideBannerAds',
                                  'tournamentOrganization','agegroup','competitionLevellist','fieldSurface','reservationCategoryFor','sortedBy','sortedOrder','cityList','guaranteedGamesList','hotelRequiredDropDown',
                                    'tournament_organization_id','age_group_id','competition_level_id','entry_fee','tournament_name','start_date','end_date','field_surface','city','state'
                        ));
                    break;
                
                case $this->_helper->getTeamTableKey():

                    /* Request */
                    $name = !empty($request->get('name')) ? $request->get('name') : '';
                    $age_group_id = !empty($request->get('age_group_id')) ? $request->get('age_group_id') : [];
                    $sortedBy = !empty($request->get('sortedBy')) ? $request->get('sortedBy') : 'name';

                    /* Get Data */
                    $teams = $this->teamObj->list($search, $page, $state_id, $is_active = 1, $city_id, $redius, $isFront=1, $latitude, $longitude, $name, $age_group_id, $sortedBy, $sortedOrder , $per_page=0, $selectColoumn=array('*'), $submitted_by_id = 0, $approvalStatus);
                    $totalRecordCount= $this->teamObj->listTotalCount($search, $state_id, $is_active =1, $city_id, $redius, $isFront=1, $latitude, $longitude, $name, $age_group_id, $sortedBy, $sortedOrder, $submitted_by_id = 0, $approvalStatus );
                    $_data = $this->teamObj->convertDataToHtml($teams);

                    $queryString = $this->tournamentObj->getQueryString($data);
                    $basePath=\Request::url().$queryString;
                    $paging=$this->tournamentObj->preparePagination($totalRecordCount,$basePath);

                    /* Look up */
                    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_teamHelper->getModuleId());

                    return view('page_builder.teams',compact('page_builder','teams','paging','topBannerAds','sideBannerAds',
                        'name','age_group_id','state','city',
                        'team','agegroup','sortedBy','sortedOrder','cityList','_data'));
                    break;

                case $this->_helper->getTryoutTableKey():

                    /* Request */
                    $team_id = !empty($request->get('team_id')) ? $request->get('team_id') : 0;
                    $tryout_name = !empty($request->get('tryout_name')) ? $request->get('tryout_name') : '';
                    $age_group_id = !empty($request->get('age_group_id')) ? $request->get('age_group_id') : [];
                    $position_id = !empty($request->get('position_id')) ? $request->get('position_id') : [];
                    $start_date = !empty($request->get('start_date')) ? $request->get('start_date') : '';
                    $end_date = !empty($request->get('end_date')) ? $request->get('end_date') : '';
                    $sortedBy = !empty($request->get('sortedBy')) ? $request->get('sortedBy') : 'tryout_name';

                    /* Get Data */
                    $tryout = $this->tryoutObj->list($search,$page, $team_id ,$state_id, $city_id, $redius, $isFront=1, $latitude, $longitude, $age_group_id, $position_id, $start_date,$end_date, $tryout_name, $sortedBy, $sortedOrder, $per_page=0, $selectColoumn=array('*'), $submitted_by_id=0);
                    $totalRecordCount= $this->tryoutObj->listTotalCount($search, $team_id, $state_id, $city_id, $redius, $isFront=1, $latitude, $longitude, $age_group_id, $position_id,$start_date,$end_date, $tryout_name, $sortedBy, $sortedOrder, $submitted_by_id=0);

                    $queryString = $this->tournamentObj->getQueryString($data);
                    $basePath=\Request::url().$queryString;
                    $paging=$this->tournamentObj->preparePagination($totalRecordCount,$basePath);

                    /* Look up */
                    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_tryoutHelper->getModuleId());
                    $position = $this->positionObj->getPositionCheckboxListByModuleIdForFrontSide($this->_ageGroupPositionHelper->getModuleId());
                    $team = $this->teamObj->getAllTeamDropdown();

                    return view('page_builder.tryout',compact('page_builder','tryout','paging','topBannerAds','sideBannerAds',
                        'agegroup','position','team','sortedOrder','sortedBy','cityList',
                        'team_id','tryout_name','age_group_id','position_id','start_date','end_date','city','state'
                        ));
                    break;    

                case $this->_helper->getShowcaseTableKey():

                    /* Request */
                    $showcase_organization_id = !empty($request->get('showcase_organization_id')) ? $request->get('showcase_organization_id') : 0;
                    $name = !empty($request->get('name')) ? $request->get('name') : '';
                    $open_or_invite = !empty($request->get('open_or_invite')) ? $request->get('open_or_invite') : [];
                    $age_group_id = !empty($request->get('age_group_id')) ? $request->get('age_group_id') : [];
                    $position_id = !empty($request->get('position_id')) ? $request->get('position_id') : [];
                    $start_date = !empty($request->get('start_date')) ? $request->get('start_date') : '';
                    $end_date = !empty($request->get('end_date')) ? $request->get('end_date') : '';
                    $sortedBy = !empty($request->get('sortedBy')) ? $request->get('sortedBy') : 'name';

                    /* Get Data */
                    $showcaseOrProspects = $this->showcaseOrProspectObj->list($search, $page, $type=1, $state_id, $city_id, $redius, $isFront=1, $latitude, $longitude, $showcase_organization_id, $open_or_invite, $age_group_id, $position_id, $start_date, $end_date, $name, $sortedBy, $sortedOrder,$is_active=1, $per_page=0, $selectColoumn=array('*'),$submitted_by_id=0, $approvalStatus);
                    $totalRecordCount= $this->showcaseOrProspectObj->listTotalCount($search,$page, $type=1, $state_id, $city_id, $redius, $isFront=1, $latitude, $longitude, $showcase_organization_id, $open_or_invite, $age_group_id, $position_id, $start_date, $end_date, $name, $sortedBy, $sortedOrder,$is_active=1, $submitted_by_id=0, $approvalStatus);

                    $queryString = $this->tournamentObj->getQueryString($data);
                    $basePath=\Request::url().$queryString;
                    $paging=$this->tournamentObj->preparePagination($totalRecordCount,$basePath);

                    /* Look up */
                    $tournamentOrganization = $this->tournamentOrganizationObj->getTournamentOrganizationDropdownWithAllOption();
                    $openOrInvites = $this->_showcaseOrProspectHelper->getOpenOrInvitesForFront();
                    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_showcaseOrProspectHelper->getModuleId());
                    $position = $this->positionObj->getPositionCheckboxListByModuleIdForFrontSide($this->_showcaseOrProspectHelper->getModuleId());
                    $showcaseOrganization = $this->showcaseOrganizationObj->getAllShowcaseOrganizationDropDownWithAllOption();

                    return view('page_builder.showcaseorprospect',compact('page_builder','showcaseOrProspects','paging','topBannerAds','sideBannerAds',
                        'sortedBy','sortedOrder','cityList',
                        'tournamentOrganization','openOrInvites','agegroup','position','showcaseOrganization','city','state',
                        'showcase_organization_id','name','open_or_invite','age_group_id','position_id','start_date','end_date'));
                    break;  

                case $this->_helper->getAcademyTableKey():

                    /* Request */
                    $academy_name = !empty($request->get('academy_name')) ? $request->get('academy_name') : '';
                    $service_id = !empty($request->get('service_id')) ? $request->get('service_id') : [];
                    $sortedBy = !empty($request->get('sortedBy')) ? $request->get('sortedBy') : 'academy_name';


                    /* Get Data */
                    $academies = $this->academiesObj->list($search, $page, $state_id, $city_id, $redius, $isFront=1, $is_active=1, $latitude, $longitude, $academy_name, $service_id, $sortedBy, $sortedOrder, $per_page=0, $selectColoumn=array('*'), $member_id=0, $approvalStatus);
                    $totalRecordCount= $this->academiesObj->listTotalCount($search,$page=0, $state_id, $city_id, $redius, $isFront=1, $is_active=1, $latitude, $longitude, $academy_name, $service_id, $sortedBy, $sortedOrder, $member_id=0, $approvalStatus);

                    $queryString = $this->tournamentObj->getQueryString($data);
                    $basePath=\Request::url().$queryString;
                    $paging=$this->tournamentObj->preparePagination($totalRecordCount,$basePath);

                    /* Look up */
                    $services = $this->servicesObj->getServicesCheckboxListByModuleId($this->_academiesHelper->getModuleId());

                    return view('page_builder.academies',compact('page_builder','academies','paging','topBannerAds','sideBannerAds',
                        'services','academy_name','service_id','sortedOrder','sortedBy','cityList','state','city'));

                    break;

                default:
                    
                    return view('page_builder.details_page',compact('page_builder','topBannerAds','sideBannerAds'));
                    break;                
                    
            }
    }
}
