<?php
namespace App\Http\Controllers\Member\Team;

use Auth;
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
        if (!Gate::allows('member_teams')){ return abort(404); }

        $page=0;
        $search='';
        if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
        $submitted_by_id = $this->teamObj->isLoginMember();
        $teams = $this->teamObj->list($search, $page, $state_id=0, $is_active = 2, $city_id=0, $redius=0, $isFront=0, $latitude='', $longitude='', $name='', $age_group_id=array(), $sortedBy='', $sortedOrder='' , $per_page=0, $selectColoumn=array('*'), $submitted_by_id);
        $totalRecordCount= $this->teamObj->listTotalCount($search,$state_id=0, $is_active = 2, $city_id=0, $redius=0, $isFront=0,  $latitude='', $longitude='', $name='', $age_group_id=array(), $sortedBy='', $sortedOrder='', $submitted_by_id);
        $basePath=\Request::url().'?search='.$search.'&';
        $paging=$this->teamObj->preparePagination($totalRecordCount,$basePath);
        $entity = $this->teamObj->getEntity();

        return view('member.team.index',compact('teams','paging','entity'));
    }

    public function save(Request $request)
    {
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->teamObj->saveRecord($data);
        $show_advertise = $this->_helper->getShowAdvertise();
        $images = $this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state  = $this->stateObj->getStateDropdown();
        $submitted_by_id = $this->teamObj->isLoginMember();
        $age_group = $this->agegroupObj->getAgeGroupCheckboxListByModuleId( $this->_helper->getModuleId());
        $city = array();
        if(isset($result['id'])){
            $team =$this->teamObj->display($result['id']);
            $city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
            if($result['success']==false){
                return view('member.team.create',compact('team','state','submitted_by_id','age_group','focus','team_coach','show_advertise','images','city'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('member.team.create',compact('team','state','submitted_by_id','age_group','focus','team_coach','show_advertise','images','city'));
            }
        }else{
            if($result['success']==false){
                return view('member.instructors.create',compact('state','submitted_by_id','age_group','focus','team_coach','show_advertise','images','city'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);

                return view('member.team.create',compact('state','submitted_by_id','age_group','focus','team_coach','show_advertise','images','city'));
            }
        }
    }

    public function create()
    {
        if (!Gate::allows('member_teams')){ return abort(404); }

        $show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
        $submitted_by_id = $this->teamObj->isLoginMember();
        $age_group = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $city = array();
        $defaultApprovalStatus = $this->_helper->getDefaultApprovalStatus();

        return view('member.team.create',compact('state','submitted_by_id','age_group','show_advertise','images','city','defaultApprovalStatus'));
    }

    public function edit($id)
    {
        if (!Gate::allows('member_teams')){ return abort(404); }

        $team = $this->teamObj->display($id);
        $show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
        $submitted_by_id = $this->teamObj->isLoginMember();
        $age_group = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $city = $this->cityObj->getCityDropdownByCityId($team->city_id);

        return view('member.team.create',compact('team','state','submitted_by_id','age_group','show_advertise','images','city'));
    }

    public function destroy($id)
    {
        if (!Gate::allows('member_teams')){ return abort(404); }

        $isdelete =$this->teamObj->removed($id);
        if($isdelete){
            return redirect()->route('member.teams.index')->with('success','Team Deleted Successfully.');
        }else{
            return redirect()->route('member.teams.index')->with('error','Team Is Not Deleted Successfully.');
        }
    }
}
