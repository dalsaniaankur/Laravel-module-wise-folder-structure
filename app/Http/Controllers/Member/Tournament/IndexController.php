<?php
namespace App\Http\Controllers\Member\Tournament;

use Auth;
use App\Classes\Models\Tournament\Tournament;
use App\Classes\Models\Members\Members;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\State\State;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Helpers\Tournament\Helper;
use Illuminate\Support\Facades\Gate;
use App\Classes\Models\TournamentOrganization\TournamentOrganization;
use App\Classes\Models\City\City;
use App\Classes\Models\AgeGroupEntryFee\AgeGroupEntryFee;

class IndexController extends Controller{

    protected $tournamentObj;
    protected $tournamentOrganizationObj;
    protected $stateObj;
    protected $memberObj;
    protected $agegroupObj;
    protected $cityObj;
    protected $ageGroupEntryFee;
    protected $_helper;

    public function __construct(Tournament $tournament)
    {
        $this->tournamentObj = $tournament;
        $this->stateObj = new State();
        $this->memberObj = new Members();
        $this->agegroupObj = new AgeGroup();
        $this->tournamentOrganizationObj = new TournamentOrganization();
        $this->cityObj = new City();
        $this->ageGroupEntryFee = new AgeGroupEntryFee();
        $this->_helper = new Helper();
    }

    public function index(Request $request, $tournament_organization_id){

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
        $tournament = $this->tournamentObj->list($search,$page, $tournament_organization_id, $state_id=0, $city_id =0, $redius=0, $isFront=0, $latitude ='', $longitude='', $age_group_id = array(), $competition_level_id = array(), $entry_fee='', $start_date='', $end_date='',$field_surface='', $tournament_name='', $sortedBy='', $sortedOrder='', $per_page=0, $selectColoumn=array('*'), $submitted_by_id);
        $totalRecordCount= $this->tournamentObj->listTotalCount($search, $tournament_organization_id, $state_id=0, $city_id =0, $redius=0, $isFront=0, $latitude ='', $longitude='', $age_group_id = array(), $competition_level_id = array(), $entry_fee='', $start_date='', $end_date='',$field_surface='', $tournament_name='', $sortedBy='', $sortedOrder='', $submitted_by_id );
        $basePath=\Request::url().'?search='.$search.'&';
        $paging=$this->tournamentObj->preparePagination($totalRecordCount,$basePath);
        $entity = $this->tournamentObj->getEntity();

        return view('member.tournament.index',compact('tournament','paging','tournament_organization_id','entity'));
    }

    public function save(Request $request, $tournament_organization_id){

        $submitData = $request->all();
        $data = $submitData;

        $data['start_date'] = '';
        $data['end_date'] = '';

        if(!empty($data['dates'])) {
            $data['start_date'] = $this->tournamentObj->convertDatesToStartDate($data['dates']);
            $data['end_date'] = $this->tournamentObj->convertDatesToEndDate($data['dates']);
            unset($data['dates']);
        }

        $result = $this->tournamentObj->saveRecord($data,$tournament_organization_id);
        $state = $this->stateObj->getStateDropdown();
        $tournamentOrganization = $this->tournamentOrganizationObj->getTournamentOrganizationDropdown();
        $submitted_by_id = $this->tournamentOrganizationObj->isLoginMember();
        $competition_Level_list = $this->_helper->getCompetitionLevellist();
        $field_surface_list = $this->_helper->getFieldSurface();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $city = array();
        $ageGroupEntryFee = array();
        $guaranteedGamesList = $this->_helper->getGuaranteedGamesList();

        if(isset($result['id'])){

            $tournament =$this->tournamentObj->display($result['id']);
            $city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);

            if(!empty($result['id']) && $result['id'] > 0) {
                $ageGroupEntryFee = $this->ageGroupEntryFee->getAgeGroupEntryFeeByTournamentId($result['id']);
            }

            if($result['success']==false){
                return view('member.tournament.create',compact('tournament','tournament_organization_id','state','submitted_by_id','agegroup','field_surface_list','competition_Level_list','tournamentOrganization','city','ageGroupEntryFee','guaranteedGamesList'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('member.tournament.create',compact('tournament','tournament_organization_id','state','submitted_by_id','agegroup','field_surface_list','competition_Level_list','tournamentOrganization','city','ageGroupEntryFee','guaranteedGamesList'));
            }
        }else{
            if($result['success']==false){
                return view('member.tournament.create',compact('tournament_organization_id','state','member','agegroup','field_surface_list','competition_Level_list','tournamentOrganization','city','ageGroupEntryFee','submitted_by_id','guaranteedGamesList'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('member.tournament.create',compact('tournament_organization_id','state','member','agegroup','field_surface_list','competition_Level_list','tournamentOrganization','city','ageGroupEntryFee','submitted_by_id','guaranteedGamesList'));
            }
        }
    }

    public function create($tournament_organization_id){

        if (!Gate::allows('member_tournaments')){ return abort(404); }

        $state = $this->stateObj->getStateDropdown();
        $tournamentOrganization = $this->tournamentOrganizationObj->getTournamentOrganizationDropdown();
        $submitted_by_id = $this->tournamentOrganizationObj->isLoginMember();
        $competition_Level_list = $this->_helper->getCompetitionLevellist();
        $field_surface_list = $this->_helper->getFieldSurface();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $city = array();
        $guaranteedGamesList = $this->_helper->getGuaranteedGamesList();

        return view('member.tournament.create',compact('tournament_organization_id','state','submitted_by_id','agegroup','field_surface_list','competition_Level_list','tournamentOrganization','city','guaranteedGamesList'));

    }

    public function edit($id, $tournament_organization_id){

        if (!Gate::allows('member_tournaments')){ return abort(404); }

        $tournament =$this->tournamentObj->display($id);
        $tournamentOrganization = $this->tournamentOrganizationObj->getTournamentOrganizationDropdown();
        $state = $this->stateObj->getStateDropdown();
        $submitted_by_id = $this->tournamentOrganizationObj->isLoginMember();
        $competition_Level_list = $this->_helper->getCompetitionLevellist();
        $field_surface_list = $this->_helper->getFieldSurface();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $city = $this->cityObj->getCityDropdownByCityId($tournament->city_id);
        $ageGroupEntryFee = $this->ageGroupEntryFee->getAgeGroupEntryFeeByTournamentId($id);
        $guaranteedGamesList = $this->_helper->getGuaranteedGamesList();

        return view('member.tournament.create',compact('tournament','tournament_organization_id','state','submitted_by_id','agegroup','field_surface_list','competition_Level_list','tournamentOrganization','city','ageGroupEntryFee','guaranteedGamesList'));
    }

    public function destroy($id, $tournament_organization_id){

        if (!Gate::allows('member_tournaments')){ return abort(404); }

        $isdelete =$this->tournamentObj->removed($id);
        if($isdelete){
            return redirect('/member/tournament/'.$tournament_organization_id)->with('success','Tournament Deleted.');
        }else{
            return redirect('/member/tournament/'.$tournament_organization_id)->with('error','Tournament Is Not deleted.');
        }
    }
}
