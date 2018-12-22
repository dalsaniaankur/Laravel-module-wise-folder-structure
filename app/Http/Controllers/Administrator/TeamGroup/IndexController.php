<?php
namespace App\Http\Controllers\Administrator\TeamGroup;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\Images\Images;
use App\Classes\Models\Team\Team;
use App\Classes\Models\Team\TeamGroup;
use App\Classes\Models\Team\TeamToGroup;
use App\Classes\Helpers\Team\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{

    protected $teamGroupObj;
    protected $teamObj;
    protected $imagesObj;
    protected $_helper;


    public function __construct(TeamGroup $teamGroup)
    {
        $this->imagesObj = new Images();
        $this->_helper = new Helper();
        $this->teamGroupObj = $teamGroup;
        $this->teamObj=new Team();
    }

    public function index(Request $request)
    {
        if (!Gate::allows('team_groups')){ return abort(404); }

        $page=0;
        $search='';
        if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
        $teamgroup = $this->teamGroupObj->list($search,$page);
        $totalRecordCount= $this->teamGroupObj->listTotalCount($search);
        $basePath=\Request::url().'?search='.$search.'&';
        $paging=$this->teamGroupObj->preparePagination($totalRecordCount,$basePath);
        return view('administrator.team.group.index',compact('teamgroup','paging'));
    }

    public function save(Request $request){

        $submitData = $request->all();
        $data = $submitData;
        $result = $this->teamGroupObj->saveRecord($data);
        $teams 		= $this->teamObj->getAllTeamDropdown();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());

        if(isset($result['id'])){
            $teamgroup = $this->teamGroupObj->display($result['id']);

            if($result['success']==false){
                return view('administrator.team.group.create',compact('teamgroup','teams','images'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('administrator.team.group.create',compact('teamgroup','teams','images'));
            }
        }else{
            if($result['success']==false){
                return view('administrator.team.group.create',compact('teamgroup','teams','images'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('administrator.team.group.create',compact('teamgroup','teams','images'));
            }
        }
    }

    public function create()
    {
        if (!Gate::allows('team_add_group')){ return abort(404); }

        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $teams = $this->teamObj->getAllTeamDropdown();
        return view('administrator.team.group.create',compact('teamgroup','teams','images'));
    }

    public function edit($id)
    {
        if (!Gate::allows('team_edit_group')){
            return abort(404);
        }
        $teamgroup = $this->teamGroupObj->display($id);
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $teams = $this->teamObj->getAllTeamDropdown();

        return view('administrator.team.group.create',compact('teamgroup','teams','images'));
    }

    public function destroy($id)
    {
        if (!Gate::allows('team_delete_group')){ return abort(404); }

        $isdelete =$this->teamGroupObj->removed($id);
        if($isdelete){
            return redirect()->route('administrator.team_group.index')->with('success','Group Deleted Successfully.');
        }else{
            return redirect()->route('administrator.team_group.index')->with('error','Group Is NOt Deleted Successfully.');
        }
    }

    public function unlinkTeam($team_group_id,$team_id)
    {

        $teamToGroupObj = new TeamToGroup();
        $isdelete=$teamToGroupObj->unlinkTeam($team_group_id,$team_id);
        if($isdelete){
            return redirect()->route('administrator.team_group.edit',[$team_group_id])->with('success','Team unlink successfully..');
        }else{
            return redirect()->route('administrator.team_group.edit',[$team_group_id])->with('error','Team is not unlink successfully..');
        }
    }
}
