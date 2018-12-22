<?php

namespace App\Classes\Models\EmailTemplate;

use App\Classes\Models\BaseModel;
use App\Classes\Helpers\EmailTemplate\Helper;

class EmailTemplate extends BaseModel{
    
	protected $table = 'sbc_email_template';
    protected $primaryKey = 'email_template_id';
    
  	protected $entity='sbc_email_template';
	protected $searchableColumns=['entity'];
    protected $fillable = ['email_template_id', 'entity', 'subject', 'template_content', 'last_email_sent_date'];
    protected $_helper;


	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new \App\Classes\Helpers\EmailTemplate\Helper();
    }

   public function addModuleIdFilter($module_id=0)
	{
		$this->queryBuilder->where('module_id',$module_id);
		return $this;
	}

	public function addEmailTemplateIdFilter($email_template_id=0){

		$this->queryBuilder->where('email_template_id',$email_template_id);
		return $this;
	}
	
	public function saveRecord($data){

		$rules=array();	
		$rules=[
			'entity' => 'required|unique:'.$this->table,
			'subject' => 'required',
			'template_content' => 'required',
		];

		if(isset($data['id']) && $data['id'] !=''){ 
			$rules['entity']='required|unique:'.$this->table.',entity,'.$data['id'].',email_template_id';
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
		if(!isset($data['last_email_sent_date'])){
		    $data['last_email_sent_date']=NULL;
		}

		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	
		  	$EmailTemplate = \App\Classes\Models\EmailTemplate\EmailTemplate::findOrFail($data['id']);
            $EmailTemplate->update($data);	
            $this->afterSave($data,$EmailTemplate);
			$result['id']=$EmailTemplate->email_template_id;	

		}else{
		 	
		 	 $EmailTemplate = \App\Classes\Models\EmailTemplate\EmailTemplate::create($data);
			 $result['id']=$EmailTemplate->email_template_id;
			 $this->afterSave($data,$EmailTemplate);

		}
		
		$result['success']=true;
		$result['message']="Email Template Saved Successfully.";
		return $result;
	}
	
	public function load($email_template_id){

		$this->beforeLoad($email_template_id);
	   	
	    $return =$this->setSelect()
	   			  ->addEmailTemplateIdFilter($email_template_id)	
				  ->get()
				  ->first();

		$this->afterLoad($email_template_id, $return);
				  
		return $return;
   	}

	public function display($email_template_id)
    {
    	return $this->load($email_template_id);
   	}

   	public function list($search='',$page=0){

  		$per_page=$this->_helper->getConfigPerPageRecord();
			$list=$this->setSelect()
				  ->addSearch($search)
				  ->addPaging($page,$per_page)
				  ->get();

		return $list;
   	}
	
	public function listTotalCount($search=''){
		$this->reset();
		$count=$this->setSelect()
				  ->addSearch($search)
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

	public function removed($email_template_id)
	{	
		$this->beforeRemoved($email_template_id);
		$deleteEmailTemplateObj = $this->display($email_template_id);
		if(!empty($deleteEmailTemplateObj)){
			 $delete = $deleteEmailTemplateObj->delete();
			 $this->afterRemoved($email_template_id);
			 return $delete;
		}
		return false;
	}
	
	public function addEntityTypeFilter($entity){

		$this->queryBuilder->where('entity',$entity);
		return $this;
	}
	
	public function getEmailTemplateForEntityType($entity){

	    return $this->setSelect()
	   			  ->addEntityTypeFilter($entity)	
				  ->get()
				   ->first();

   	}
	
}