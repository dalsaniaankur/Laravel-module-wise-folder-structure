<?php
namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;
use App\Classes\Helpers\Home\Helper;
use App\Classes\Models\Tournament\Tournament;
use App\Classes\Models\Academies\Academies;
use App\Classes\Models\Team\Team;
use App\Classes\Models\Tryout\Tryout;
use App\Classes\Models\City\City;
use App\Classes\Models\State\State;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrProspect;
use App\Classes\Helpers\Location;
use App\Classes\Models\Categories\Categories;
use App\Classes\Models\PageBuilder\PageBuilder;
use App\Classes\Models\Subscribes\Subscribes;
use DB;

class HomeController extends Controller
{
	
    protected $tournamentObj;
    protected $academiesObj;
    protected $bannerAdsCategoryObj;
    protected $teamObj;
    protected $tryoutObj;
    protected $showcaseOrProspectObj;
    protected $cityObj;
    protected $stateObj;
    protected $locationObj;
    protected $categoriesObj;
    protected $pageBuilderObj;
    protected $subscribesObj;
    protected $_helper;
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->bannerAdsCategoryObj = new BannerAdsCategory();
        $this->academiesObj = new Academies();
        $this->tournamentObj = new Tournament();
        $this->teamObj = new Team();
        $this->tryoutObj = new Tryout();
        $this->showcaseOrProspectObj = new ShowcaseOrProspect();
        $this->cityObj = new City();
        $this->stateObj = new State();
        $this->locationObj = new Location();
        $this->categoriesObj = new Categories();
        $this->pageBuilderObj = new PageBuilder();
        $this->subscribesObj = new Subscribes();
        $this->_helper = new Helper();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $reservationCategoryFor = $this->_helper->getReservationCategoryFor();
        $topBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'top');
        $sideBannerAds = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithAds($reservationCategoryFor,'side');
         
        /* Social */ 
        $facebookUrl = $this->_helper->getFacebookUrl();
        $instagramUrl = $this->_helper->getInstagramUrl();
        $twitterUrl = $this->_helper->getTwitterUrl();
        
        /* Blog */
        $blogList = array();     
        $categories = $this->categoriesObj->getCategoryByUrlKey('blog');
        $blog_per_page = $this->_helper->getBlogFeedCountForHomePage();
        if(!empty($categories->category_id) && $categories->category_id > 0){ 
            $blogList = $this->pageBuilderObj->list('', $page=0, $categories->category_id, $status=1, $sortedBy ='page_builder_id', $sortedOrder='DESC', $blog_per_page);
        }

        /* Instagram */
        $instagram_error_message="";
        $access_token = $this->_helper->getInstagramAccessToken();
        $instagram_feed_count = $this->_helper->getInstagramFeedCount();
        $count ='&count='.$instagram_feed_count;
        $instagram_feed_data_json = @file_get_contents('https://api.instagram.com/v1/users/self/media/recent/?access_token='.$access_token.$count);
        $instagram_feed_data = json_decode($instagram_feed_data_json, true);
        if($instagram_feed_data['meta']['code'] == 200) {
            $instagram_feed_data = (!empty($instagram_feed_data['data'])) ? $instagram_feed_data['data'] : array();
        }else{
            $instagram_feed_data = array();
            $instagram_error_message = trans('front.instagram_access_token_invalid');
        }

        $pageTitle = $this->_helper->getPageTitle();
        $metaTitle = $this->_helper->getMetaTitle();
        $metaKeyword = $this->_helper->getMetaKeywords();
        $metaDescription = $this->_helper->getMetaDescription();
        $metaImage = $this->_helper->getMetaetaImage();
        
        return view('home',compact('topBannerAds','sideBannerAds','facebookUrl','instagramUrl','twitterUrl','blogList','instagram_feed_data','pageTitle','metaTitle','metaKeyword','metaDescription','metaImage','instagram_error_message'));

    }

     public function search(Request $request){
        $data = $request->all();
        $search = trim($data['term']);

        /* Check Location info */
        $locationInfo = $this->locationObj->getCookieLocation();

        /* Set Location info */
        if(empty($locationInfo['latitude'])){
            $this->locationObj->setLongAndLatitude();    
        }

        /* get Location Info */
        $locationInfo = $this->locationObj->getCookieLocation();
        $latitude     = !empty(trim($locationInfo['latitude'])) ? $locationInfo['latitude'] : '';
        $longitude    = !empty(trim($locationInfo['longitude'])) ? $locationInfo['longitude'] : '';
        $city_id      = !empty(trim($locationInfo['city_id'])) ? $locationInfo['city_id'] : 0 ;
        $state_id     = !empty(trim($locationInfo['state_id'])) ? $locationInfo['state_id'] : 0 ; 
        $redius       = $this->_helper->getMileRadius(); 
        
        /* If set Location info then flush city_id and State_id */
        if(!empty(trim($latitude)) && !empty(trim($longitude)) && $redius > 0){ $city_id = $state_id = 0; }

        $tournaments = $this->tournamentObj->HeaderSearch( $search, $city_id, $state_id, $latitude, $longitude, $redius );
        $teams = $this->teamObj->HeaderSearch( $search, $city_id, $state_id, $latitude, $longitude, $redius );
        $tryouts = $this->tryoutObj->HeaderSearch( $search, $city_id, $state_id, $latitude, $longitude, $redius );
        $academies = $this->academiesObj->HeaderSearch( $search, $city_id, $state_id, $latitude, $longitude, $redius );
        $showcases = $this->showcaseOrProspectObj->HeaderSearch( $search, $city_id, $state_id, $latitude, $longitude, $redius );
        
        $tournamentsResults = $this->bannerAdsCategoryObj->getSearchDataToFormat($tournaments, 'tournaments', $search, 'Tournaments', 'tournament_name');
        $teamsResults = $this->bannerAdsCategoryObj->getSearchDataToFormat($teams, 'teams', $search, 'Teams', 'name');
        $tryoutsResults = $this->bannerAdsCategoryObj->getSearchDataToFormat($tryouts, 'tryouts', $search, 'Tryouts', 'tryout_name');
        $academies = $this->bannerAdsCategoryObj->getSearchDataToFormat($academies, 'academies', $search, 'Academies', 'academy_name');
        $showcases = $this->bannerAdsCategoryObj->getSearchDataToFormat($showcases, 'showcases', $search, 'Showcases', 'name');

        return response()->json(array_merge($tournamentsResults,$teamsResults,$tryoutsResults,$academies,$showcases));
    }

    public function getCityDropdown(Request $request){
        $data = $request->all();
        $state = $data['state'];
        $state_id='';
        $city = $data['city'];
        $state_id = $this->stateObj->getStateByCode($state);
        $state_id = !empty($state_id->state_id) ? $state_id->state_id : 0;
        if($state_id){
            $latitudeAndLongitudeData = $this->cityObj->getCityDropdownByStateIdNameWise($state_id, $city);
            return response()->json($latitudeAndLongitudeData);
        }
        return response()->json([]);
    }

    public function getCityDropdownForRegistrationPage(Request $request){
        
        $data = $request->all();
        $state_id = $data['state_id'];
        $city = trim($data['city']);
        
        if(!empty($state_id) && !empty($city)){
            $cityList = $this->cityObj->getCityDropdownByStateIdAndCityName($state_id, $city);
            return response()->json($cityList);
        }
        return response()->json(false);
    }
    
    public function getSubscribeNewsletter(Request $request){
        $data = $request->all();
        $results = $this->subscribesObj->saveSubscriber($data);
        return response()->json($results);
                
    }

    /*public function importCityStateCsv(Request $request){
        $filePath = public_path("/csv/uscities.csv");

        $file = fopen($filePath, 'r');
        $count = 0;
        $cityNameColume = 0;
        $stateCodeColume = 1;
        $stateNameColume = 2;
        $latitudeColume = 3;
        $longitudeColume = 4;
        $countryCode = 'US';
        $current_date = Carbon::now()->toDateTimeString();

        while (!feof($file) ) {
            $row = fgetcsv($file, 1024);
            $count++;
            if ($count == 1) { continue; }

            $stateName = trim($row[$stateNameColume]); 
            if(!empty($stateName)){
                
                /* For State */
 /*               $stateTable = DB::table('sbc_state')->where('name', '=' , $stateName)->get()->first();
                if(empty($stateTable)){
                    DB::table('sbc_state')->insert( ['name' => $stateName, 'code' => $row[$stateCodeColume], 'status' => '1', 'created_at' => $current_date, 'updated_at' => $current_date] );
                }

                /* For City */
