<?php
namespace App\Http\Controllers\Administrator\Team;

use App\Classes\Models\Members\Members;
use App\Classes\Models\Team\Team;
use App\Classes\Models\State\State;
use App\Classes\Models\Images\Images;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\City\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Helpers\Team\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{

 	protected $teamObj;
	protected $imagesObj;
	protected $stateObj;
	protected $memberObj;
	protected $cityObj;
	protected $agegroupObj;
	protected $_helper;

	public function __construct(Team $team)
	{	
        $this->teamObj = $team;
		$this->stateObj = new State();
		$this->imagesObj = new Images();
		$this->memberObj = new Members();
		$this->agegroupObj = new AgeGroup();
		$this->cityObj = new City();
		$this->_helper = new Helper();
	}
  
    public function index(Request $request)
	{
		if (!Gate::allows('teams')){ return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$teams = $this->teamObj->list($search,$page);
		$totalRecordCount= $this->teamObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->teamObj->preparePagination($totalRecordCount,$basePath);
        $entity = $this->teamObj->getEntity();
		return view('administrator.team.index',compact('teams','paging','entity'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->teamObj->saveRecord($data);

		$show_advertise = $this->_helper->getShowAdvertise();
        $images = $this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state  = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
		$age_group = $this->agegroupObj->getAgeGroupCheckboxListByModuleId( $this->_helper->getModuleId());
		$city = array();
        $approvalStatusList = $this->_helper->getApprovalStatusList();
		if(isset($result['id'])){
            $team =$this->teamObj->display($result['id']);
			$city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
			if($result['success']==false){
			    return view('administrator.team.create',compact('team','state','member','age_group','focus','team_coach','show_advertise','images','city','approvalStatusList'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.team.create',compact('team','state','member','age_group','focus','team_coach','show_advertise','images','city','approvalStatusList'));
			}
		}else{ 
			if($result['success']==false){
				return view('administrator.instructors.create',compact('state','member','age_group','focus','team_coach','show_advertise','images','city','approvalStatusList'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				
				return view('administrator.team.create',compact('state','member','age_group','focus','team_coach','show_advertise','images','city','approvalStatusList'));
				}
			}
		}
  
    public function create()
	{
		if (!Gate::allows('team_add')){ return abort(404); }

		$show_advertise = $this->_helper->getShowAdvertise();
		$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
		$state = $this->stateObj->getStateDropdown();
		$member = $this->memberObj->getMembersDropdown();
		$age_group = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
		$city = array();
        $approvalStatusList = $this->_helper->getApprovalStatusList();
 		
   	    return view('administrator.team.create',compact('state','member','age_group','show_advertise','images','city','approvalStatusList'));
	}
    
	public function edit($id)
	{ 
	   if (!Gate::allows('team_edit')){ return abort(404); }

	   $team = $this->teamObj->display($id);
	   $show_advertise = $this->_helper->getShowAdvertise();
	   $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
	   $state = $this->stateObj->getStateDropdown();
	   $member = $this->memberObj->getMembersDropdown();
	   $age_group = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
	   $city = $this->cityObj->getCityDropdownByCityId($team->city_id);
       $approvalStatusList = $this->_helper->getApprovalStatusList();
	   return view('administrator.team.create',compact('team','state','member','age_group','show_advertise','images','city','approvalStatusList'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('team_delete')){ return abort(404); }
		
		$isdelete =$this->teamObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.teams.index')->with('success','Team Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.teams.index')->with('error','Team Is Not Deleted Successfully.');
		}
    }
    public function duplicate($id){
        $event =$this->teamObj->display($id);
        $data = $event->toArray();
        unset($data['team_id']);
        $data['url_key'] = $this->duplicateRecord($data);
        $this->teamObj->saveRecord($data);
        return redirect()->route('administrator.teams.index')->with('success','Team duplicate created successfully.');
    }

    function duplicateRecord($data){
        $data['url_key'] = $this->teamObj->generateDuplidateUrlKey($data['url_key']);
        $result = $this->teamObj->checkDuplicateUrlKey($data['url_key']);
        if($result == 1 || $result == '1'){
            $data['url_key'] = $this->duplicateRecord($data);
        }
        return $data['url_key'];
    }
}
