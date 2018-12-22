<?php
namespace App\Http\Controllers\Administrator\Tryout\AgeGroup\Position;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Helpers\AgeGroupPosition\Helper;
use Illuminate\Support\Facades\Gate;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\Tryout\Tryout;
use App\Classes\Models\AgeGroupPosition\AgeGroupPosition;
use App\Classes\Models\Position\Position;
use App\Classes\Models\Team\Team;

class IndexController extends Controller{

  	protected $tryoutObj;
	protected $ageGroupPositionObj;
	protected $agegroupObj;
	protected $teamObj;
	protected $_helper;

	public function __construct(AgeGroupPosition $ageGroupPosition){

		$this->ageGroupPositionObj = $ageGroupPosition;
	    $this->tryoutObj = new Tryout();
	    $this->agegroupObj = new AgeGroup();
	    $this->positionObj = new Position();
	    $this->teamObj = new Team();
		$this->_helper = new Helper();
  }

  public function index(Request $request, $age_group_id, $tryout_id){		

		if (!Gate::allows('tryout_agegroup_positions')){ return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
    	
    	$tryout =  $this->tryoutObj->getTryoutNameById($tryout_id);
	    $team_name = $this->teamObj->getTeamNameById($tryout->team_id);

    	$ageGroupPosition = $this->ageGroupPositionObj->list($search, $page, $age_group_id, $tryout_id);
		$totalRecordCount= $this->ageGroupPositionObj->listTotalCount($search, $age_group_id, $tryout_id);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->ageGroupPositionObj->preparePagination($totalRecordCount,$basePath);
		return view('administrator.tryout.agegroup.position.index',compact('ageGroupPosition','paging','age_group_id','tryout_id','team_name'));
  }

  public function save(Request $request, $age_group_id, $tryout_id){		

		$submitData = $request->all();
		$data = $submitData;

		$result = $this->ageGroupPositionObj->saveRecord($data, $age_group_id, $tryout_id);
    	$position = $this->positionObj->getPositionDropDownByModuleId($this->_helper->getModuleId());
	    
		if(isset($result['id'])){
      
      	$ageGroupPosition =$this->ageGroupPositionObj->display($result['id']);

			if($result['success']==false){
			    return view('administrator.tryout.agegroup.position.create',compact('ageGroupPosition','position','age_group_id','tryout_id'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.tryout.agegroup.position.create',compact('tryout','position','age_group_id','tryout_id'));
			}
		}else{
			if($result['success']==false){
				return view('administrator.tryout.agegroup.position.create',compact('position','age_group_id','tryout_id'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.tryout.agegroup.position.create',compact('position','age_group_id','tryout_id'));
				}
			}
		}

  	public function create($age_group_id, $tryout_id){
		
		if (!Gate::allows('tryout_agegroup_position_add')){ return abort(404); }

    	$position = $this->positionObj->getPositionDropDownByModuleId($this->_helper->getModuleId());
	  return view('administrator.tryout.agegroup.position.create',compact('position','age_group_id','tryout_id'));
	   
	}

	public function edit($id, $age_group_id, $tryout_id){

		if (!Gate::allows('tryout_agegroup_position_edit')){ return abort(404); }

	 	$ageGroupPosition =$this->ageGroupPositionObj->display($id);
	    $position = $this->positionObj->getPositionDropDownByModuleId($this->_helper->getModuleId());

	    return view('administrator.tryout.agegroup.position.create',compact('ageGroupPosition','position','age_group_id','tryout_id'));
    }

  public function destroy($id, $age_group_id, $tryout_id){	
    	
		if (!Gate::allows('tryout_agegroup_position_delete')){ return abort(404); }
        
		$isdelete =$this->ageGroupPositionObj->removed($id);
		if($isdelete){
			return redirect('/administrator/agegroup_position/'.$age_group_id.'/'.$tryout_id)->with('success','Position Deleted.');
		}else{
			 return redirect('/administrator/agegroup_position/'.$age_group_id.'/'.$tryout_id)->with('error','Position Is Not deleted.');
		}
  }
}

