<?php
namespace App\Http\Controllers\Administrator\ShowcaseAgeGroup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Helpers\ShowcaseOrProspect\Helper as ShowcaseOrProspectHelper;
use App\Classes\Helpers\AgeGroup\Helper as AgeGroupHelper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{
  
	protected $ageGroupObj;
	protected $_showcaseOrProspectHelper;
	protected $_ageGroupHelper;

	public function __construct(AgeGroup $ageGroup)
	{	
        $this->ageGroupObj = $ageGroup;
        $this->_showcaseOrProspectHelper = new ShowcaseOrProspectHelper();
        $this->_ageGroupHelper = new AgeGroupHelper();
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
        $module_id = $this->_showcaseOrProspectHelper->getModuleId();
		$ageGroups = $this->ageGroupObj->list( $search, $page, $age_group_id = '', $module_id);
		$totalRecordCount = $this->ageGroupObj->listTotalCount($search, $age_group_id = '', $module_id);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->ageGroupObj->preparePagination($totalRecordCount,$basePath);

		return view('administrator.showcase_age_groups.index',compact('ageGroups','paging'));
    }

	public function save(Request $request)
	{

        $data = $request->all();
        $result = $this->ageGroupObj->saveRecord($data);
        $statusList = $this->_ageGroupHelper->getStatusDropdown();
        $module_id = $this->_showcaseOrProspectHelper->getModuleId();

		if(isset($result['id']))
		{
			$ageGroup =$this->ageGroupObj->display($result['id']);
			if($result['success']==false){
			    return view('administrator.showcase_age_groups.create',compact('statusList','ageGroup','module_id'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.showcase_age_groups.create',compact('statusList','ageGroup','module_id'));
			}
		}else{
			if($result['success']==false){
			    return view('administrator.showcase_age_groups.create',compact('statusList','module_id'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.showcase_age_groups.create',compact('statusList','module_id'));
			}
		}
	}
  
    public function create()
	{
        if (!Gate::allows('showcase_or_prospect')){ return abort(404); }

        $statusList = $this->_ageGroupHelper->getStatusDropdown();
        $module_id = $this->_showcaseOrProspectHelper->getModuleId();
    	return view('administrator.showcase_age_groups.create',compact('statusList','module_id'));
	}
    
	public function edit($id)
	{
       if (!Gate::allows('showcase_or_prospect')){ return abort(404); }

	   $ageGroup = $this->ageGroupObj->display($id);
       $statusList = $this->_ageGroupHelper->getStatusDropdown();
       $module_id = $this->_showcaseOrProspectHelper->getModuleId();
	   return view('administrator.showcase_age_groups.create',compact('ageGroup','statusList','module_id'));
    }

    public function destroy($id)
	{
        if (!Gate::allows('showcase_or_prospect')){ return abort(404); }

		$isdelete =$this->ageGroupObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.showcase_age_groups.index')->with('success','Age Group Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.showcase_age_groups.index')->with('error','Age Group Is Not Deleted Successfully.');
		}
    }
}
