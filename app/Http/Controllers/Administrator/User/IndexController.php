<?php
namespace App\Http\Controllers\Administrator\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\User\User;
use App\Classes\Models\SecurityModule\SecurityModule;
use App\Classes\Models\User\UserToSecurityModule;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{

	protected $userObj;
	protected $securityModuleObj;
	protected $userToSecurityModuleObj;
	 
	public function __construct(User $user)
	{	
        $this->userObj = $user;
        $this->securityModuleObj 	  = new SecurityModule();
		$this->userToSecurityModuleObj= new UserToSecurityModule();
    }
  
    public function index(Request $request)
	{
		if (!Gate::allows('user_management')){ return abort(404); }
		
		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$users = $this->userObj->list($search,$page);
		$totalRecordCount= $this->userObj->listTotalCount($search);
	
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->userObj->preparePagination($totalRecordCount,$basePath);
		return view('administrator.users.index',compact('users','paging'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->userObj->saveRecord($data);
		$securitymodule = $this->securityModuleObj->checkboxList();
		if(isset($result['id'])){
			$user =$this->userObj->display($result['id']);
			if($result['success']==false){
			    return view('administrator.users.create',compact('securitymodule','user'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.users.create',compact('securitymodule','user')); 
			}
		}else{
			if($result['success']==false){
			    return view('administrator.users.create',compact('securitymodule'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.users.create',compact('securitymodule'));
			}
		}
	}
  
    public function create()
	{
		if (!Gate::allows('user_management_add')){ return abort(404); }

    	$securitymodule = $this->securityModuleObj->checkboxList();
		return view('administrator.users.create',compact('securitymodule'));
	}
    
	public function edit($id)
	{ 
	   if (!Gate::allows('user_management_edit')){ return abort(404); }

	   $user_links_id=array();
	   $user = $this->userObj->display($id);
	   $securitymodule = $this->securityModuleObj->checkboxList();
	   $user_links  = $this->userToSecurityModuleObj->getAllLinksArrayByUserId($id);
	   return view('administrator.users.create',compact('user','securitymodule','user_links'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('user_management_delete')){ return abort(404); }

		$isdelete =$this->userObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.users.index')->with('success','User Deleted.');
		}else{
			 return redirect()->route('administrator.users.index')->with('error','User Is Not deleted..');
		}
    }
}
