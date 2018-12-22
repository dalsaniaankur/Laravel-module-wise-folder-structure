<?php
namespace App\Http\Controllers\Administrator\Instructor;

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
		if (!Gate::allows('instructors')){
           return abort(404);
        }
		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$instructors = $this->instructorObj->list($search,$page);
		$totalRecordCount= $this->instructorObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->instructorObj->preparePagination($totalRecordCount,$basePath);

		return view('administrator.instructors.index',compact('instructors','paging'));
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
		
		if(isset($result['id'])){
			$instructor =$this->instructorObj->display($result['id']);
			$city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
			if($result['success']==false){
			    return view('administrator.instructors.create',compact('instructor','state','member','age_group','focus','team_coach','show_advertise','images','city'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.instructors.create',compact('instructor','state','member','age_group','focus','team_coach','show_advertise','images','city'));
			}
		} else{ 
	    	if($result['success']==false){
			return view('administrator.instructors.create',compact('state','member','age_group','focus','team_coach','show_advertise','images','city'))->withErrors($result['message']);
		
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.instructors.create',compact('state','member','age_group','focus','team_coach','show_advertise','images','city'));
			}
		}
	}
  
    public function create()
	{
		if (!Gate::allows('instructor_add')){
           return abort(404);
        }
        $team_coach = $this->_helper->getTeamCoach();
		$show_advertise = $this->_helper->getShowAdvertise();

	   	$state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
		$age_group = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
		$focus = $this->focusObj->getFocusCheckboxListByModuleId($this->_helper->getModuleId());
		$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
		$city = array();
    	return view('administrator.instructors.create',compact('state','member','age_group','focus','team_coach','show_advertise','images','city'));
	}
    
	public function edit($id)
	{ 
		if (!Gate::allows('instructor_edit')){
           return abort(404);
        }
	    $instructor = $this->instructorObj->display($id);
	    $team_coach = $this->_helper->getTeamCoach();
		$show_advertise = $this->_helper->getShowAdvertise();
	   	$state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
		$age_group = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
		$focus = $this->focusObj->getFocusCheckboxListByModuleId();
		$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
		$city = $this->cityObj->getCityDropdownByCityId($instructor->city_id);

	   return view('administrator.instructors.create',compact('instructor','state','member','age_group','focus','team_coach','show_advertise','images','city'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('instructor_delete')){
           return abort(404);
        }
		$isdelete =$this->instructorObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.instructors.index')->with('success','Instructor Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.instructors.index')->with('error','Instructor Is Not Deleted Successfully.');
		}
    }
    public function duplicate($id){
        $instructors =$this->instructorObj->display($id);
        $data = $instructors->toArray();
        unset($data['instructor_id']);
        $data['url_key'] = $this->duplicateRecord($data);
        $this->instructorObj->saveRecord($data);
        return redirect()->route('administrator.instructors.index')->with('success','Instructor duplicate created successfully.');
    }

    function duplicateRecord($data){
        $data['url_key'] = $this->instructorObj->generateDuplidateUrlKey($data['url_key']);
        $result = $this->instructorObj->checkDuplicateUrlKey($data['url_key']);
        if($result == 1 || $result == '1'){
            $data['url_key'] = $this->duplicateRecord($data);
        }
        return $data['url_key'];
    }
}
