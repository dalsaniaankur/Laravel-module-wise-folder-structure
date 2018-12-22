<?php
namespace App\Http\Controllers\Administrator\CoachesNeeded;

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
		
		if (!Gate::allows('coaches_needed')){ return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$coaches_needed = $this->coachesNeededObj->list($search,$page);
		$totalRecordCount= $this->coachesNeededObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->coachesNeededObj->preparePagination($totalRecordCount,$basePath);
		return view('administrator.coaches_needed.index',compact('coaches_needed','paging'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->coachesNeededObj->saveRecord($data);
        $show_advertise = $this->_helper->getShowAdvertise();
     
	    $images = $this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
	    $member = $this->memberObj->getMembersDropdown();
	    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
	    $position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
	    $experience = $this->experienceObj->getExperienceDropdownByModuleId($this->_helper->getModuleId());
        $teams 		= $this->teamObj->getAllTeamDropdown();
		
		if(isset($result['id'])){
			$coaches_needed =$this->coachesNeededObj->display($result['id']);
			if($result['success']==false){
			    return view('administrator.coaches_needed.create',compact('coaches_needed','member','show_advertise','images','agegroup','position','experience','teams'))->withErrors($result['message']);
			}else{ 
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.coaches_needed.create',compact('coaches_needed','member','show_advertise','images','agegroup','position','experience','teams')); 		}
		}else{ 
			if($result['success']==false){
			    return view('administrator.coaches_needed.create',compact('member','show_advertise','images','agegroup','position','experience','teams'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.coaches_needed.create',compact('member','show_advertise','images','agegroup','position','experience','teams'));			}
		}
	}
  
    public function create()
	{
		if (!Gate::allows('coaches_needed_add')){ return abort(404); }

       $show_advertise = $this->_helper->getShowAdvertise();
       $images = $this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
	   $member = $this->memberObj->getMembersDropdown();
	   $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
	   $position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
	   $experience = $this->experienceObj->getExperienceDropdownByModuleId($this->_helper->getModuleId());
 	   $teams 		= $this->teamObj->getAllTeamDropdown();
		
       return view('administrator.coaches_needed.create',compact('member','show_advertise','images','agegroup','position','experience','teams'));
	}
    
	public function edit($id){ 
		
	   if (!Gate::allows('coaches_needed_edit')){ return abort(404); }

	   $coaches_needed = $this->coachesNeededObj->display($id);
	   $show_advertise = $this->_helper->getShowAdvertise();
       $images	= $this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
	   $member  = $this->memberObj->getMembersDropdown();
	   $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
	   $position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
	   $teams = $this->teamObj->getAllTeamDropdown();
	   $experience = $this->experienceObj->getExperienceDropdownByModuleId($this->_helper->getModuleId());
	   return view('administrator.coaches_needed.create',compact('coaches_needed','member','show_advertise','images','agegroup','position','experience','teams'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('coaches_needed_delete')){ return abort(404); }
		
		$isdelete =$this->coachesNeededObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.coaches_needed.index')->with('success','Coaches Needed Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.coaches_needed.index')->with('error','Coaches Needed Is Not Deleted Successfully.');
		}
    }
    public function duplicate($id){
        $event =$this->coachesNeededObj->display($id);
        $data = $event->toArray();
        unset($data['coaches_needed_id']);
        $data['url_key'] = $this->duplicateRecord($data);
        $this->coachesNeededObj->saveRecord($data);
        return redirect()->route('administrator.coaches_needed.index')->with('success','Coaches Needed duplicate created successfully.');
    }

    function duplicateRecord($data){
        $data['url_key'] = $this->coachesNeededObj->generateDuplidateUrlKey($data['url_key']);
        $result = $this->coachesNeededObj->checkDuplicateUrlKey($data['url_key']);
        if($result == 1 || $result == '1'){
            $data['url_key'] = $this->duplicateRecord($data);
        }
        return $data['url_key'];
    }
}
