<?php

namespace App\Classes\Models\CoachesNeeded;

use App\Classes\Models\BaseModel;
use App\Classes\Models\Images\Images;
Use DB;
use App\Classes\Helpers\CoachesNeeded\Helper;
use App\Classes\Models\Team\Team;

class CoachesNeeded extends BaseModel{
    
	protected $table = 'sbc_coaches_needed';
    protected $primaryKey = 'coaches_needed_id';
    
  	protected $entity='coaches_needed';
	protected $searchableColumns=['contact_first_name','contact_last_name','email'];
	protected $_helper;
	
    protected $fillable = ['coaches_needed_id',
							'member_id',
							'contact_first_name',
							'contact_last_name',
							'url_key',
							'phone_number',
							'email',
							'is_subscribe_newsletter',
							'team_id',
							'position_id',
							'age_group_id',
							'experience_id',
							'description',
							'image_id',
							'is_active',
							'is_send_email_to_user'
							];

	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();

    }

	
	public function Images()
    {
        return $this->belongsTo(Images::class, 'image_id', 'image_id');
    }

 	public function team()
	{
        return $this->belongsTo(Team::class, 'team_id', 'team_id');
    }
	
	public function addCoachesNeededIdFilter($coaches_needed_id=0)
	{
		$this->queryBuilder->where('coaches_needed_id',$coaches_needed_id);
		return $this;
	}
    public function addMemberIdFilter($member_id=0)
    {
        if($member_id > 0) {
            $this->queryBuilder->where('member_id', $member_id);
        }
        return $this;
    }
	//Logic method
	public function list($search='',$page=0, $member_id=0)
	{
  		$per_page=$this->_helper->getConfigPerPageRecord();
  		$list=$this->setSelect()
  				   ->addSearch($search)
                    ->addMemberIdFilter($member_id)
				   ->addPaging($page,$per_page)
				   ->get();
		
		return $list;
   	}
	
	public function listTotalCount($search='', $member_id=0)
	{
		$this->reset();
		$count=$this->setSelect()
				  ->addSearch($search)
				  ->addMemberIdFilter($member_id)
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
			'contact_first_name'   => 'required',
			'contact_last_name'    => 'required',
			'url_key'              => 'required|unique:'.$this->table,  
			'phone_number'         => 'required',
			'email'                => 'required|email',
			'position_id'          => 'required',
			'age_group_id'         => 'required',
			'coaches_needed_image' => 'mimes:jpeg,jpg,png,gif',
		];
		
		if(!empty($data['position_id'])){
			$data['position_id'] = $this->getArrayToCSV($data['position_id']);
		}else{
			$data['position_id'] ='';
		}

		if(!empty($data['age_group_id'])){
			$data['age_group_id'] = $this->getArrayToCSV($data['age_group_id']);
		}else{
			$data['age_group_id'] ='';
		}

		$data['url_key'] = str_slug($data['url_key']);
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',coaches_needed_id';
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
		
		if(!empty($data['coaches_needed_image'])){
			$image = $data['coaches_needed_image'];
			$coaches_needed_image_name = $data['coaches_needed_image']->getClientOriginalName();
			$coaches_needed_image_name = str_replace('.'.$data['coaches_needed_image']->getClientOriginalExtension().'','_'.time().'.'.$data['coaches_needed_image']->getClientOriginalExtension(),$coaches_needed_image_name);
			
			$destinationPath = public_path('/images/module_images');
			$image->move($destinationPath, $coaches_needed_image_name);

			$image_data['image_name'] = $coaches_needed_image_name;
			$image_data['image_path'] = 'images/module_images/'.$coaches_needed_image_name;
			$image_data['module_id'] = $this->_helper->getModuleId();
			
			$image_for_module_wise = \App\Classes\Models\Images\Images::insert($image_data);
			$inserted_image_id = DB::getPdo()->lastInsertId();
		}

		if(!empty($inserted_image_id)){
			$data['image_id'] = $inserted_image_id;
		}

		if(!empty($data['is_subscribe_newsletter']) && $data['is_subscribe_newsletter'] ='on'){
			$data['is_subscribe_newsletter'] = 1;
		}else{
			$data['is_subscribe_newsletter'] = 0;
		}

		if(!empty($data['is_active']) && $data['is_active'] ='on'){
			$data['is_active'] = 1;
		}else{
			$data['is_active'] = 0;
		}

		if(!empty($data['is_send_email_to_user']) && $data['is_send_email_to_user'] ='on'){
			$data['is_send_email_to_user'] = 1;
		}else{
			$data['is_send_email_to_user'] = 0;
		}
		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	
		  	$coaches_needed = \App\Classes\Models\CoachesNeeded\CoachesNeeded::findOrFail($data['id']);
            $coaches_needed->update($data);	
            $this->afterSave($data,$coaches_needed);
			$result['id']=$coaches_needed->coaches_needed_id;	

		}else{
		 	
		 	 $coaches_needed = \App\Classes\Models\CoachesNeeded\CoachesNeeded::create($data);
			 $result['id']=$coaches_needed->coaches_needed_id;
			 $this->afterSave($data,$coaches_needed);

		}

		$result['success']=true;
		$result['message']="Coaches Needed Saved Successfully.";
		return $result;
	}
	public function load($coaches_needed_id){

		$this->beforeLoad($coaches_needed_id);
	   	
	   	$return =$this->setSelect()
	   			  ->addCoachesNeededIdFilter($coaches_needed_id)	
				  ->get()
				  ->first();
		
		$this->afterLoad($coaches_needed_id, $return);
				  
		return $return;
   	}
	
	public function display($coaches_needed_id)
    {
		$return = $this->load($coaches_needed_id);

		if(!empty($return->age_group_id))
		{		  
			$return->age_group_id = $this->getCSVToArray($return->age_group_id);
		}
		
		if(!empty($return->position_id))
		{
			$return->position_id = $this->getCSVToArray($return->position_id);	
		}		  

		return $return;
   	}
	
	public function removed($coaches_needed_id)
	{
		$this->beforeRemoved($coaches_needed_id);
		$deleteMemberObj=$this->display($coaches_needed_id);
		if(!empty($deleteMemberObj)){
			 $delete = $deleteMemberObj->delete();
			 $this->afterRemoved($coaches_needed_id);
			 return $delete;
		}
		return false;
	}
	
	public function getCoachesNeededWidget($submitted_by_id=0){

    	return $this->setSelect()
                      ->addMemberIdFilter($submitted_by_id)
    			      ->get()
    			      ->count();
    }
    
     public function addIsActiveFilter($is_active=1){
    	
		$this->queryBuilder->where('is_active',$is_active);
		return $this;
	}

	public function addIsSendEmailToUserFilter($is_send_email_to_user=1){
    	
		$this->queryBuilder->where('is_send_email_to_user',$is_send_email_to_user);
		return $this;
	}

    public function getCoachesNeededListForSendMail(){
    
    	return $this->setSelect()
    				->addIsActiveFilter()
    				->addIsSendEmailToUserFilter()
   			        ->get();
		
	}
    public function addUrlKeyFilter($url_key){

        if(!empty(trim($url_key))) {
            $this->queryBuilder->where('url_key', $url_key);
        }
        return $this;
    }
    public function checkDuplicateUrlKey($url_key){
        return $this->setSelect()
            ->addUrlKeyFilter($url_key)
            ->get()
            ->count();
    }
}