<?php
namespace App\Classes\Models\Members;

use Hash;
use Auth;
use App\Classes\Models\EntityBaseModel;
use App\Notifications\MemberResetPasswordNotification;
use App\Classes\Helpers\Member\Helper;
use App\Classes\Models\MemberModulePermission\MemberModulePermission;
use App\Classes\Models\BaseModel;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;

class Members extends EntityBaseModel
{
    protected $table = 'sbc_members';
    public $primaryKey = 'member_id';
    public $entity='member';
	protected $searchableColumns=['first_name','last_name','email'];
    protected $fillable = ['member_id', 'email', 'password', 'first_name', 'last_name', 'url_key', 'description_id', 'affiliation', 'address_1', 'address_2', 'city_id', 'state_id', 'zip', 'phone'];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $_helper;
    protected $memberModulePermission;
    protected $baseModelObj;
    protected $stateObj;
    protected $cityObj;

	public function __construct(array $attributes = []){

        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
        $this->memberModulePermission = new MemberModulePermission();
        $this->baseModelObj = new BaseModel();
        $this->stateObj = new State();
        $this->cityObj = new City();
    }
	
	public function getMembersDropdown()
	{
	    return Members::orderBy('email', 'asc')->get()->pluck('email', 'member_id')->prepend(trans('quickadmin.qa_none'), 0);
	}

	public function addUrlKeyFilter($url_key){
        $this->queryBuilder->where('url_key',$url_key);
        return $this;
    }
    
	public function admin()
    {
        return $this->belongsTo(Administrator::class, 'created_by');
    }

    public function addEmailFilter($email='')
    {
        if(!empty($email)) {
            $this->queryBuilder->where('email', '=', $email);
        }
        return $this;
    }

	public function addMrmberIdFilter($member_id=0)
	{
		$this->queryBuilder->where('member_id',$member_id);
		return $this;
	}
	
	public function list($search='',$page=0,$selectedColumns = array('*'))
    {
 		$per_page=$this->_helper->getConfigPerPageRecord();
		$list=$this->setSelect()
				  ->addSearch($search)
				  ->addPaging($page,$per_page)
				  ->get($selectedColumns);

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
	
	public function preparePagination($total,$basePath){

		$perpage= $this->_helper->getConfigPerPageRecord();
		$pageHelper=new \App\Classes\PageHelper($perpage,'page');
		$pageHelper->set_total($total); 
		$pageHelper->page_links($basePath);
		return $pageHelper->page_links($basePath);
	}

	public function saveRecord($data){

		$rules=array();	    
		$rules=[
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'url_key' => 'required|unique:' . $this->table,
            'email' => 'required|unique:' . $this->table . ',email',
            'zip' => 'min:5|max:5',
            'member_module' => 'required',
            'description_id' => 'required',
		];

        $data['url_key'] = str_slug($data['url_key']);
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['email']='required|unique:'.$this->table.',email,'.$id.',member_id';
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',member_id';

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
		
		if(!empty($data['description_id'])){
			$data['description_id'] = $this->getArrayToCSV($data['description_id']);
		}else{
			$data['description_id'] ='';
		}

		if(!empty($data['password'])){
			$data['password']=bcrypt($data['password']);		  		
		}else{
		 	unset($data['password']);
		}
		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$member = \App\Classes\Models\Members\Members::findOrFail($data['id']);
            $member->update($data);	
			$this->afterSave($data,$member);
			$result['id']=$member->member_id;	
		}else{
		 	 $member = \App\Classes\Models\Members\Members::create($data);
			 $result['id']=$member->member_id;
			 $this->afterSave($data,$member);
		}
        $member_module = !empty($data['member_module']) ? $data['member_module'] : array();
        $this->memberModulePermission->updateMemberModulePermission($result['id'], $member_module);
		$result['success']=true;
		$result['message']="Memeber Saved Successfully.";
		return $result;
	}

	public function load($member_id){

		$this->beforeLoad($member_id);
	   	
	   	$return = $this->setSelect()
	   			  ->addMrmberIdFilter($member_id)	
				  ->get()
				  ->first();

		
		$this->afterLoad($member_id, $return);
				  
		return $return;
   	}
	
	public function display($member_id){

		$return = $this->load($member_id);

		if(!empty($return->description_id)){
		    $return->description_id = $this->getCSVToArray($return->description_id);
		}		  

		return $return;
   	}
	
