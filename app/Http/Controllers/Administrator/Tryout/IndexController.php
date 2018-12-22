<?php
namespace App\Http\Controllers\Administrator\Tryout;

use Auth;
use App\Classes\Models\Tryout\Tryout;
use App\Classes\Models\Members\Members;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\State\State;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Helpers\Tryout\Helper;
use Illuminate\Support\Facades\Gate;
use App\Classes\Models\Team\Team;
use App\Classes\Models\City\City;
use App\Classes\Models\Position\Position;
use App\Classes\Helpers\AgeGroupPosition\Helper as AgeGroupPositionHelper;
use App\Classes\Models\AgeGroupPosition\AgeGroupPosition;
use App\Classes\Models\TryoutDate\TryoutDate;

class IndexController extends Controller{

    protected $tryoutObj;
    protected $stateObj;
    protected $memberObj;
    protected $agegroupObj;
    protected $teamObj;
    protected $cityObj;
    protected $positionObj;
    protected $_helper;
    protected $_ageGroupPositionHelper;
    protected $ageGroupPositionObj;
    protected $tryoutDateObj;

    public function __construct(Tryout $tryout){

        $this->tryoutObj = $tryout;
        $this->stateObj = new State();
        $this->memberObj = new Members();
        $this->agegroupObj = new AgeGroup();
        $this->teamObj = new Team();
        $this->cityObj = new City();
        $this->positionObj = new Position();
        $this->_helper = new Helper();
        $this->_ageGroupPositionHelper = new AgeGroupPositionHelper();
        $this->ageGroupPositionObj = new AgeGroupPosition();
        $this->tryoutDateObj = new TryoutDate();
    }

    public function index(Request $request, $team_id){

        if (!Gate::allows('tryouts')){ return abort(404); }

        $page=0;
        $search='';
        if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }

