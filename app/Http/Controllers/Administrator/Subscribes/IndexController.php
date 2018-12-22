<?php
namespace App\Http\Controllers\Administrator\Subscribes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Helpers\Subscribes\Helper;
use App\Classes\Models\Subscribes\Subscribes;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{
  
	protected $SubscribesObj;
	 
	public function __construct(Subscribes $subscribes){	

        $this->SubscribesObj = $subscribes;
        $this->_helper = new Helper();
    }
  
    public function index(Request $request){
    	
		if (!Gate::allows('subscribes')){ return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$subscribes = $this->SubscribesObj->list($search,$page);
		$totalRecordCount= $this->SubscribesObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->SubscribesObj->preparePagination($totalRecordCount,$basePath);
		
		return view('administrator.subscribes.index',compact('subscribes','paging'));
    }

	public function save(Request $request){
		
		$submitData = $request->all();
		$data = $submitData;
		$result = $this->SubscribesObj->saveRecord($data);

		if(isset($result['id']))
		{
			$subscribes =$this->SubscribesObj->display($result['id']);
			if($result['success']==false){
			    return view('administrator.subscribes.create',compact('subscribes'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.subscribes.create',compact('subscribes')); 
			}
		}else{
			if($result['success']==false){
			    return view('administrator.subscribes.create')->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.subscribes.create');
			}
		}
	}
    
	public function edit($id){ 

	   if (!Gate::allows('subscribes_edit')){ return abort(404); }
	   
	   $subscribes = $this->SubscribesObj->display($id);
	   return view('administrator.subscribes.create',compact('subscribes'));
    }

    public function destroy($id){

		if (!Gate::allows('subscribes_delete')){ return abort(404); }
		
		$isdelete = $this->SubscribesObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.subscribes.index')->with('success','Subscribes Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.subscribes.index')->with('error','Subscribes Is Not Deleted Successfully.');
		}
    }
}
