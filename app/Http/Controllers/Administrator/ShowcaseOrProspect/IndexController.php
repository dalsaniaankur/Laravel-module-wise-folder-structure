<?php
namespace App\Http\Controllers\Administrator\ShowcaseOrProspect;

use Auth;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrProspect;
use App\Classes\Models\Members\Members;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Helpers\ShowcaseOrProspect\Helper;
use App\Classes\Models\Position\Position;
use Illuminate\Support\Facades\Gate;
use App\Classes\Models\ShowcaseDate\ShowcaseDate;

class IndexController extends Controller{
 
	protected $showcaseOrganizationObj;
	protected $positionObj;
	protected $stateObj;
	protected $memberObj;
	protected $agegroupObj;
	protected $cityObj;
	protected $showcaseOrProspectObj;
	protected $showcaseDateObj;
	protected $_helper;
	
	public function __construct(ShowcaseOrProspect $ShowcaseOrProspect)
	{	
        $this->showcaseOrProspectObj = $ShowcaseOrProspect;
        $this->stateObj = new State();
		$this->memberObj = new Members();
		$this->agegroupObj = new AgeGroup();
        $this->showcaseOrganizationObj = new ShowcaseOrganization();
   		$this->positionObj = new Position();     
   		$this->cityObj = new City();
        $this->_helper = new Helper();
        $this->showcaseDateObj = new ShowcaseDate();

    }
  
    public function index(Request $request)
	{
		if (!Gate::allows('showcase_or_prospect')){ return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$showcaseOrProspects = $this->showcaseOrProspectObj->list($search,$page);
		$totalRecordCount= $this->showcaseOrProspectObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->showcaseOrProspectObj->preparePagination($totalRecordCount,$basePath);
        $entity = $this->showcaseOrProspectObj->getEntity();
		return view('administrator.showcase_organization.showcaseorprospect.index',compact('showcaseOrProspects','paging','entity'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->showcaseOrProspectObj->saveRecord($data);

		$open_or_invites=$this->_helper->getOpenOrInvites();
		$types = $this->_helper->getTypes();
 		$showcase_organizations = $this->showcaseOrganizationObj->getAllShowcaseOrganizationDropDown();
	    $state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
	    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
		$position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
		$city = array();
        $approvalStatusList = $this->_helper->getApprovalStatusList();
        $showcaseDate = "";

        if(isset($result['id'])){
            $showcaseOrProspect = $this->showcaseOrProspectObj->display($result['id']);
			$city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
            $showcaseDate = $this->showcaseDateObj->getDateList($result['id']);

            if($result['success']==false){
                return view('administrator.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types', 'showcase_organizations','position','open_or_invites','agegroup','state','member','city','approvalStatusList','showcaseDate'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types','showcase_organizations','position','open_or_invites','agegroup','state','member','city','approvalStatusList','showcaseDate'));
			}
		}else{ 
			if($result['success']==false){
				return view('administrator.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types','showcase_organizations','position','open_or_invites','agegroup','state','member','city','approvalStatusList','showcaseDate'))->withErrors($result['message']);
			
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types','showcase_organizations','position','open_or_invites','agegroup','state','member','city','approvalStatusList','showcaseDate'));
				}
			}
		}
  
    public function create()
	{
		if (!Gate::allows('showcase_or_prospect_add')){ return abort(404); }

		$showcaseOrProspect = new \stdClass();
    	$showcaseOrProspect->type = $this->_helper->getDefaultType();
    	$open_or_invites=$this->_helper->getOpenOrInvites();
		$types = $this->_helper->getTypes();
 		$showcase_organizations = $this->showcaseOrganizationObj->getAllShowcaseOrganizationDropDown();
	    $state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
	    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
		$position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
     	$city = array();
        $approvalStatusList = $this->_helper->getApprovalStatusList();
        $showcaseDate = "";

	    return view('administrator.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types','showcase_organizations','position','open_or_invites','agegroup','state','member','city','approvalStatusList','showcaseDate'));
	}
    
	public function edit($id)
	{ 
		if (!Gate::allows('showcase_or_prospect_edit')){ return abort(404); }

	    $showcaseOrProspect = $this->showcaseOrProspectObj->display($id);
    	$open_or_invites=$this->_helper->getOpenOrInvites();
		$types = $this->_helper->getTypes();
 		$showcase_organizations = $this->showcaseOrganizationObj->getAllShowcaseOrganizationDropDown();
	    $state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
	    $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
		$position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
		$city = $this->cityObj->getCityDropdownByCityId($showcaseOrProspect->city_id);
        $approvalStatusList = $this->_helper->getApprovalStatusList();
        $showcaseDate = $this->showcaseDateObj->getDateList($id);

	    return view('administrator.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types','showcase_organizations','position','open_or_invites','agegroup','state','member','city','approvalStatusList','showcaseDate'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('showcase_or_prospect_delete')){ return abort(404); }
		
		$isdelete =$this->showcaseOrProspectObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.showcase_or_prospect.index')->with('success','Showcase Or Prospect is Deleted.');
		}else{
			 return redirect()->route('administrator.showcase_or_prospect.index')->with('error','Showcase Or Prospect is Not deleted.');
		}
    }
    public function duplicate($id){
        $event =$this->showcaseOrProspectObj->display($id);
        $data = $event->toArray();
        unset($data['showcase_or_prospect_id']);
        $data['url_key'] = $this->duplicateRecord($data);
        $results = $this->showcaseOrProspectObj->saveRecord($data);

        /* Dates */
        if(!empty($results['id']) && $results['id'] > 0){

            $tryoutDates = $this->showcaseDateObj->getDateListByShowcaseOrProspectId($id);
            if(!empty($tryoutDates) && count($tryoutDates) > 0){
                $short_order = 1;
                foreach ($tryoutDates as $date){
                    $this->showcaseDateObj::create(['showcase_or_prospect_id' => $results['id'],'date' => $date,'sort_order' => $short_order]);
                    $short_order ++;
                }
            }
        }

        return redirect()->route('administrator.showcase_or_prospect.index')->with('success','Showcase Or Prospect duplicate created successfully.');
    }

    function duplicateRecord($data){
        $data['url_key'] = $this->showcaseOrProspectObj->generateDuplidateUrlKey($data['url_key']);
        $result = $this->showcaseOrProspectObj->checkDuplicateUrlKey($data['url_key']);
        if($result == 1 || $result == '1'){
            $data['url_key'] = $this->duplicateRecord($data);
        }
        return $data['url_key'];
    }
}
