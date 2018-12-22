<?php
namespace App\Http\Controllers\Administrator\Members;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\Members\Members;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\Description\Description;
use Illuminate\Support\Facades\Gate;
use App\Classes\Helpers\Member\Helper;
use App\Classes\Models\MemberModulePermission\MemberModulePermission;

class IndexController extends Controller{
  	
	protected $memberObj;
	protected $descriptionObj;
	protected $stateObj;
	protected $cityObj;
    protected $_helper;
    protected $memberModulePermission;

	public function __construct(Members $members)
	{
        $this->memberObj = $members;
        $this->descriptionObj = new Description();
        $this->stateObj = new State();
        $this->cityObj = new City();
        $this->_helper = new Helper();
        $this->memberModulePermission = new MemberModulePermission();
    }
  
    public function index(Request $request)
	{
		if (!Gate::allows('members')){
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
        
		$members = $this->memberObj->list($search,$page);
		$totalRecordCount= $this->memberObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->memberObj->preparePagination($totalRecordCount,$basePath);

		return view('administrator.members.index',compact('members','paging'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result=$this->memberObj->saveRecord($data);
		
		$state = $this->stateObj->getStateDropdown();
    	$description = $this->descriptionObj->checkboxList();
    	$city = array();
        $moduleList = $this->_helper->getModuleList();
        $memberModuleList = array();

		if(isset($result['id'])){
			$member =$this->memberObj->display($result['id']);
			$city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
            $memberModuleList = $this->memberModulePermission->getMemberModuleListByMemberId($result['id']);
			if($result['success']==false){
			    return view('administrator.members.create',compact('state','member','description','city','moduleList','memberModuleList'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
				return view('administrator.members.create',compact('state','member','description','city','moduleList','memberModuleList'));
			}
		}else{
			if($result['success']==false){
			    return view('administrator.members.create',compact('state','description','city','moduleList','memberModuleList'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.members.create',compact('state','description','city','moduleList','memberModuleList'));
			}
		}
	}
  
    public function create()
	{
		if (!Gate::allows('member_add')){
           return abort(404);
        }
    	$state = $this->stateObj->getStateDropdown();
    	$description = $this->descriptionObj->checkboxList();
    	$city = array();
        $moduleList = $this->_helper->getModuleList();
        $memberModuleList = array();
    	return view('administrator.members.create',compact('state','description','city','moduleList','memberModuleList'));
	}
    
	public function edit($id)
	{ 

	   if (!Gate::allows('member_edit')){
           return abort(404);
       }

	   $member =$this->memberObj->display($id);
	   $state = $this->stateObj->getStateDropdown();
	   $description = $this->descriptionObj->checkboxList();
	   $city = $this->cityObj->getCityDropdownByCityId($member->city_id);
       $moduleList = $this->_helper->getModuleList();
       $memberModuleList = $this->memberModulePermission->getMemberModuleListByMemberId($id);
       return view('administrator.members.create',compact('member','state','description','city','moduleList','memberModuleList'));
    }

    public function destroy($id)
	{
 		if (!Gate::allows('member_delete')){
           return abort(404);
        }
		$isdelete =$this->memberObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.members.index')->with('success','Member Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.members.index')->with('error','Member Is Not Deleted Successfully.');
		}
    }
}
