<?php
namespace App\Http\Controllers\Administrator\ShowcaseOrganization;

use Auth;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization;
use App\Classes\Models\Members\Members;
use App\Classes\Models\Images\Images;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Helpers\ShowcaseOrganization\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{
 
	protected $showcaseOrganizationObj;
	protected $imagesObj;
	protected $stateObj;
	protected $memberObj;
	protected $cityObj;
	protected $_helper;
	
	public function __construct(ShowcaseOrganization $showcaseOrganization)
	{	
        $this->showcaseOrganizationObj = $showcaseOrganization;
	  	$this->stateObj = new State();
		$this->imagesObj = new Images();
		$this->memberObj = new Members();
		$this->cityObj = new City();
		$this->_helper = new Helper();
		
    }
  
    public function index(Request $request)
	{
		if (!Gate::allows('showcase_organizations')){
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
		$showcaseOrganizations = $this->showcaseOrganizationObj->list($search,$page);
		$totalRecordCount= $this->showcaseOrganizationObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->showcaseOrganizationObj->preparePagination($totalRecordCount,$basePath);
        $entity = $this->showcaseOrganizationObj->getEntity();
		return view('administrator.showcase_organization.organization.index',compact('showcaseOrganizations','paging','entity'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->showcaseOrganizationObj->saveRecord($data);
	
	    $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
	    $city = array();

		if(isset($result['id'])){
			$showcaseOrganization =$this->showcaseOrganizationObj->load($result['id']);
			$city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
			if($result['success']==false){
			    return view('administrator.showcase_organization.organization.create',compact('showcaseOrganization','state','member','images','city'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.showcase_organization.organization.create',compact('showcaseOrganization','state','member','images','city'));
			}
		}else{ 
			if($result['success']==false){
				return view('administrator.showcase_organization.organization.create',compact('state','member','images','city'))->withErrors($result['message']);
			
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.showcase_organization.organization.create',compact('state','member','images','city'));
				}
			}
		}
  
    public function create(){

		if (!Gate::allows('showcase_organization_add')){
           return abort(404);
        }
    	$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
	    $city = array();
       return view('administrator.showcase_organization.organization.create',compact('state','member','images','city'));
	}
    
	public function edit($id){ 

		if (!Gate::allows('showcase_organization_edit')){
           return abort(404);
        }
	   $showcaseOrganization = $this->showcaseOrganizationObj->display($id);
	   $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
       $state = $this->stateObj->getStateDropdown();
	   $member = $this->memberObj->getMembersDropdown();
	   $city = $this->cityObj->getCityDropdownByCityId($showcaseOrganization->city_id);

	   return view('administrator.showcase_organization.organization.create',compact('showcaseOrganization','state','member','images','city'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('showcase_organization_delete')){
           return abort(404);
        }
		$isdelete =$this->showcaseOrganizationObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.showcase_organization.index')->with('success','Showcase Organization Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.showcase_organization.index')->with('error','Showcase Organization Is Not Deleted Successfully');
		}
    }
    public function duplicate($id){
        $event =$this->showcaseOrganizationObj->display($id);
        $data = $event->toArray();
        unset($data['showcase_organization_id']);
        $data['url_key'] = $this->duplicateRecord($data);
        $this->showcaseOrganizationObj->saveRecord($data);
        return redirect()->route('administrator.showcase_organization.index')->with('success','Showcase Organization duplicate created successfully.');
    }

    function duplicateRecord($data){
        $data['url_key'] = $this->showcaseOrganizationObj->generateDuplidateUrlKey($data['url_key']);
        $result = $this->showcaseOrganizationObj->checkDuplicateUrlKey($data['url_key']);
        if($result == 1 || $result == '1'){
            $data['url_key'] = $this->duplicateRecord($data);
        }
        return $data['url_key'];
    }
}
