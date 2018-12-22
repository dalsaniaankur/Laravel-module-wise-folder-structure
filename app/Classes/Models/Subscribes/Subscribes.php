<?php
namespace App\Classes\Models\Subscribes;

use App\Classes\Models\BaseModel;
use App\Classes\Helpers\Subscribes\Helper;

class Subscribes extends BaseModel{
    
	protected $table = 'sbc_subscriber';
    protected $primaryKey = 'subscriber_id';
    
  	protected $entity='sbc_subscriber';
	protected $searchableColumns=['email'];
    protected $fillable = ['subscriber_id', 'email'];
	protected $_helper;
	
	public function __construct(array $attributes=[])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
    }
    
	public function addSubscriberIdFilter($subscriber_id=0)
	{
		$this->queryBuilder->where($this->table.'.subscriber_id',$subscriber_id);
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
	
	public function saveRecord($data){

		$rules=array();	
		$rules=[
			'email'      => 'required|unique:'.$this->table.',email',
			
		];
		
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['email']='required|unique:'.$this->table.',email,'.$id.',subscriber_id';
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
		
		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$subscribes = \App\Classes\Models\Subscribes\Subscribes::findOrFail($data['id']);
            $subscribes->update($data);
			 $this->afterSave($data,$subscribes);	
			$result['id']=$subscribes->subscriber_id;	
		}else{
		 	 $subscribes = \App\Classes\Models\Subscribes\Subscribes::create($data);
			 $this->afterSave($data,$subscribes);
			 $result['id']=$subscribes->subscriber_id;
		}
		$result['success']=true;
		$result['message']="Subscriber Saved Successfully.";
		return $result;
	}
	public function load($subscriber_id){

		$this->beforeLoad($subscriber_id);

		$return =$this->setSelect()
	   			  ->addSubscriberIdFilter($subscriber_id)	
				  ->get()
				  ->first();

		$this->afterLoad($subscriber_id, $return);
				  
		return $return;
   	}
	
	public function display($subscriber_id)
    {
    	return $this->load($subscriber_id);
	    
   	}
	
	public function removed($subscriber_id)
	{
		$this->beforeRemoved($subscriber_id);
		$deleteImageObj=$this->display($subscriber_id);
		if(!empty($deleteImageObj)){
			 $deleted=$deleteImageObj->delete();
			 $this->afterRemoved($subscriber_id);
			 return $deleted;
		}
		return false;
	}
	public function getSubscribesWidget(){

    	return $this->setSelect()
    			      ->get()
    			      ->count();
    }

    public function saveSubscriber($data){

		$rules=array();	
		$rules=[
			'email'  => 'required',
		];
		
		$validationResult=$this->validateData($rules,$data);
		$result=array();

		if($validationResult['success']==false){
			$result['success']=false;
			$result['message']=$validationResult['message'];
			return $result;
		}

		$subscriber = Subscribes::where('email',$data['email'])->first();

		if(!empty($subscriber->subscriber_id) && $subscriber->subscriber_id > 0){

			$result['message']="Given email address is already subscribed thank you.";
			$result['already_subscribed']=true;

		}else{
		 	$subscriber = \App\Classes\Models\Subscribes\Subscribes::create($data);
			$this->afterSave($data,$subscriber);
			$result['message']="Thank You For Subscribing To The Softballconnected Newsletter.";
			$result['already_subscribed']=false;
		}
		$result['success']=true;
		return $result;
	}
}