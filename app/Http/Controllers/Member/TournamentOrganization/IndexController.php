<?php
namespace App\Http\Controllers\Member\TournamentOrganization;

use App\Classes\Models\TournamentOrganization\TournamentOrganization;
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
        if (!Gate::allows('member_tournaments')){ return abort(404); }

        $page=0;
        $search='';
        if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
        $submitted_by_id = $this->tournamentOrganizationObj->isLoginMember();
        $tournamentOrganizations = $this->tournamentOrganizationObj->list($search,$page, $submitted_by_id);
        $totalRecordCount= $this->tournamentOrganizationObj->listTotalCount($search, $submitted_by_id);
        $basePath=\Request::url().'?search='.$search.'&';
        $paging = $this->tournamentOrganizationObj->preparePagination($totalRecordCount,$basePath);
        $entity = $this->tournamentOrganizationObj->getEntity();

        return view('member.tournament_organization.index',compact('tournamentOrganizations','paging','entity'));
    }

    public function save(Request $request)
    {
        $data = $request->all();
        $result = $this->tournamentOrganizationObj->saveRecord($data);
        $show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
        $member = $this->memberObj->getMembersDropdown();
        $city = array();
        $submitted_by_id = $this->tournamentOrganizationObj->isLoginMember();
        if(isset($result['id']))
        {
            $tournamentOrganization =$this->tournamentOrganizationObj->load($result['id']);
            $city = $this->cityObj->getCityDropdownByCityId($data['city_id']);
            if($result['success']==false){
                return view('member.tournament_organization.create',compact('tournamentOrganization','state','submitted_by_id','show_advertise','images','city'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('member.tournament_organization.create',compact('tournamentOrganization','state','submitted_by_id','show_advertise','images','city'));
            }
        }else{
            if($result['success']==false){
                return view('member.tournament_organization.create',compact('state','member','show_advertise','images','city','submitted_by_id'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('member.tournament_organization.create',compact('state','member','show_advertise','images','city','submitted_by_id'));
            }
        }
    }

    public function create()
    {
        if (!Gate::allows('member_tournaments')){ return abort(404); }

        $show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
        $submitted_by_id = $this->tournamentOrganizationObj->isLoginMember();
        $city = array();

        $defaultApprovalStatus = $this->_helper->getDefaultApprovalStatus();

        return view('member.tournament_organization.create',compact('state','submitted_by_id','show_advertise','images','city','defaultApprovalStatus'));
    }

    public function edit($id)
    {
        if (!Gate::allows('member_tournaments')){ return abort(404); }

        $tournamentOrganization =$this->tournamentOrganizationObj->display($id);
        $show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
        $city = $this->cityObj->getCityDropdownByCityId($tournamentOrganization->city_id);
        $submitted_by_id = $this->tournamentOrganizationObj->isLoginMember();
        return view('member.tournament_organization.create',compact('tournamentOrganization','state','submitted_by_id','show_advertise','images','city'));
    }

    public function destroy($id)
    {
        if (!Gate::allows('member_tournaments')){ return abort(404); }

        $isdelete =$this->tournamentOrganizationObj->removed($id);
        if($isdelete){
            return redirect()->route('member.tournament_organizations.index')->with('success','Tournament Organization Deleted.');
        }else{
            return redirect()->route('member.tournament_organizations.index')->with('error','Tournament Organization Is Not deleted.');
        }
    }
}