<?php
namespace App\Http\Controllers\Member\CoachesNeeded;

use App\Classes\Models\CoachesNeeded\CoachesNeeded;
use App\Http\Controllers\Controller;
use App\Classes\Models\Images\Images;
use App\Classes\Models\Members\Members;
use Illuminate\Http\Request;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\Position\Position;
use App\Classes\Models\Experience\Experience;
use App\Classes\Helpers\CoachesNeeded\Helper;
use App\Classes\Models\Team\Team;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{

	protected $coachesNeededObj;
	protected $teamObj;
	protected $imagesObj;
	protected $memberObj;
	protected $agegroupObj;
	protected $positionObj;
	protected $experienceObj;
	protected $_helper;

 
	public function __construct(CoachesNeeded $coachesNeeded)
	{	
        $this->coachesNeededObj = $coachesNeeded;
		$this->imagesObj = new Images();
		$this->memberObj = new Members();
		$this->agegroupObj = new AgeGroup();
		$this->positionObj = new Position();
		$this->experienceObj = new Experience();
		$this->teamObj=new Team();
		$this->_helper = new Helper();
    }
  
    public function index(Request $request){
		
		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
        $member_id = $this->coachesNeededObj->isLoginMember();

		$coaches_needed = $this->coachesNeededObj->list($search,$page, $member_id);
		$totalRecordCount= $this->coachesNeededObj->listTotalCount($search, $member_id);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->coachesNeededObj->preparePagination($totalRecordCount,$basePath);
		return view('member.coaches_needed.index',compact('coaches_needed','paging'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->coachesNeededObj->saveRecord($data);
        $show_advertise = $this->_helper->getShowAdvertise();
     
	    $images = $this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $member_id = $this->coachesNeededObj->isLoginMember();
	    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
	    $position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
	    $experience = $this->experienceObj->getExperienceDropdownByModuleId($this->_helper->getModuleId());
        $teams 		= $this->teamObj->getAllTeamDropdown();
		
		if(isset($result['id'])){
			$coaches_needed =$this->coachesNeededObj->display($result['id']);
			if($result['success']==false){
			    return view('member.coaches_needed.create',compact('coaches_needed','member_id','show_advertise','images','agegroup','position','experience','teams'))->withErrors($result['message']);
			}else{ 
			 	$request->session()->flash('success', $result['message']);
			 	return view('member.coaches_needed.create',compact('coaches_needed','member_id','show_advertise','images','agegroup','position','experience','teams')); 		}
		}else{ 
			if($result['success']==false){
			    return view('member.coaches_needed.create',compact('member_id','show_advertise','images','agegroup','position','experience','teams'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('member.coaches_needed.create',compact('member_id','show_advertise','images','agegroup','position','experience','teams'));			}
		}
	}
  
    public function create()
	{
       $show_advertise = $this->_helper->getShowAdvertise();
       $images = $this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $member_id = $this->coachesNeededObj->isLoginMember();
	   $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
	   $position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
	   $experience = $this->experienceObj->getExperienceDropdownByModuleId($this->_helper->getModuleId());
 	   $teams 		= $this->teamObj->getAllTeamDropdown();
		
       return view('member.coaches_needed.create',compact('member_id','show_advertise','images','agegroup','position','experience','teams'));
	}
    
	public function edit($id){ 
		
	   $coaches_needed = $this->coachesNeededObj->display($id);
	   $show_advertise = $this->_helper->getShowAdvertise();
       $images	= $this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
       $member_id = $this->coachesNeededObj->isLoginMember();
	   $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
	   $position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
	   $teams = $this->teamObj->getAllTeamDropdown();
	   $experience = $this->experienceObj->getExperienceDropdownByModuleId($this->_helper->getModuleId());
	   return view('member.coaches_needed.create',compact('coaches_needed','member_id','show_advertise','images','agegroup','position','experience','teams'));
    }

    public function destroy($id)
	{
		$isdelete =$this->coachesNeededObj->removed($id);
		if($isdelete){
			 return redirect()->route('member.coaches_needed.index')->with('success','Coaches Needed Deleted Successfully.');
		}else{
			 return redirect()->route('member.coaches_needed.index')->with('error','Coaches Needed Is Not Deleted Successfully.');
		}
    }
}