	public function removed($member_id){

		$this->beforeRemoved($member_id);
		$deleteMemberObj=$this->display($member_id);
		if(!empty($deleteMemberObj)){
			  $delete=$deleteMemberObj->delete();
			  $this->afterRemoved($member_id);
			  return $delete;
		}
		return false;
	}
	public function getMemberWidget(){

    	return $this->setSelect()
    			      ->get()
    			      ->count();
    }

    public function addDescriptionIdFilter($descriptionId){

		$fieldName = $this->table.'.description_id';
		$this->queryBuilder->whereRaw("find_in_set('".$descriptionId."',".$fieldName.")");
		return $this;
	}

    public function getMemberListForSendMail($descriptionId){

	    return $this->setSelect()
	    			->addDescriptionIdFilter($descriptionId)
	   			  	->get();
   	}

   	/* Front */

   	public function setPasswordAttribute($input){
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
    
	//Send password reset notification
	public function sendPasswordResetNotification($token){
		$this->notify(new MemberResetPasswordNotification($token));
	}

	public function saveRegistrationRecord($data){
        $rules=array(); 
        $rules=[
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'required|unique:'.$this->table.',email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'description_id' => 'required',
            'zip'=>'required',
            'member_module' => 'required',
        ];
        $data['url_key'] = str_slug($data['url_key']);
        $validationResult=$this->validateData($rules,$data);
        $result=array();
        $result['id']='';
        
        if($validationResult['success']==false){
            $result['success']=false;
            $result['message']=$validationResult['message'];
            return $result;
        }

        if(!empty($data['description_id'])){
            $data['description_id'] = $this->getArrayToCSV($data['description_id']);
        }else{
            $data['description_id'] ='';
        }

        if(!empty($data['password'])){
            $data['password']=bcrypt($data['password']);                
        }else{
            unset($data['password']);
        }
        $this->beforeSave($data);

        $member = \App\Classes\Models\Members\Members::create($data);
        
        $this->loginMember($member);
        
        $result['id']=$member->member_id;
        $this->afterSave($data,$member);

        $member_module = !empty($data['member_module']) ? $data['member_module'] : array();
        $this->memberModulePermission->updateMemberModulePermission($result['id'], $member_module);

        $result['success']=true;
        $result['message']="Memeber registration successfully.";
        return $result;
    }

    public function loginMember($member){
    	\Auth::guard('member')->login($member);
    }

    public function getCurrentLoginMember(){

    	$member = \Auth::guard('member')->user();
    	if(!empty($member->description_id)){
            $member->description_id = $this->getCSVToArray($member->description_id);
        }

        return $member;
    }

    public function changeProfile($data){
        
        $rules=array();	
        $result['id']=$data['id'];
		$rules=[
			'first_name' => 'required|max:100',
			'last_name' => 'required|max:100',
			'email' => 'required|unique:'.$this->table.',email,'.$data['id'].',member_id',
			'zip'=>'min:5|max:5',
		];
		
		$validationResult=$this->validateData($rules,$data);
		
		if($validationResult['success']==false){
			$result['success']=false;
			$result['message']=$validationResult['message'];
			return $result;
		}
		
		if(!empty($data['description_id'])){
			$data['description_id'] = $this->getArrayToCSV($data['description_id']);
		}else{
			$data['description_id'] ='';
		}

		$this->beforeSave($data);
	  	$member = \App\Classes\Models\Members\Members::findOrFail($data['id']);
	  	
        $member->update($data);	
		$this->afterSave($data,$member);
		
		$result['success']=true;
		$result['message']="Memeber profile changed successfully.";
		return $result;
    }

    public function checkPermission($member,$permission)
    {
        $checkPermission=false;
        if(count((array)$member)==0){
            return $checkPermission;
        }
        $checkPermission=$this->memberModulePermission->checkPermission($member->member_id,$permission);
        return $checkPermission;
    }

    public function getMemberEmailByMemberId($member_id){

        return $this->setSelect()
            ->addMrmberIdFilter($member_id)
            ->get(['email'])
            ->pluck('email')
            ->first();
    }

    public function recordCount($email){
        return $this->setSelect()
            ->addEmailFilter($email)
            ->get(['member_id'])
            ->pluck('member_id')
            ->first();
    }

    public function checkIsExistMember($url_key){

        return  $this->setSelect()
               ->addUrlKeyFilter($url_key)
               ->get()
               ->first();
    }
}