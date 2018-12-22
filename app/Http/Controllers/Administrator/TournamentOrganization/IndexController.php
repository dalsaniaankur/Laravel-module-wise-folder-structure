<?php
namespace App\Http\Controllers\Administrator\TournamentOrganization;

use Auth;
use App\Classes\Models\TournamentOrganization\TournamentOrganization;

use App\Administrator\Administrator;
use App\Classes\Models\Members\Members;
use App\Classes\Models\Images\Images;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Helpers\TournamentOrganization\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{

	protected $tournamentOrganizationObj;
	protected $imagesObj;
	protected $stateObj;
	protected $memberObj;
	protected $agegroupObj;
	protected $cityObj;
	protected $_helper;

	public function __construct(TournamentOrganization $tournamentOrganization)
	{
    	$this->tournamentOrganizationObj = $tournamentOrganization;
    	$this->stateObj = new State();
		$this->imagesObj = new Images();
		$this->memberObj = new Members();
		$this->agegroupObj = new AgeGroup();
		$this->cityObj = new City();
		$this->_helper = new Helper();
    }

  public function index(Request $request)
  {		
  		if (!Gate::allows('tournament_organizations')){ return abort(404); }
		
		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$tournamentOrganizations = $this->tournamentOrganizationObj->list($search,$page);
		$totalRecordCount= $this->tournamentOrganizationObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->tournamentOrganizationObj->preparePagination($totalRecordCount,$basePath);
        $entity = $this->tournamentOrganizationObj->getEntity();
		return view('administrator.tournament_organization.index',compact('tournamentOrganizations','paging','entity'));
  }

  public function save(Request $request)
  {
		$submitData = $request->all();
		$data = $submitData;

		$result = $this->tournamentOrganizationObj->saveRecord($data);
		$show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
	    $city = array();
        $approvalStatusList = $this->_helper->getApprovalStatusList();
		if(isset($result['id']))
		{
			$tournamentOrganization =$this->tournamentOrganizationObj->load($result['id']);
			$city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
			if($result['success']==false){
			    return view('administrator.tournament_organization.create',compact('tournamentOrganization','state','member','show_advertise','images','city','approvalStatusList'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.tournament_organization.create',compact('tournamentOrganization','state','member','show_advertise','images','city','approvalStatusList'));
			}
		}else{
			if($result['success']==false){
				return view('administrator.tournament_organization.create',compact('state','member','show_advertise','images','city','approvalStatusList'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.tournament_organization.create',compact('state','member','show_advertise','images','city','approvalStatusList'));
				}
			}
		}

    public function create()
	{
		if (!Gate::allows('tournament_organization_add')){ return abort(404); }

      	$show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
	    $city = array();
        $approvalStatusList = $this->_helper->getApprovalStatusList();

	   return view('administrator.tournament_organization.create',compact('state','member','show_advertise','images','city','approvalStatusList'));
	}

	public function edit($id)
	{
		if (!Gate::allows('tournament_organization_edit')){ return abort(404); }

	    $tournamentOrganization =$this->tournamentOrganizationObj->display($id);
	  	$show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
	    $member = $this->memberObj->getMembersDropdown();
	    $city = $this->cityObj->getCityDropdownByCityId($tournamentOrganization->city_id);
        $approvalStatusList = $this->_helper->getApprovalStatusList();

	   return view('administrator.tournament_organization.create',compact('tournamentOrganization','state','member','show_advertise','images','city','approvalStatusList'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('tournament_organization_delete')){ return abort(404); }
		
		$isdelete =$this->tournamentOrganizationObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.tournament_organizations.index')->with('success','Tournament Organization Deleted.');
		}else{
			 return redirect()->route('administrator.tournament_organizations.index')->with('error','Tournament Organization Is Not deleted.');
		}
    }
    public function duplicate($id){
        $tournamentOrganization =$this->tournamentOrganizationObj->display($id);
        $data = $tournamentOrganization->toArray();
        unset($data['tournament_organization_id']);
        $data['url_key'] = $this->duplicateRecord($data);
        $this->tournamentOrganizationObj->saveRecord($data);
        return redirect()->route('administrator.tournament_organizations.index')->with('success', 'Tournament Organization duplicate created successfully.');
    }

    function duplicateRecord($data){
        $data['url_key'] = $this->tournamentOrganizationObj->generateDuplidateUrlKey($data['url_key']);
        $result = $this->tournamentOrganizationObj->checkDuplicateUrlKey($data['url_key']);
        if($result == 1 || $result == '1'){
            $data['url_key'] = $this->duplicateRecord($data);
        }
        return $data['url_key'];
    }
}