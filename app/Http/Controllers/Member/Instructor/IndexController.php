<?php
namespace App\Http\Controllers\Member\Instructor;

use Auth;
use App\Classes\Models\Instructors\Instructors;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\City\City;
use App\Classes\Models\State\State;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\Focus\Focus;
use App\Classes\Models\Members\Members;
use App\Classes\Models\Images\Images;
use App\Classes\Helpers\Instructor\Helper;
use Illuminate\Support\Facades\Gate;


class IndexController extends Controller{
    
	protected $instructorObj;
	protected $stateObj;
	protected $memberObj;
	protected $agegroupObj;
	protected $focusObj;
	protected $imagesObj;
	protected $cityObj;
	protected $_helper;
	 
	public function __construct(Instructors $instructors){	
        $this->instructorObj = $instructors;
		$this->stateObj = new State();
		$this->memberObj = new Members();
		$this->imagesObj = new Images();
		$this->agegroupObj = new AgeGroup();
		$this->focusObj = new Focus();		
		$this->cityObj = new City();
        $this->_helper = new Helper();
    }
  
    public function index(Request $request)
	{
		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
        $member_id = $this->instructorObj->isLoginMember();
		$instructors = $this->instructorObj->list($search, $page, $member_id);
		$totalRecordCount= $this->instructorObj->listTotalCount($search, $member_id);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->instructorObj->preparePagination($totalRecordCount,$basePath);
		return view('member.instructors.index',compact('instructors','paging'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->instructorObj->saveRecord($data);
		$team_coach = $this->_helper->getTeamCoach();
		$show_advertise = $this->_helper->getShowAdvertise();

	   	$state     = $this->stateObj->getStateDropdown();
	    $member    = $this->memberObj->getMembersDropdown();
		$images    = $this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
		$age_group = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
		$focus 	   = $this->focusObj->getFocusCheckboxListByModuleId($this->_helper->getModuleId());
		$city = array();
        $member_id = $this->instructorObj->isLoginMember();
		if(isset($result['id'])){
			$instructor =$this->instructorObj->display($result['id']);
			$city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
			if($result['success']==false){
			    return view('member.instructors.create',compact('instructor','state','member_id','age_group','focus','team_coach','show_advertise','images','city'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('member.instructors.create',compact('instructor','state','member_id','age_group','focus','team_coach','show_advertise','images','city'));
			}
		} else{ 
	    	if($result['success']==false){
			return view('member.instructors.create',compact('state','member','age_group','focus','team_coach','show_advertise','images','city','member_id'))->withErrors($result['message']);
		
			}else{
				$request->session()->flash('success', $result['message']);
				return view('member.instructors.create',compact('state','member','age_group','focus','team_coach','show_advertise','images','city','member_id'));
			}
		}
	}
  
    public function create()
	{
        $team_coach = $this->_helper->getTeamCoach();
		$show_advertise = $this->_helper->getShowAdvertise();

	   	$state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
		$age_group = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
		$focus = $this->focusObj->getFocusCheckboxListByModuleId($this->_helper->getModuleId());
		$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
		$city = array();
        $member_id = $this->instructorObj->isLoginMember();
    	return view('member.instructors.create',compact('state','member_id','age_group','focus','team_coach','show_advertise','images','city'));
	}
    
	public function edit($id)
	{ 
	    $instructor = $this->instructorObj->display($id);
	    $team_coach = $this->_helper->getTeamCoach();
		$show_advertise = $this->_helper->getShowAdvertise();
	   	$state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
		$age_group = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
		$focus = $this->focusObj->getFocusCheckboxListByModuleId();
		$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $member_id = $this->instructorObj->isLoginMember();
		$city = $this->cityObj->getCityDropdownByCityId($instructor->city_id);

	   return view('member.instructors.create',compact('instructor','state','member_id','age_group','focus','team_coach','show_advertise','images','city'));
    }

    public function destroy($id)
	{
		$isdelete =$this->instructorObj->removed($id);
		if($isdelete){
			 return redirect()->route('member.instructors.index')->with('success','Instructor Deleted Successfully.');
		}else{
			 return redirect()->route('member.instructors.index')->with('error','Instructor Is Not Deleted Successfully.');
		}
    }
}
