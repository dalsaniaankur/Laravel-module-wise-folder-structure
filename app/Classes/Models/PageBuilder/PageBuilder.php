<?php

namespace App\Classes\Models\PageBuilder;

use App\Classes\Models\BaseModel;
Use DB;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Helpers\PageBuilder\Helper;
use App\Classes\Models\AdministratorConfiguration\AdministratorConfiguration;
use Image;

class PageBuilder extends BaseModel{
    
	protected $table = 'sbc_page_builder';
    protected $primaryKey = 'page_builder_id';
    
  	protected $entity='sbc_page_builder';
	protected $searchableColumns=['page_title','filter_table','url_key'];
	
	protected $_helper;
	protected $administratorConfigurationObj;

    protected $fillable = ['page_builder_id',
							'page_title',
							'url_key',
							'content',
							'short_content',
							'display_banner_ads',
							'banner_ads_category_id',
							'category_id',
							'filter_table',
							'state_id',
							'city_id',
							'redius',
							'image_name',
							'image_path',
							'meta_title',
							'meta_keywords',
							'meta_description',
							'status'];


	public function __construct(array $attributes = []){

        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
        $this->administratorConfigurationObj = new AdministratorConfiguration();
    }

    public function state(){

        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function city(){

        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }
    public function addPageBuilderIdFilter($page_builder_id=0){

		$this->queryBuilder->where('page_builder_id',$page_builder_id);
		return $this;
	}

	public function addStatusFilter($status=2){
		
		if($status != 2){
			$this->queryBuilder->where('status',$status);
		}
		return $this;
	}

	public function addCategoryIdFilter($category_id=0){
		
		if(!empty($category_id) && $category_id > 0){
			$this->queryBuilder->where('category_id',$category_id);
		}
		
		return $this;
	}

	public function addgroupBy($groupByName){
		
		$this->queryBuilder->groupBy($groupByName);	
		return $this;
	}

	public function addOrderBy($_sortedBy='', $_sortedOrder=''){
		
		if(!empty($_sortedBy) && !empty($_sortedOrder)){
			$this->queryBuilder->orderBy($_sortedBy, $_sortedOrder);
		}
		return $this;
	}

	//Logic method
	public function list($search='',$page=0, $category_id=0, $status=2, $sortedBy ='page_title', $sortedOrder='ASC', $per_page=0){

  		$per_page = $per_page == 0 ? $this->_helper->getConfigPerPageRecord() : $per_page;

  		$list=$this->setSelect()
  				   ->addSearch($search)
  				   ->addCategoryIdFilter($category_id)	
  				   ->addStatusFilter($status)
				   ->addOrderBy($this->table.'.'.$sortedBy, $sortedOrder)
				   ->addPaging($page,$per_page)
				   ->addgroupBy($this->table.'.page_builder_id')
				   ->get();
		
		return $list;
   	}
	
	public function listTotalCount($search='', $category_id=0, $status=2){
		$this->reset();
		$count=$this->setSelect()
				    ->addSearch($search)
				    ->addCategoryIdFilter($category_id)	
				    ->addStatusFilter($status)
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
	
	public function saveRecord($data){

        /* Check Duplicate or Form Submit Call */
        $is_form_submit=0;
        if(!empty($data['_token'])){
            $is_form_submit=1;
        }

		$rules=array();	
		$rules=[
			'page_title'   => 'required',  
			'url_key'      => 'required|unique:'.$this->table,  
			'content'      => 'required',  
			'filter_table' => 'required',  
			'state_id'     => 'required',  
		];
		if($is_form_submit == 1){
            $rules['image_name'] = 'mimes:jpeg,jpg,png,gif|dimensions:min_width=795';
        }
		$content = $data['content'];
		$data['content'] = strip_tags($data['content']);

		if(empty(trim($data['content']))){
			$data['content']='';
		}

		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',page_builder_id';
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
		
		$data['content'] = $content;
        if($is_form_submit == 1) {
            if (!empty($data['image_name'])) {
                $image = $data['image_name'];
                $image_name = $data['image_name']->getClientOriginalName();
                $image_name = str_replace('.' . $data['image_name']->getClientOriginalExtension() . '', '_' . time() . '.' . $data['image_name']->getClientOriginalExtension(), $image_name);
                $destinationPath = public_path('/images/page_builder');

                $aspectImage = Image::make($image)->resize(795, null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->save($destinationPath.'/'.$image_name);

                $data['image_name'] = $image_name;
                $data['image_path'] = 'images/page_builder/' . $image_name;
            }
        }
		if(!empty($data['status']) && $data['status'] ='on'){
			$data['status'] = 1;
		}else{
			$data['status'] = 0;
		}
		
		
	    $this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$page_builder = PageBuilder::findOrFail($data['id']);
            $page_builder->update($data);	
			$this->afterSave($data,$page_builder);
			$result['id']=$page_builder->page_builder_id;	
		}else{
		 	 $page_builder = PageBuilder::create($data);
			 $result['id']=$page_builder->page_builder_id;
			 $this->afterSave($data,$page_builder);
		}
		$result['success']=true;
		$result['message']="Page Builder Saved Successfully.";
		return $result;
	}

	public function load($page_builder_id){

		$this->beforeLoad($page_builder_id);
	   	
	    $return =$this->setSelect()
	   			  ->addPageBuilderIdFilter($page_builder_id)	
				  ->get()
				  ->first();

		$this->afterLoad($page_builder_id, $return);
				  
		return $return;
   	}
	
	public function display($page_builder_id)
    {
		$return = $this->load($page_builder_id); 
		return $return;
   	}
	
	public function removed($page_builder_id)
	{
		$this->beforeRemoved($page_builder_id);
		$deleteMemberObj=$this->display($page_builder_id);
		if(!empty($deleteMemberObj)){
			 $delete = $deleteMemberObj->delete();
			 $this->afterRemoved($page_builder_id);
			 return $delete;
		}
		return false;
	}
	public function getPageBuilderWidget(){

    	return $this->setSelect()
    			      ->get()
    			      ->count();
    }

    public function addUrlKeyFilter($url_key){
        if(!empty(trim($url_key))) {
            $this->queryBuilder->where('url_key', $url_key);
        }
		return $this;
	}

    public function getPageByUrlKey($url_key){
	   	
	    return $this->setSelect()
	   			    ->addUrlKeyFilter($url_key)	
	   			    ->addStatusFilter(1)
				    ->get()
				    ->first();
   	}

   	public function getPageTitle(){

		if(!empty(trim($this->page_title))){
			return $this->page_title;	
		}

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('page_title');
		return $dbConfig->value;
	}

   	public function getMetaTitle(){
		
		if(!empty(trim($this->meta_title))){
			return $this->meta_title;
		}

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('meta_title');
		return $dbConfig->value;
	}

	public function getMetaKeywords(){
		
		if(!empty(trim($this->meta_keywords))){
			return $this->meta_keywords;
		}

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('meta_keyword');
		return $dbConfig->value;
	}

	public function getMetaDescription(){
		
		if(!empty(trim($this->meta_description))){
			return $this->meta_description;
		}

		$dbConfig = $this->administratorConfigurationObj->getValueByKey('meta_description');
		return $dbConfig->value;
	}
	public function getMetaImage(){

		return (!empty(trim($this->image_path))) ?  \URL::to('/') .'/'.$this->image_path : '';
		
	}
    public function checkDuplicateUrlKey($url_key){
        return $this->setSelect()
            ->addUrlKeyFilter($url_key)
            ->get()
            ->count();
    }

    public function checkIsPageBuilder($url_key){

        return $this->setSelect()
                    ->addUrlKeyFilter($url_key)
                    ->addStatusFilter(1)
                    ->get()
                    ->count();

    }
}