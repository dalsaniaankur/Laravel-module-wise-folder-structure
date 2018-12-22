<?php
namespace App\Classes\Models\LookupForPlayerExperience;

use App\Classes\Models\BaseModel;
use App\Classes\Models\Images\Images;
Use DB;
use App\Classes\Helpers\LookupForPlayerExperience\Helper;

class LookupForPlayerExperience extends BaseModel{
    
	protected $table = 'sbc_lookup_for_player_experience';
    protected $primaryKey = 'lookup_for_player_experience_id';
    
  	protected $entity='lookup_for_player_experience';
	protected $searchableColumns=['player_first_name','player_last_name','player_email'];
   
    protected $_helper;
    protected $fillable = ['lookup_for_player_experience_id',
							'member_id',
							'player_first_name',
							'player_last_name',
							'url_key',
							'player_phone_number',
							'player_email',
							'player_zip',
							'is_subscribe_newsletter',
							'age_group_id',
							'position_id',
							'bats_or_throw_id',
							'experience_id',
							'comments',
							'image_id',
							'is_active',
							'is_send_email_to_user',
							];


	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
    }

	public function addLookupForPlayerExperienceIdFilter($lookup_for_player_experience_id=0)
	{
		$this->queryBuilder->where('lookup_for_player_experience_id',$lookup_for_player_experience_id);
		return $this;
	}

    public function Images()
    {
        return $this->belongsTo(Images::class, 'image_id', 'image_id');
    }
    public function addMemberIdFilter($member_id=0)
    {
        if($member_id > 0) {
            $this->queryBuilder->where('member_id', $member_id);
        }
        return $this;
    }
	//Logic method
	public function list($search='',$page=0, $member_id=0){

  		$per_page=$this->_helper->getConfigPerPageRecord();

  		$list=$this->setSelect()
  				   ->addSearch($search)
                   ->addMemberIdFilter($member_id)
				   ->addPaging($page,$per_page)
				   ->get();
		
		return $list;
   	}
	
	public function listTotalCount($search='', $member_id=0){
		$this->reset();
		$count=$this->setSelect()
				  ->addSearch($search)
                  ->addMemberIdFilter($member_id)
				  ->get()
				  ->count();
		
		return $count;
	}
	
	public function preparePagination($total,$basePath){

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
			'member_id'           => 'required',
			'player_first_name'   => 'required|max:100',
			'player_last_name'    => 'required|max:100',
			'url_key'             => 'required|unique:'.$this->table,  
			'player_phone_number' => 'required',
			'player_email'        => 'required|email',
			'player_zip'          => 'required|min:5|max:5',
			'age_group_id'        => 'required',
			'position_id'         => 'required',
			'bats_or_throw_id'    => 'required',
			'lookup_for_player_experience_image'    => 'mimes:jpeg,jpg,png,gif',
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
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',lookup_for_player_experience_id';
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
		
		if(!empty($data['lookup_for_player_experience_image'])){
			$image = $data['lookup_for_player_experience_image'];
			$lookup_for_player_experience_image_name = $data['lookup_for_player_experience_image']->getClientOriginalName();
			$lookup_for_player_experience_image_name = str_replace('.'.$data['lookup_for_player_experience_image']->getClientOriginalExtension().'','_'.time().'.'.$data['lookup_for_player_experience_image']->getClientOriginalExtension(),$lookup_for_player_experience_image_name);
			
			$destinationPath = public_path('/images/module_images');
			$image->move($destinationPath, $lookup_for_player_experience_image_name);

			$image_data['image_name'] = $lookup_for_player_experience_image_name;
			$image_data['image_path'] = 'images/module_images/'.$lookup_for_player_experience_image_name;
			$image_data['module_id'] = $this->_helper->getModuleId();
			
			\App\Classes\Models\Images\Images::insert($image_data);
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
		  	$lookup_for_player_experience = \App\Classes\Models\LookupForPlayerExperience\LookupForPlayerExperience::findOrFail($data['id']);
            $lookup_for_player_experience->update($data);	
            $this->afterSave($data,$lookup_for_player_experience);
			$result['id']=$lookup_for_player_experience->lookup_for_player_experience_id;	
		}else{

		 	 $lookup_for_player_experience = \App\Classes\Models\LookupForPlayerExperience\LookupForPlayerExperience::create($data);
			 $result['id']=$lookup_for_player_experience->lookup_for_player_experience_id;
			 $this->afterSave($data,$lookup_for_player_experience);
		}
		$result['success']=true;
		$result['message']="Lookup For Player Experience Saved Successfully.";
		return $result;
	}
	public function load($lookup_for_player_experience_id){

		$this->beforeLoad($lookup_for_player_experience_id);
	   	
	   	 $return =$this->setSelect()
	   			  ->addLookupForPlayerExperienceIdFilter($lookup_for_player_experience_id)	
				  ->get()
				  ->first();  

		$this->afterLoad($lookup_for_player_experience_id, $return);
				  
		return $return;
   	}
	
	public function display($lookup_for_player_experience_id)
    {

    	$return = $this->load($lookup_for_player_experience_id);
	    
		if(!empty($return->age_group_id)){
			$return->age_group_id  = $this->getCSVToArray($return->age_group_id);	
		}
		if(!empty($return->position_id)){
			$return->position_id  = $this->getCSVToArray($return->position_id);
		}

		return $return;
   	}
	
	public function removed($lookup_for_player_experience_id)
	{
		$this->beforeRemoved($lookup_for_player_experience_id);
		$deleteMemberObj=$this->display($lookup_for_player_experience_id);
		if(!empty($deleteMemberObj)){
			 $delete = $deleteMemberObj->delete();
			 $this->afterRemoved($lookup_for_player_experience_id);
			  return $delete;
		}
		return false;
	}
	
	public function getLookupForPlayerExperienceWidget($submitted_by_id = 0){

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

    public function getLookupForPlayerExperienceListForSendMail(){
	    
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