        $tryout = $this->tryoutObj->list($search,$page,$team_id);
        $totalRecordCount= $this->tryoutObj->listTotalCount($search, $team_id);
        $basePath=\Request::url().'?search='.$search.'&';
        $paging=$this->tryoutObj->preparePagination($totalRecordCount,$basePath);
        $entity = $this->tryoutObj->getEntity();
        return view('administrator.tryout.index',compact('tryout','paging','team_id', 'entity'));
    }

    public function save(Request $request, $team_id){

        $data = $request->all();

        $result = $this->tryoutObj->saveRecord($data,$team_id);
        $state = $this->stateObj->getStateDropdown();
        $member = $this->memberObj->getMembersDropdown();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $team = $this->teamObj->getTeamDropdown();
        $city = array();
        $tryoutDate="";

        $positionList = $this->positionObj->getPositionDropDownByModuleId($this->_ageGroupPositionHelper->getModuleId());

        if(isset($result['id'])){

            $tryout =$this->tryoutObj->display($result['id']);
            $city = $this->cityObj->getCityDropdownByCityId($data['city_id']);
            $tryoutDate = $this->tryoutDateObj->getDateList($result['id']);

            if(!empty($tryout->age_group_id)) {
                foreach ($tryout->age_group_id as $age_group_id) {
                    $key = 'age_group_position_' . $age_group_id;
                    $tryout->{$key} = $this->ageGroupPositionObj->getSelectedAgeGroupPosition($result['id'], $age_group_id);
                }
            }

            if($result['success']==false){
                return view('administrator.tryout.create',compact('tryout','team_id','state','member','agegroup','team','city','positionList','tryoutDate'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('administrator.tryout.create',compact('tryout','team_id','state','member','agegroup','team','city','positionList','tryoutDate'));
            }
        }else{
            if($result['success']==false){
                return view('administrator.tryout.create',compact('team_id','state','member','agegroup','team','city','positionList','tryoutDate'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('administrator.tryout.create',compact('team_id','state','member','agegroup','team','city','positionList','tryoutDate'));
            }
        }
    }

    public function create($team_id){

        if (!Gate::allows('tryout_add')){ return abort(404); }

        $team = $this->teamObj->getTeamDropdown();
        $state = $this->stateObj->getStateDropdown();
        $member = $this->memberObj->getMembersDropdown();
        $competition_Level_list = $this->_helper->getCompetitionLevellist();
        $field_surface_list = $this->_helper->getFieldSurface();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $city = array();
        $positionList = $this->positionObj->getPositionDropDownByModuleId($this->_ageGroupPositionHelper->getModuleId());
        $tryoutDate = '';

        return view('administrator.tryout.create',compact('team_id','state','member','agegroup','field_surface_list','competition_Level_list','team','city','positionList','tryoutDate'));

    }

    public function edit($id, $team_id){

        if (!Gate::allows('tryout_edit')){ return abort(404); }

        $team = $this->teamObj->getTeamDropdown();
        $tryout =$this->tryoutObj->display($id);
        $state = $this->stateObj->getStateDropdown();
        $member = $this->memberObj->getMembersDropdown();
        $agegroup = $this->agegroupObj->getAgeGroupCheckboxListByModuleId($this->_helper->getModuleId());
        $city = $this->cityObj->getCityDropdownByCityId($tryout->city_id);
        $tryoutDate = $this->tryoutDateObj->getDateList($id);

        if(!empty($tryout->age_group_id)) {
            foreach ($tryout->age_group_id as $age_group_id) {
                $key = 'age_group_position_' . $age_group_id;
                $tryout->{$key} = $this->ageGroupPositionObj->getSelectedAgeGroupPosition($id, $age_group_id);
            }
        }
        $positionList = $this->positionObj->getPositionDropDownByModuleId($this->_ageGroupPositionHelper->getModuleId());

        return view('administrator.tryout.create',compact('tryout','team_id','state','member','agegroup','team','city','positionList','tryoutDate'));
    }

    public function destroy($id, $team_id){

        if (!Gate::allows('tryout_delete')){ return abort(404); }

        $isdelete =$this->tryoutObj->removed($id);
        if($isdelete){
            return redirect('/administrator/tryout/'.$team_id)->with('success','Tryout Deleted.');
        }else{
            return redirect('/administrator/tryout/'.$team_id)->with('error','Tryout Is Not deleted.');
        }
    }

    public function duplicate($id, $team_id){

        $event =$this->tryoutObj->display($id);
        $data = $event->toArray();
        unset($data['tryout_id']);
        $data['url_key'] = $this->duplicateRecord($data);
        $results = $this->tryoutObj->saveRecord($data, $team_id);

        /* Entry Fee AgeGroup Position */
        if(!empty($results['id']) && $results['id'] > 0){
            $ageGroupPosition = $this->ageGroupPositionObj->getListByTryoutId($id);

            if(!empty($ageGroupPosition) && count($ageGroupPosition) > 0){
                foreach ($ageGroupPosition as $ageGroupPositionData){
                    $this->ageGroupPositionObj::create(['age_group_id' => $ageGroupPositionData->age_group_id,
                                                            'tryout_id' => $results['id'],
                                                            'position_id' => $ageGroupPositionData->position_id,
                                                        ]);
                }
            }
        }

        /* Dates */
        if(!empty($results['id']) && $results['id'] > 0){

            $tryoutDates = $this->tryoutDateObj->getDateListByTryoutId($id);
            if(!empty($tryoutDates) && count($tryoutDates) > 0){
                $short_order = 1;
                foreach ($tryoutDates as $date){
                    $this->tryoutDateObj::create(['tryout_id' => $results['id'],'date' => $date,'sort_order' => $short_order]);
                    $short_order ++;
                }
            }
        }

        return redirect('/administrator/tryout/'.$team_id)->with('success','Tryout duplicate created successfully');
    }

    function duplicateRecord($data){
        $data['url_key'] = $this->tryoutObj->generateDuplidateUrlKey($data['url_key']);
        $result = $this->tryoutObj->checkDuplicateUrlKey($data['url_key']);
        if($result == 1 || $result == '1'){
            $data['url_key'] = $this->duplicateRecord($data);
        }
        return $data['url_key'];
    }
}
