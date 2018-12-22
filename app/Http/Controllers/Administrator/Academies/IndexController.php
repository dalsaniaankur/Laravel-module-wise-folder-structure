<?php
namespace App\Http\Controllers\Administrator\Academies;

use App\Classes\Models\Academies\Academies;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\Members\Members;
use App\Classes\Models\Images\Images;
use App\Classes\Models\Services\Services;
use App\Classes\Helpers\Academies\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{
    
	protected $academyObj;
	protected $imagesObj;
	protected $stateObj;
	protected $memberObj;
	protected $servicesObj;
	protected $cityObj;
	protected $_helper;
 
	public function __construct(Academies $academies)
	{	
		$this->academyObj = $academies;
		$this->imagesObj = new Images();
		$this->stateObj = new State();
		$this->memberObj = new Members();
		$this->servicesObj = new Services();
		$this->cityObj = new City();
		$this->_helper = new Helper();
    }
  
    public function index(Request $request)
	{
		if (!Gate::allows('academies')){ return abort(404); }
			
		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$academies = $this->academyObj->list($search,$page);
		$totalRecordCount= $this->academyObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->academyObj->preparePagination($totalRecordCount,$basePath);
        $entity = $this->academyObj->getEntity();

		return view('administrator.academies.index',compact('academies','paging','entity'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;

        $result = $this->academyObj->saveRecord($data);

        $show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
	    $services = $this->servicesObj->getServicesCheckboxListByModuleId($this->_helper->getModuleId());
	    $city = array();
        $approvalStatusList = $this->_helper->getApprovalStatusList();
		if(isset($result['id'])){

			$academies =$this->academyObj->display($result['id']);
			$city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
			if($result['success']==false){
			    return view('administrator.academies.create',compact('academies','state','member','show_advertise','images','services','city','approvalStatusList'))->withErrors($result['message']);
			}else{ 
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.academies.create',compact('academies','state','member','show_advertise','images','services','city','approvalStatusList'));
			}
		}else{ 

			if($result['success']==false){
			    return view('administrator.academies.create',compact('state','member','show_advertise','images','services','city','approvalStatusList'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.academies.create',compact('state','member','show_advertise','images','services','city','approvalStatusList'));
			}
		}
	}
  
    public function create()
	{
	   if (!Gate::allows('academie_add')){ return abort(404); }

       $show_advertise = $this->_helper->getShowAdvertise();
       $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
       $state = $this->stateObj->getStateDropdown();
       $member = $this->memberObj->getMembersDropdown();
	   $services = $this->servicesObj->getServicesCheckboxListByModuleId($this->_helper->getModuleId());
	   $city = array();
       $approvalStatusList = $this->_helper->getApprovalStatusList();
       return view('administrator.academies.create',compact('state','member','show_advertise','images','services','city','approvalStatusList'));
	}
    
	public function edit($id)
	{ 
	   if (!Gate::allows('academie_edit')){ return abort(404); }

	   $academies = $this->academyObj->display($id);

	   $show_advertise = $this->_helper->getShowAdvertise();
       $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
       $state = $this->stateObj->getStateDropdown();
	   $member = $this->memberObj->getMembersDropdown();
	   $services = $this->servicesObj->getServicesCheckboxListByModuleId($this->_helper->getModuleId());
	   $city = $this->cityObj->getCityDropdownByCityId($academies->city_id);
       $approvalStatusList = $this->_helper->getApprovalStatusList();
	   return view('administrator.academies.create',compact('academies','state','member','show_advertise','images','services','city','approvalStatusList'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('academie_delete')){ return abort(404); }
		
		$isdelete =$this->academyObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.academies.index')->with('success','Academy Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.academies.index')->with('error','Academy Is Not Deleted Successfully.');
		}
    }
    
    public function getGoogleLongitudeLatitude(Request $request){
        
        $data = $request->all();
        $data['zipcode'];
        if(!empty($data['address'])){
	        //$searchQuery = "components=postal_code:".$data['zipcode'];
	        $searchQuery = "address=".urlencode($data['address'].','.$data['city'].','.$data['zipcode']);
	        $latitudeAndLongitudeData = $this->academyObj->getLatitudeAndLongitudeData($searchQuery);
	        return response()->json($latitudeAndLongitudeData);
	    }
        return response()->json(false);
    }

    public function getCityDropdown(Request $request){
        
        $data = $request->all();
        $state_id = $data['state_id'];
        $city = trim($data['city']);
        
        if(!empty($state_id) && !empty($city)){
	        $cityList = $this->cityObj->getCityDropdownByStateIdAndCityName($state_id, $city);
	        return response()->json($cityList);
	    }
        return response()->json(false);
    }
    public function duplicate($id){
        $event =$this->academyObj->display($id);
        $data = $event->toArray();
        unset($data['academy_id']);
        $data['url_key'] = $this->duplicateRecord($data);
        $this->academyObj->saveRecord($data);
        return redirect()->route('administrator.academies.index')->with('success','Academy duplicate created successfully.');
    }

    function duplicateRecord($data){
        $data['url_key'] = $this->academyObj->generateDuplidateUrlKey($data['url_key']);
        $result = $this->academyObj->checkDuplicateUrlKey($data['url_key']);
        if($result == 1 || $result == '1'){
            $data['url_key'] = $this->duplicateRecord($data);
        }
        return $data['url_key'];
    }
}
