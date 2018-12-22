<?php
namespace App\Classes\Models\Categories;

use App\Classes\Models\BaseModel;
use App\Classes\Helpers\Categories\Helper;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;

class Categories extends BaseModel{

	protected $table = 'sbc_categories';
	protected $primaryKey = 'category_id';

	protected $entity='sbc_categories';
	protected $searchableColumns=['title'];
	protected $fillable = ['title', 
						   'parent_category_id', 
						   'banner_ads_category_id', 
						   'meta_title',
						   'meta_keyword',
						   'meta_description',
						   'short_description',
						   'description',
						   'image_name',
						   'image_path',
						   'url_key',
						   'sort',
						   'status',
						];
	protected $_helper;


	public function __construct(array $attributes=[])
	{
	    $this->bootIfNotBooted();
	    $this->syncOriginal();
	    $this->fill($attributes);
	    $this->_helper = new Helper();
	}
		    
	public function bannerAdsCategory(){

        return $this->belongsTo(BannerAdsCategory::class, 'banner_ads_category_id', 'banner_ads_category_id');
    }

    public function ParentCategory(){

        return $this->belongsTo(Categories::class, 'parent_category_id', 'category_id');
    }
    public function getImagePath(){
		
		if(!empty($this->image_path)){
    		return \URL::to('/') .'/'.$this->image_path;
		}
		return '';
    }

    public function addOrderBy($columeName, $orderBy){
		
		if(!empty($columeName) && !empty($orderBy)){

			$this->queryBuilder->orderBy($columeName, $orderBy);
		}
		return $this;
	}

	public function addStatusFilter($status = 2){
		
		if($status != 2){
			$this->queryBuilder->where($this->table.'.status',$status);
		}
		return $this;
	}

    public function getCategoriesDropdownWithNoneOption(){

	    return  $this->setSelect()
	    			  ->addStatusFilter(1)
		   			  ->addOrderBy('title', 'asc')	
		   			  ->get()
	                  ->pluck('title', 'category_id')
	                  ->prepend(trans('quickadmin.qa_none'), 0);
  	}

	public function addCategoryIdFilter($category_id=0){

		$this->queryBuilder->where($this->table.'.category_id',$category_id);
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
			'title' => 'required',
			'url_key'      => 'required|unique:'.$this->table,
			'image_name' => 'mimes:jpeg,jpg,png,gif',
			'sort' => 'required',
		];
		$data['url_key'] = str_slug($data['url_key']);
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',category_id';
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
		$imageUploadPath = $this->_helper->getImageUploadPath();

		if(!empty($data['image_name'])){
			$image = $data['image_name'];
			$image_name = $data['image_name']->getClientOriginalName();
			$image_name = str_replace('.'.$data['image_name']->getClientOriginalExtension().'','_'.time().'.'.$data['image_name']->getClientOriginalExtension(),$image_name);
			
			$destinationPath = public_path($imageUploadPath);
			$image->move($destinationPath, $image_name);

			$data['image_name'] = $image_name;
			$data['image_path'] = $imageUploadPath.'/'.$image_name;
		}

		if(!empty($data['status']) && $data['status'] ='on'){
			$data['status'] = 1;
		}else{
			$data['status'] = 0;
		}
		
		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$categories = \App\Classes\Models\Categories\Categories::findOrFail($data['id']);
	        $categories->update($data);
			 $this->afterSave($data,$categories);	
			$result['id']=$categories->category_id;	
		}else{
		 	 $categories = \App\Classes\Models\Categories\Categories::create($data);
			 $this->afterSave($data,$categories);
			 $result['id']=$categories->category_id;
		}
		$result['success']=true;
		$result['message']="Category Saved Successfully.";
		return $result;
	}
	public function load($category_id){

		$this->beforeLoad($category_id);

		$return =$this->setSelect()
	   			  ->addCategoryIdFilter($category_id)	
				  ->get()
				  ->first();

		$this->afterLoad($category_id, $return);
				  
		return $return;
	}

	public function display($category_id)
	{
		return $this->load($category_id);
	    
	}

	public function removed($category_id)
	{
		$this->beforeRemoved($category_id);
		$deleteImageObj=$this->display($category_id);
		if(!empty($deleteImageObj)){
			 $deleted=$deleteImageObj->delete();
			 $this->afterRemoved($category_id);
			 return $deleted;
		}
		return false;
	}
	public function getCategoryWidget(){

		return $this->setSelect()
				      ->get()
				      ->count();
	}

	public function addUrlKeyFilter($url_key){
		
		if(!empty($url_key)){
			$this->queryBuilder->where('url_key',$url_key);
		}
		return $this;
	}

	public function getCategoryByUrlKey($url_key){

		$return = $this->setSelect()
   			  	  ->addUrlKeyFilter($url_key)
				  ->get()
				  ->first();

		return $return;
	}

	public function addParentCategoryIdFilter($parent_category_id=0){

		$this->queryBuilder->where($this->table.'.parent_category_id',$parent_category_id);
		return $this;
	}
	public function getCategoryListByParentCategoryId($parent_category_id){

		$return =$this->setSelect()
	   			  ->addParentCategoryIdFilter($parent_category_id)	
				  ->get();
		return $return;
	}

	public function getMetaTitle(){
		return $this->meta_title;
	}

	public function getMetaKeywords(){
		return $this->meta_keywords;
	}

	public function getMetaDescription(){
		return $this->meta_description;
	}
	public function getMetaImage(){
		return (!empty($this->image_path)) ?  \URL::to('/') .'/'.$this->image_path : '';
		
	}

    public function addCategoryLevelFilter($categoryLevel = 0){

	    if($categoryLevel == 0) {
            $this->queryBuilder->where($this->table . '.parent_category_id', '=', 0);
        }else{
            $this->queryBuilder->where($this->table . '.parent_category_id', '!=', 0);
        }
        return $this;
    }

    public function checkIsCategory($url_key, $categoryLevel = 0){

        return  $this->setSelect()
                    ->addUrlKeyFilter($url_key)
                    ->addCategoryLevelFilter($categoryLevel)
                    ->addStatusFilter(1)
                    ->get()
                    ->count();

    }
	
}