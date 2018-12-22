<?php
namespace App\Http\Controllers\Member\LookupForPlayerExperience;

use App\Classes\Models\LookupForPlayerExperience\LookupForPlayerExperience;
use App\Http\Controllers\Controller;
use App\Classes\Models\Images\Images;
use App\Classes\Models\Members\Members;
use Illuminate\Http\Request;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\Position\Position;
use App\Classes\Models\Experience\Experience;
use App\Classes\Models\BatsOrThrows\BatsOrThrows;
use App\Classes\Helpers\LookupForPlayerExperience\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller
{
	protected $LookupForPlayerExperienceObj;
	
	protected $memberObj;
	protected $agegroupObj;
	protected $positionObj;
	protected $experienceObj;
	protected $batsOrThrowsObj;
	protected $imagesObj;
	protected $_helper;
	
	public function __construct(LookupForPlayerExperience $lookupForPlayerExperience)
	{	
        $this->LookupForPlayerExperienceObj = $lookupForPlayerExperience;
		$this->memberObj = new Members();
		$this->agegroupObj = new AgeGroup();
		$this->positionObj = new Position();
		$this->experienceObj = new Experience();
		$this->batsOrThrowsObj = new BatsOrThrows();
		$this->imagesObj = new Images();
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
        $member_id = $this->LookupForPlayerExperienceObj->isLoginMember();
		$lookup_for_player_experience = $this->LookupForPlayerExperienceObj->list($search, $page, $member_id);
		$totalRecordCount= $this->LookupForPlayerExperienceObj->listTotalCount($search, $member_id);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->LookupForPlayerExperienceObj->preparePagination($totalRecordCount,$basePath);
		return view('member.lookup_for_player_experience.index',compact('lookup_for_player_experience','paging'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->LookupForPlayerExperienceObj->saveRecord($data);

		$show_advertise = $this->_helper->getShowAdvertise();
        $member_id = $this->LookupForPlayerExperienceObj->isLoginMember();
		$agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
		$position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
		$experience = $this->experienceObj->getExperienceDropdownByModuleId($this->_helper->getModuleId());
		$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
		$bats_or_throws = $this->batsOrThrowsObj->dropDownList($this->_helper->getModuleId());
        
		if(isset($result['id'])){

			$lookup_for_player_experience =$this->LookupForPlayerExperienceObj->display($result['id']);

			if($result['success']==false){
			    return view('member.lookup_for_player_experience.create',compact('lookup_for_player_experience','member_id','show_advertise','images','agegroup','position','experience','bats_or_throws'))->withErrors($result['message']);
			}else{ 
			 	$request->session()->flash('success', $result['message']);
			 	return view('member.lookup_for_player_experience.create',compact('lookup_for_player_experience','member_id','show_advertise','images','agegroup','position','experience','bats_or_throws'));
			}
		}else{ 

			if($result['success']==false){
			    return view('member.lookup_for_player_experience.create',compact('member','show_advertise','member_id','agegroup','position','experience','bats_or_throws'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('member.lookup_for_player_experience.create',compact('member','show_advertise','member_id','agegroup','position','experience','bats_or_throws'));
			}
		}
	}
  
    public function create()
	{
	   $show_advertise = $this->_helper->getShowAdvertise();
       $member_id = $this->LookupForPlayerExperienceObj->isLoginMember();
	   $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
	   $position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
	   $experience= $this->experienceObj->getExperienceDropdownByModuleId($this->_helper->getModuleId());
	   $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
	   $bats_or_throws = $this->batsOrThrowsObj->dropDownList($this->_helper->getModuleId());
	   
    	return view('member.lookup_for_player_experience.create',compact('member_id','show_advertise','images','agegroup','position','experience','bats_or_throws'));
	}
    
	public function edit($id){ 

	   $lookup_for_player_experience = $this->LookupForPlayerExperienceObj->display($id);

	   $show_advertise = $this->_helper->getShowAdvertise();
       $member_id = $this->LookupForPlayerExperienceObj->isLoginMember();
	   $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
	   $position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
	   $experience = $this->experienceObj->getExperienceDropdownByModuleId($this->_helper->getModuleId());
	   $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
	   $bats_or_throws = $this->batsOrThrowsObj->dropDownList($this->_helper->getModuleId());

	   return view('member.lookup_for_player_experience.create',compact('lookup_for_player_experience','member_id','show_advertise','images','agegroup','position','experience','bats_or_throws'));
    }

    public function destroy($id)
	{
		$isdelete =$this->LookupForPlayerExperienceObj->removed($id);
		if($isdelete){
			 return redirect()->route('member.lookup_for_player_experience.index')->with('success','Lookup For Player Experience Recored Deleted.');
		}else{
			 return redirect()->route('member.lookup_for_player_experience.index')->with('error','Lookup For Player Experience Recored not deleted.');
		}
    }
}
