<?php
namespace App\Http\Controllers\Administrator\CampOrClinic;

use Auth;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization;
use App\Classes\Models\ShowcaseOrganization\CampOrClinic;
use App\Administrator\Administrator;
use App\Classes\Models\Members\Members;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Helpers\CampOrClinic\Helper;
use App\Classes\Models\Services\Services;
use Illuminate\Support\Facades\Gate;
use App\Classes\Models\ShowcaseOrganization\Typeofcamporclinic;

class IndexController extends Controller{
 
	protected $campOrClinicOb;
	protected $showcaseOrganizationObj;
	protected $typeofcamporclinicObj;
	protected $Images;
	protected $showcase_organizations;
	protected $camporclinicservices;
	protected $typeofcamporclinic;
	protected $stateObj;
	protected $memberObj;
	protected $servicesObj;
	protected $agegroupObj;
	protected $campOrClinicServicesObj;
	protected $cityObj;

	protected $_helper;

	public function __construct(CampOrClinic $campOrClinic)
	{	
        $this->campOrClinicOb = $campOrClinic;
        $this->stateObj = new State();
		$this->memberObj = new Members();
		$this->servicesObj = new Services();
		$this->agegroupObj = new AgeGroup();
        $this->showcaseOrganizationObj = new ShowcaseOrganization();
        $this->typeofcamporclinicObj = new Typeofcamporclinic();
        $this->cityObj = new City();
        $this->_helper = new Helper();
	}
  
    public function index(Request $request)
	{
		if (!Gate::allows('camp_or_clinic')){ return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$campOrClinics = $this->campOrClinicOb->list($search,$page);
		$totalRecordCount= $this->campOrClinicOb->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->campOrClinicOb->preparePagination($totalRecordCount,$basePath);
		
		return view('administrator.showcase_organization.camporclinic.index',compact('campOrClinics','paging'));
    }

	public function save(Request $request){

        $submitData = $request->all();
        $data = $submitData;
        $result = $this->campOrClinicOb->saveRecord($data);
		
		$types=$this->_helper->getTypes();
		$boys_or_girls=$this->_helper->getBoysOrGirls();
		$showcase_organizations = $this->showcaseOrganizationObj->getAllShowcaseOrganizationDropDown();
		$typeofcamporclinic = $this->typeofcamporclinicObj->getAllTypeOfCampClinicCheckBoxList();
	    $state   = $this->stateObj->getStateDropdown();
	    $member  = $this->memberObj->getMembersDropdown();
	    $services = $this->servicesObj->getServicesCheckboxListByModuleId($this->_helper->getModuleId());
	    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
		$city = array();

		if(isset($result['id'])){
			
			$campOrClinic = $this->campOrClinicOb->display($result['id']);
			$city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
			if($result['success']==false){
			    return view('administrator.showcase_organization.camporclinic.create',compact('campOrClinic','types','showcase_organizations','services','typeofcamporclinic','boys_or_girls','agegroup','state','member','city'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.showcase_organization.camporclinic.create',compact('campOrClinic','types','showcase_organizations','services','typeofcamporclinic','boys_or_girls','agegroup','state','member','city'));
			}
		}else{ 
			if($result['success']==false){
				return view('administrator.showcase_organization.camporclinic.create',compact('campOrClinic','types','showcase_organizations','services','typeofcamporclinic','boys_or_girls','agegroup','state','member','city'))->withErrors($result['message']);
			
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.showcase_organization.camporclinic.create',compact('campOrClinic','types','showcase_organizations','services','typeofcamporclinic','boys_or_girls','agegroup','state','member','city'));
				}
			}
		}
  
    public function create()
	{
		if (!Gate::allows('camp_or_clinic_add')){ return abort(404); }

    	$campOrClinic = new \stdClass();
    	$campOrClinic->type = $this->_helper->getDefaultType();
		$types=$this->_helper->getTypes();
		$boys_or_girls=$this->_helper->getBoysOrGirls();
 		$typeofcamporclinic = $this->typeofcamporclinicObj->getAllTypeOfCampClinicCheckBoxList();
		$showcase_organizations = $this->showcaseOrganizationObj->getAllShowcaseOrganizationDropDown();
	    $state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
	    $services = $this->servicesObj->getServicesCheckboxListByModuleId($this->_helper->getModuleId());
	    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
	    $city = array();
	
	   return view('administrator.showcase_organization.camporclinic.create',compact('campOrClinic','types','showcase_organizations','services','typeofcamporclinic','boys_or_girls','agegroup','state','member','city'));
	}
    
	public function edit($id){ 
	    
		if (!Gate::allows('camp_or_clinic_edit'))
		{
           return abort(404);
        }
	    $campOrClinic = $this->campOrClinicOb->display($id);
	    $types=$this->_helper->getTypes();
		$boys_or_girls=$this->_helper->getBoysOrGirls();
 		$typeofcamporclinic = $this->typeofcamporclinicObj->getAllTypeOfCampClinicCheckBoxList();
		$showcase_organizations = $this->showcaseOrganizationObj->getAllShowcaseOrganizationDropDown();
	    $state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
	    $services = $this->servicesObj->getServicesCheckboxListByModuleId($this->_helper->getModuleId());
	    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
	    $city = $this->cityObj->getCityDropdownByCityId($campOrClinic->city_id);
	
	    return view('administrator.showcase_organization.camporclinic.create',compact('campOrClinic','types','showcase_organizations','services','typeofcamporclinic','boys_or_girls','agegroup','state','member','city'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('camp_or_clinic_delete')) { return abort(404); }
		
		$isdelete =$this->campOrClinicOb->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.camp_or_clinic.index')->with('success','Camp Or Clinic is Deleted.');
		}else{
			 return redirect()->route('administrator.camp_or_clinic.index')->with('error','Camp Or Clinic is Not deleted.');
		}
    }
    public function duplicate($id){
        $event =$this->campOrClinicOb->display($id);
        $data = $event->toArray();
        unset($data['camp_clinic_id']);
        $data['url_key'] = $this->duplicateRecord($data);
        $this->campOrClinicOb->saveRecord($data);

        return redirect()->route('administrator.camp_or_clinic.index')->with('success','Camp Or Clinic duplicate created successfully.');
    }

    function duplicateRecord($data){
        $data['url_key'] = $this->campOrClinicOb->generateDuplidateUrlKey($data['url_key']);
        $result = $this->campOrClinicOb->checkDuplicateUrlKey($data['url_key']);
        if($result == 1 || $result == '1'){
            $data['url_key'] = $this->duplicateRecord($data);
        }
        return $data['url_key'];
    }
}
