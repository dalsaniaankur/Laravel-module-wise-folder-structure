<?php
namespace App\Classes\Models\User;

use App\Classes\Models\BaseModel;

class User extends BaseModel
{
	protected $table = 'sbc_users';
    protected $primaryKey = 'user_id';
    
  	protected $entity='users';
	protected $searchableColumns=['first_name','last_name'];
    protected $fillable = ['user_id','first_name','last_name','password','email','profile_picture','status','parent_id'];
    protected $_helper;

	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper =new \App\Classes\Helpers\Helper();
    }
	
	public function addUserIdFilter($user_id=0)
	{
		$this->queryBuilder->where('user_id',$user_id);
		return $this;
	}
	
	public function list($search='',$page=0)
    {
		$per_page=$this->_helper->getConfigPerPageRecord();
		$list=$this->setSelect()
				  ->addSearch($search)
				  ->addPaging($page,$per_page)
				  ->get();

		return $list;
   	}
	
	public function listTotalCount($search='')
	{
		$this->reset();
		$count=$this->setSelect()
				 ->addSearch($search)
				  ->get()
				  ->count();
		
		return $count;
	}
	
	public function preparePagination($total,$basePath)
	{
		$perpage=$this->_helper->getConfigPerPageRecord();
		$pageHelper=new \App\Classes\PageHelper($perpage,'page');
		$pageHelper->set_total($total); 
		$pageHelper->page_links($basePath);
		return $pageHelper->page_links($basePath);
	}
	
	public function saveRecord($data)
	{
		$rules=array();	
		$rules=[
			'first_name' => 'required|max:100',
			'last_name' => 'required|max:100',
			'email' => 'required|unique:'.$this->table.',email',
		];
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];

		   $rules['email']='required|unique:'.$this->table.',email,'.$id.',user_id';

		   if(!empty($data['password'])){
			   	$rules['password']='required|confirmed';
				$rules['password_confirmation']='required';
		   }
		}else{
			$rules['password']='required|confirmed';
			$rules['password_confirmation']='required';
		}
		
		$validationResult=$this->validateData($rules,$data);
		$result=array();
		$result['id']='';
		if($validationResult['success']==false){
			$result['success']=false;
			$result['message']=$validationResult['message'];
			$result['id']=$data['id'];
			return $result;
		}
		
		$link_ids=array();
		if(!empty($data['link_id'])){
			$link_ids =$data['link_id'];
		}
		if(!empty($data['password'])){
			$data['password']=bcrypt($data['password']);		  		
		}else{
		 	unset($data['password']);
		}
		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$user = \App\Classes\Models\User\User::findOrFail($data['id']);
            $user->update($data);	
		    $this->afterSave($data,$user);
			$userToSecurityModule=new \App\Classes\Models\User\UserToSecurityModule();
			$userToSecurityModule->removeAllByUserId($data['id']);
			$result['id']=$user->user_id;	
			
		}else{
		 	 $user = \App\Classes\Models\User\User::create($data);
			 $result['id']=$user->user_id;
			 $this->afterSave($data,$user);
		}
		if(count($link_ids)>0){
			 foreach($link_ids as $link_id){
				$userToSecurityModuleEntry=array();
				$userToSecurityModuleEntry['user_id']=$result['id'];
				$userToSecurityModuleEntry['link_id']=$link_id; 
				$userToSecurityModule =\App\Classes\Models\User\UserToSecurityModule::create($userToSecurityModuleEntry);
			 }
		}
		$result['success']=true;
		$result['message']="User Saved Successfully.";
		return $result;
	}
	public function load($user_id){

		$this->beforeLoad($user_id);
	   	
	   	$return =$this->setSelect()
	   			  ->addUserIdFilter($user_id)	
				  ->get()
				  ->first();  

		$this->afterLoad($user_id, $return);
				  
		return $return;
   	}

	public function display($user_id)
    {
    	return $this->load($user_id);
   	}
	
	public function removed($user_id)
	{
		$this->beforeRemoved($user_id);
		$deleteMemberObj=$this->display($user_id);
		if(!empty($deleteMemberObj)){
			 $delete=$deleteMemberObj->delete();
			 $this->afterRemoved($user_id);
			 return $delete;
		}
		return false;
	}
	
	public function getUserWidget()
	{
		return $this->setSelect()
    			      ->get()
    			      ->count();
    }
}