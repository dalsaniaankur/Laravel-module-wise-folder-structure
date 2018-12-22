<?php
namespace App\Http\Controllers\Member\ShowcaseOrProspect;

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
    protected $showcaseOrProspectOb;
    protected $showcaseDateObj;
    protected $_helper;

    public function __construct(ShowcaseOrProspect $ShowcaseOrProspect)
    {
        $this->showcaseOrProspectOb = $ShowcaseOrProspect;
        $this->stateObj = new State();
        $this->memberObj = new Members();
        $this->agegroupObj = new AgeGroup();
        $this->showcaseOrganizationObj = new ShowcaseOrganization();
        $this->positionObj = new Position();
        $this->cityObj = new City();
        $this->showcaseDateObj = new ShowcaseDate();
        $this->_helper = new Helper();

    }

    public function index(Request $request)
    {
        if (!Gate::allows('member_showcase')){ return abort(404); }

        $page=0;
        $search='';
        if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
        $submitted_by_id = $this->showcaseOrProspectOb->isLoginMember();
        $showcaseOrProspects = $this->showcaseOrProspectOb->list($search,$page, $type=0, $state_id=0, $city_id=0, $redius=0, $isFront=0, $latitude='', $longitude='', $showcase_organization_id=0, $open_or_invite='', $age_group_id=array(), $position_id=array(), $start_date='', $end_date='', $name='', $sortedBy='', $sortedOrder='',$is_active=2, $per_page=0, $selectColoumn=array('*'), $submitted_by_id);
        $totalRecordCount= $this->showcaseOrProspectOb->listTotalCount($search,$page, $type=0, $state_id=0, $city_id=0, $redius=0, $isFront=0, $latitude='', $longitude='', $showcase_organization_id=0, $open_or_invite='', $age_group_id=array(), $position_id=array(), $start_date='', $end_date='', $name='', $sortedBy='', $sortedOrder='',$is_active=2, $submitted_by_id);
        $basePath=\Request::url().'?search='.$search.'&';
        $paging=$this->showcaseOrProspectOb->preparePagination($totalRecordCount,$basePath);
        $entity = $this->showcaseOrProspectOb->getEntity();

        return view('member.showcase_organization.showcaseorprospect.index',compact('showcaseOrProspects','paging','entity'));
    }

    public function save(Request $request)
    {
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->showcaseOrProspectOb->saveRecord($data);

        $open_or_invites=$this->_helper->getOpenOrInvites();
        $types = $this->_helper->getTypes();
        $showcase_organizations = $this->showcaseOrganizationObj->getAllShowcaseOrganizationDropDown();
        $state = $this->stateObj->getStateDropdown();
        $submitted_by_id = $this->showcaseOrProspectOb->isLoginMember();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
        $city = array();
        $showcaseDate = "";

        if(isset($result['id'])){

            $showcaseOrProspect = $this->showcaseOrProspectOb->display($result['id']);
            $city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
            $showcaseDate = $this->showcaseDateObj->getDateList($result['id']);
            if($result['success']==false){
                return view('member.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types','showcase_organizations','position','open_or_invites','agegroup','state','submitted_by_id','city','showcaseDate'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('member.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types','showcase_organizations','position','open_or_invites','agegroup','state','submitted_by_id','city','showcaseDate'));
            }
        }else{
            if($result['success']==false){
                return view('member.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types','showcase_organizations','position','open_or_invites','agegroup','state','submitted_by_id','city','showcaseDate'))->withErrors($result['message']);

            }else{
                $request->session()->flash('success', $result['message']);
                return view('member.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types','showcase_organizations','position','open_or_invites','agegroup','state','submitted_by_id','city','showcaseDate'));
            }
        }
    }

    public function create()
    {
        if (!Gate::allows('member_showcase')){ return abort(404); }

        $showcaseOrProspect = new \stdClass();
        $showcaseOrProspect->type = $this->_helper->getDefaultType();
        $open_or_invites=$this->_helper->getOpenOrInvites();
        $types = $this->_helper->getTypes();
        $showcase_organizations = $this->showcaseOrganizationObj->getAllShowcaseOrganizationDropDown();
        $state = $this->stateObj->getStateDropdown();
        $submitted_by_id = $this->showcaseOrProspectOb->isLoginMember();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
        $city = array();
        $defaultApprovalStatus = $this->_helper->getDefaultApprovalStatus();
        $showcaseDate = "";

        return view('member.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types','showcase_organizations','position','open_or_invites','agegroup','state','submitted_by_id','city','defaultApprovalStatus','showcaseDate'));
    }

    public function edit($id)
    {
        if (!Gate::allows('member_showcase')){ return abort(404); }

        $showcaseOrProspect = $this->showcaseOrProspectOb->display($id);
        $open_or_invites=$this->_helper->getOpenOrInvites();
        $types = $this->_helper->getTypes();
        $showcase_organizations = $this->showcaseOrganizationObj->getAllShowcaseOrganizationDropDown();
        $state = $this->stateObj->getStateDropdown();
        $submitted_by_id = $this->showcaseOrProspectOb->isLoginMember();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $position = $this->positionObj->getPositionCheckboxListByModuleId($this->_helper->getModuleId());
        $city = $this->cityObj->getCityDropdownByCityId($showcaseOrProspect->city_id);
        $showcaseDate = $this->showcaseDateObj->getDateList($id);

        return view('member.showcase_organization.showcaseorprospect.create',compact('showcaseOrProspect','types','showcase_organizations','position','open_or_invites','agegroup','state','submitted_by_id','city','showcaseDate'));
    }

    public function destroy($id)
    {
        if (!Gate::allows('member_showcase')){ return abort(404); }

        $isdelete =$this->showcaseOrProspectOb->removed($id);
        if($isdelete){
            return redirect()->route('member.showcase_or_prospect.index')->with('success','Showcase Or Prospect Record Deleted.');
        }else{
            return redirect()->route('member.showcase_or_prospect.index')->with('error','Showcase Or Prospect Record not deleted.');
        }
    }
}