/*                $cityName = trim($row[$cityNameColume]); 
                if(!empty($cityName)){
                    $cityTable = DB::table('sbc_city')->where('city', '=' , $cityName)->get()->first();
                    if(empty($cityTable)){
                        DB::table('sbc_city')->insert( ['city' => $cityName, 'state' => $row[$stateCodeColume], 'country' => $countryCode, 'latitude' => $row[$latitudeColume], 'longitude' => $row[$longitudeColume], ] );
                    }
                }
            }
        }

        echo "You process has been completed"; exit;
    }*/

    public function importCityStateCsv(Request $request){
        $filePath = public_path("/csv/uscities.csv");

        $file = fopen($filePath, 'r');
        $count = 0;
        $cityNameColume = 0;
        $stateCodeColume = 1;
        $stateNameColume = 2;
        $latitudeColume = 3;
        $longitudeColume = 4;
        $countryCode = 'US';
        $current_date = Carbon::now()->toDateTimeString();

        while (!feof($file) ) {
            $row = fgetcsv($file, 1024);
            $count++;
            if ($count == 1) { continue; }

            $stateName = trim($row[$stateNameColume]); 
            if(!empty($stateName)){
                
                /* For State */
                /*$stateTable = DB::table('sbc_state')->where('name', '=' , $stateName)->get()->first();
                if(empty($stateTable)){
                    DB::table('sbc_state')->insert( ['name' => $stateName, 'code' => $row[$stateCodeColume], 'status' => '1', 'created_at' => $current_date, 'updated_at' => $current_date] );
                }*/

                /* For City */
                $cityName = trim($row[$cityNameColume]); 
                if(!empty($cityName)){
                    //$cityTable = DB::table('sbc_city')->where('city', '=' , $cityName)->get()->first();
                   // if(empty($cityTable)){
                        DB::table('sbc_city')->insert( ['city' => $cityName, 'state' => $row[$stateCodeColume], 'country' => $countryCode, 'latitude' => $row[$latitudeColume], 'longitude' => $row[$longitudeColume], 'created_at' => $current_date, 'updated_at' => $current_date ] );
                    //}
                }
            }
        }

        echo "You process has been completed"; exit;
    }

    public function postGoogleRecaptchaValidation(Request $request){
        $data = $request->all();
        $results = $this->tournamentObj->frontGoogleCaptchaValidation($data);
        return response()->json($results);
    }

}
