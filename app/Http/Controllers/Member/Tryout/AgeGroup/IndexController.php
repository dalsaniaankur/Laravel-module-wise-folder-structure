<?php
namespace App\Http\Controllers\Member\Tryout\AgeGroup;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Helpers\AgeGroup\Helper;
use Illuminate\Support\Facades\Gate;
use App\Classes\Models\Tryout\Tryout;
use App\Classes\Models\Team\Team;

class IndexController extends Controller{

    protected $tryoutObj;
    protected $agegroupObj;
    protected $teamObj;
    protected $_helper;

    public function __construct(AgeGroup $ageGroup){

        $this->agegroupObj = $ageGroup;
        $this->tryoutObj = new Tryout();
        $this->teamObj = new Team();
        $this->_helper = new Helper();
    }

    public function index(Request $request, $tryout_id){

        if (!Gate::allows('member_tryouts')){ return abort(404); }

        $page=0;
        $search='';
        if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
        $ageGroupCsv  = $this->tryoutObj->getAgeGroupCsv($tryout_id);
        $ageGroupCsvArray = $this->tryoutObj->getCSVToArray($ageGroupCsv->age_group_id);
        $agegroup = $this->agegroupObj->list($search,$page,$ageGroupCsvArray, $status = 1);
        $tryout =  $this->tryoutObj->getTryoutNameById($tryout_id);

        $tryout_name = !empty($tryout->tryout_name) ? $tryout->tryout_name : '';
        $team_name = $this->teamObj->getTeamNameById($tryout->team_id);

        $totalRecordCount= $this->agegroupObj->listTotalCount($search, $ageGroupCsvArray, $status = 1);
        $basePath=\Request::url().'?search='.$search.'&';
        $paging=$this->agegroupObj->preparePagination($totalRecordCount,$basePath);
        return view('member.tryout.agegroup.index',compact('agegroup','paging','tryout_id','tryout_name','team_name'));
    }
}