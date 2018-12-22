<?php
namespace App\Classes\Models\User;

use Auth;
use DB;
use App\Classes\Models\BaseModel;
use App\Classes\Models\State\State;

use App\Classes\Models\User\User;
use App\Classes\Models\SecurityModule\SecurityModule;

class UserToSecurityModule extends BaseModel{
    
	protected $table = 'sbc_user_to_security_module';
    protected $primaryKey = 'user_to_security_module_id';
    
  	protected $entity='user_to_security_module';
	protected $searchableColumns=[];

    protected $fillable = ['link_id','user_id'];

	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
    }
	
	public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
	
	public function securitymodule()
    {
        return $this->belongsTo(SecurityModule::class, 'link_id', 'link_id');
    }
	
	/**
	**	Model Filter Methods 
	*/	
	public function addLinkIdFilter($link_id=0)
	{
		$this->queryBuilder->where('link_id',$link_id);
		return $this;
	}
	
	public function addUserIdFilter($user_id=0)
	{
		$this->queryBuilder->where('user_id',$user_id);
		return $this;
	}
	public function addUserToSecurityModuleIdFilter($user_to_security_module_id=0)
	{
		$this->queryBuilder->where('user_to_security_module_id',$user_to_security_module_id);
		return $this;
	}
	
	public function addCodeFilter($code='')
	{
		$securityModule=new \App\Classes\Models\SecurityModule\SecurityModule;
		$securityModuleTable=$securityModule->getTable();
		$this->queryBuilder->where($securityModuleTable.'.code',$code);
		return $this;
	}
	/*
	**	Join Methods
	*/
	public function joinSecurityModule($searchable=false)
	{
		$securityModule=new \App\Classes\Models\SecurityModule\SecurityModule;
		$securityModuleTable=$securityModule->getTable();
		$searchableColumns=$securityModule->getSearchableColumns();
		$this->joinTables[]=array('table'=>$securityModuleTable,'searchable'=>$searchable,'searchableColumns'=>$searchableColumns);
		$this->queryBuilder->join($securityModuleTable,function($join) use($securityModuleTable){
			$join->on($this->table.'.link_id', '=', $securityModuleTable.'.link_id');
		});
		return $this;
	}
	
	
	/*
	**	Logic Methods
	*/
	public function load($user_to_security_module_id)
    {
    	$this->beforeLoad($user_to_security_module_id);
	   $return = $this->setSelect()
	   			  ->addUserToSecurityModuleIdFilter($user_to_security_module_id)	
				  ->get()
				  ->first();
				  
		$this->afterLoad($user_to_security_module_id, $return);		  
		return $return;
   	}
	
	public function getAllLinksArrayByUserId($user_id)
	{
		
		$list=$this->setSelect()
				   ->joinSecurityModule()	
				   ->addUserIdFilter($user_id)
				   ->get();
				   
		$user_links_ids=array();
		if(count($list)>0){
			foreach ($list as $value){ 
    			$user_links_ids[] = $value->link_id;
			}
		}

		return $user_links_ids;
	}
	
	public function removeAllByUserId($user_id)
	{
		$list=$this->setSelect()
				   ->joinSecurityModule()	
				   ->addUserIdFilter($user_id)
				   ->get();
				   
		$user_links_ids=array();
		if(count($list)>0){
			foreach ($list as $value){ 
    			$this->removed($value->user_to_security_module_id);
			}
		}
		return $user_links_ids;
	}
	

	public function removed($id)
	{
		$galary=$this->load($id);
		if(!empty($galary)){
			 return $galary->delete();
		}
		return false;
	}
	
	public function checkPermission($user_id,$code)
	{
		$list=$this->setSelect()
				   ->joinSecurityModule()	
				   ->addUserIdFilter($user_id)
				   ->addCodeFilter($code)
				   ->get();
		if(count($list)>0){
			return true;	
		}		   
		return false;
	}
	
}