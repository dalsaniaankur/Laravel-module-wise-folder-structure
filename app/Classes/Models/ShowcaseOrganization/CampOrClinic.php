<?php
namespace App\Classes\Models\ShowcaseOrganization;

use Auth;
use DB;
use App\Classes\Models\BaseModel;
use App\Classes\Models\State\State;
use App\Classes\Helpers\CampOrClinic\Helper;

class CampOrClinic extends BaseModel{
    
	protected $table = 'sbc_camp_or_clinic';
    protected $primaryKey = 'camp_clinic_id';
    
  	protected $entity='camp_or_clinic';
	protected $searchableColumns=['name'];
	protected $_helper;

    protected $fillable = ['submitted_by_id',
							'type',
							'showcase_organization_id',
							'name',
							'url_key',
							'date',
							'address_1',
							'address_2',
							'city_id',
							'state_id',
							'zip',
							'phone_number',
							'email',
							'description',
							'age_group_id',
							'service_id',
							'type_of_camp_or_clinic_id',
							'boys_or_girls',
							'longitude',
							'latitude',
							'attachment_name_1',
							'attachment_path_1',
							'attachment_name_2',
							'attachment_path_2',
							'website_url',
							'cost_or_notes',
							'other_information',
							'is_active',
							'is_send_email_to_user',
							];


	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new \App\Classes\Helpers\CampOrClinic\Helper;
    }

	/**
	**	Model Relation Methods 
	*/

    public function getDateAttribute($value) {
        if(!empty($value)) {
            return \Carbon\Carbon::parse($value)->format('m/d/Y');
        }
        return '';
    }

    public function agegroupformodulewise()
    {
        return $this->hasMany(\App\Classes\Models\AgeGroup\AgeGroup::class, 'age_group_id', 'age_group_id');
    }

	public function showcaseorganization()
    {
        return $this->belongsTo(\App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization::class, 'showcase_organization_id');
    }
   
	public function state(){

        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }
	
	/**
	**	Model Attirbute Methods 
	*/
	public function setDateAttribute($value)
	{
		if($value)
     	 $this->attributes['date'] = date("Y-m-d", strtotime($value));
		else
		 $this->attributes['date']=''; 
	}
	/**
	**	Model Filter Methods 
	*/	
	public function addCampClinicIdFilter($camp_clinic_id=0)
	{
		$this->queryBuilder->where('camp_clinic_id',$camp_clinic_id);
		return $this;
	}

	public function addShowcaseOrganizationIdFilter($showcase_organization_id=0)
	{
		$this->queryBuilder->where('showcase_organization_id',$showcase_organization_id);
		return $this;
	}
	
	/*
	**	Logic Methods
	*/
	public function load($camp_clinic_id)
    {
       $this->beforeLoad($camp_clinic_id);
	   
	   $return =$this->setSelect()
	   			  ->addCampClinicIdFilter($camp_clinic_id)	
				  ->get()
				  ->first();

		$this->afterLoad($camp_clinic_id, $return);				  
				  
		return $return;
   	}
    public function addSubmittedByIdFilter($submitted_by_id=0)
    {
        if($submitted_by_id > 0) {
            $this->queryBuilder->where('submitted_by_id', $submitted_by_id);
        }
        return $this;
    }
	public function list($search='',$page=0, $submitted_by_id=0)
	{
		$per_page=$this->_helper->getConfigPerPageRecord();
  		$list=$this->setSelect()
				   ->addSearch($search)
                   ->addSubmittedByIdFilter($submitted_by_id)
				   ->addPaging($page,$per_page)
				   ->get();

		if(count($list)>0){
			$ageGroupForModuleWiseObj = new \App\Classes\Models\AgeGroup\AgeGroup();
			
			foreach($list as $row){
				
				if(!empty($row->age_group_id)){
					$age_group_id_array = $this->getCSVToArray($row->age_group_id);
				}

				$age_group_list = array();
				
				if(!empty($age_group_id_array)){
					foreach ($age_group_id_array as $row_age_group_id) {
						$age_group_result = $ageGroupForModuleWiseObj->getAllAgeGropuForCampOrClinic($row_age_group_id);		
						$age_group_list[] = $age_group_result->name;
					}
				}
				
				$row['age_group'] = $age_group_list;	
			}

		}			   
        
		return $list;
   	}
	
	public function listTotalCount($search='', $submitted_by_id=0)
	{
		$this->reset();
		$count=$this->setSelect()
				  ->addSearch($search)
                  ->addSubmittedByIdFilter($submitted_by_id)
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
	
	public function saveRecord($data){

        /* Check Duplicate or Form Submit Call */
        $is_form_submit=0;
        if(!empty($data['_token'])){
            $is_form_submit=1;
        }

		$rules=array();	
		$rules=[
				'submitted_by_id'       => 'required',
				'type'                  => 'required',
				'showcase_organization_id' => 'required',
				'name'                  => 'required',
				'url_key'               => 'required|unique:'.$this->table,  
				'address_1'             => 'required',
				'city_id'               => 'required',
				'state_id'              => 'required',
				'zip'                   => 'required',
				'phone_number'          => 'required',
				'age_group_id'          => 'required',
				'email'                 => 'required',
		];

        if($is_form_submit == 1) {
            $rules['attachment_name_1'] = 'mimes:application/msword,pdf,application/pdf,text/plain';
            $rules['attachment_name_2'] = 'mimes:application/msword,pdf,application/pdf,text/plain';
        }
            if (!empty($data['age_group_id'])) {
                $data['age_group_id'] = $this->getArrayToCSV($data['age_group_id']);
            } else {
                $data['age_group_id'] = '';
            }


		$data['url_key'] = str_slug($data['url_key']);
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',camp_clinic_id';
		}

		$validationResult=$this->validateData($rules,$data);
		$result=array();
		$result['id']='';

		if($validationResult['success']==false){
			$result['success']=false;
			$result['message']=$validationResult['message'];
			if(!empty($data['id'])) {
                $result['id'] = $data['id'];
            }
			return $result;
		}

        if($is_form_submit == 1) {
            $imageUploadPath = $this->_helper->getImageUploadPath();
            if (!empty($data['attachment_name_1'])) {
                $attachment_1 = $data['attachment_name_1'];
                $attachment_1_name = $attachment_1->getClientOriginalName();

                $destinationPath = public_path($imageUploadPath);
                $attachment_1->move($destinationPath, $attachment_1_name);

                $data['attachment_name_1'] = $attachment_1_name;
                $data['attachment_path_1'] = $imageUploadPath . '/' . $attachment_1_name;
            }

            if (!empty($data['attachment_name_2'])) {
                $attachment_2 = $data['attachment_name_2'];
                $attachment_2_name = $attachment_2->getClientOriginalName();

                $destinationPath = public_path($imageUploadPath);
                $attachment_2->move($destinationPath, $attachment_2_name);

                $data['attachment_name_2'] = $attachment_2_name;
                $data['attachment_path_2'] = $imageUploadPath . '/' . $attachment_2_name;
            }
        }

        if (!empty($data['service_id'])) {
            $data['service_id'] = $this->getArrayToCSV($data['service_id']);
        } else {
            $data['service_id'] = '';
        }

        if (!empty($data['type_of_camp_or_clinic_id'])) {
            $data['type_of_camp_or_clinic_id'] = $this->getArrayToCSV($data['type_of_camp_or_clinic_id']);
        } else {
            $data['type_of_camp_or_clinic_id'] = '';
        }

        if (!empty($data['boys_or_girls'])) {
            $data['boys_or_girls'] = $this->getArrayToCSV($data['boys_or_girls']);
        } else {
            $data['boys_or_girls'] = '';
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
		  	$camp_clinic = self::findOrFail($data['id']);
		    $camp_clinic ->update($data);	
		    $this->afterSave($data,$camp_clinic);
			$result['id']=$camp_clinic ->camp_clinic_id;	
	
		}else{
		 	$camp_clinic  = self::create($data);
			$result['id'] = $camp_clinic->camp_clinic_id;
			$this->afterSave($data,$camp_clinic);
		}
		$result['success']=true;
		$result['message']="Camp Or Clinic Saved Successfully.";
	
		return $result;
	}
	
	public function display($id)
    {
	    $return =$this->load($id);
	    if(!empty($return->age_group_id)){
		    $return->age_group_id = $this->getCSVToArray($return->age_group_id);
	    }
	    if(!empty($return->service_id)){
		    $return->service_id = $this->getCSVToArray($return->service_id);
	    }
	    if(!empty($return->type_of_camp_or_clinic_id)){
		    $return->type_of_camp_or_clinic_id = $this->getCSVToArray($return->type_of_camp_or_clinic_id);
	    }
	    if(!empty($return->boys_or_girls)){
		    $return->boys_or_girls = $this->getCSVToArray($return->boys_or_girls);
	    }
		return $return;
   	}
	
	public function removed($id)
	{
		$this->beforeRemoved($id);
		$organisation=$this->load($id);
		if(!empty($organisation)){
			 $delete = $organisation->delete();
			 $this->afterRemoved($id);
			 return $delete;
		}
		return false;
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