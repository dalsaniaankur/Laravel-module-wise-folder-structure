<?php
namespace App\Classes\Models\BannerAdsCategory;

use App\Classes\Models\BaseModel;
use App\Classes\Helpers\BannerAdsCategory\Helper;
use App\Classes\Models\BannerAds\BannerAds;
use App\Classes\Models\Images\Images;

class BannerAdsCategory extends BaseModel{
    
	protected $table = 'sbc_banner_ads_category';
    protected $primaryKey = 'banner_ads_category_id';
    
  	protected $entity='sbc_banner_ads_category';
	protected $searchableColumns=['name'];
    protected $fillable = ['banner_ads_category_id', 'name', 'reservation_category_for', 'sort'];
	protected $_helper;

	
	public function __construct(array $attributes=[])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
    }
    

	public function addBannerAdsCategoryIdFilter($banner_ads_category_id=0)
	{
		$this->queryBuilder->where($this->table.'.banner_ads_category_id',$banner_ads_category_id);
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
			'name' => 'required',
			'sort' => 'required',
		];
		
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
		  	$bannerAdsCategory = \App\Classes\Models\BannerAdsCategory\BannerAdsCategory::findOrFail($data['id']);
            $bannerAdsCategory->update($data);
			 $this->afterSave($data,$bannerAdsCategory);	
			$result['id']=$bannerAdsCategory->banner_ads_category_id;	
		}else{
		 	 $bannerAdsCategory = \App\Classes\Models\BannerAdsCategory\BannerAdsCategory::create($data);
			 $this->afterSave($data,$bannerAdsCategory);
			 $result['id']=$bannerAdsCategory->banner_ads_category_id;
		}
		$result['success']=true;
		$result['message']="Banner Ads Category Saved Successfully.";
		return $result;
	}
	public function load($banner_ads_category_id){

		$this->beforeLoad($banner_ads_category_id);

		$return =$this->setSelect()
	   			  ->addBannerAdsCategoryIdFilter($banner_ads_category_id)	
				  ->get()
				  ->first();

		$this->afterLoad($banner_ads_category_id, $return);
				  
		return $return;
   	}
	
	public function display($banner_ads_category_id)
    {
    	return $this->load($banner_ads_category_id);
	    
   	}
	
	public function removed($banner_ads_category_id)
	{
		$this->beforeRemoved($banner_ads_category_id);
		$deleteImageObj=$this->display($banner_ads_category_id);
		if(!empty($deleteImageObj)){
			 $deleted=$deleteImageObj->delete();
			 $this->afterRemoved($banner_ads_category_id);
			 return $deleted;
		}
		return false;
	}
	public function getBannerAdsCategoryWidget(){

    	return $this->setSelect()
    			      ->get()
    			      ->count();
    }

    public function getBannerAdsCategoryDropdown()
    {
	   $return =$this->setSelect()
	  		 	  ->orderBy('name', 'asc')
				  ->pluck('name', 'banner_ads_category_id');
				  
		return $return;
   	}

   	public function getBannerAdsCategoryWithNoneOptionDropdown()
    {
	   $return =$this->setSelect()
	  		 	  ->orderBy('name', 'asc')
	  		 	  ->get()
				  ->pluck('name', 'banner_ads_category_id')
				  ->prepend(trans('quickadmin.qa_none'), 0);
		return $return;
   	}

   	public function addReservationCategoryForFilter($reservation_category_for)
	{
		$this->queryBuilder->where('reservation_category_for',$reservation_category_for);
		return $this;
	}

	public function joinBannerAds($searchable=false)
	{	
		$bannerAds = new BannerAds();
		$bannerAdsTable = $bannerAds->getTable();
		$searchableColumns = $bannerAds->getSearchableColumns();

		$this->joinTables[]=array('table'=>$bannerAdsTable,'searchable'=>$searchable,'searchableColumns'=>$searchableColumns);
		$this->queryBuilder->join($bannerAdsTable,function($join) use($bannerAdsTable) {
			$join->on($this->table.'.banner_ads_category_id', '=', $bannerAdsTable.'.banner_ads_category_id');
		});
		return $this;
	}

	public function joinImages($searchable=false)
	{	
		$imagesAds = new Images();
		$imagesTable = $imagesAds->getTable();
		$searchableColumns = $imagesAds->getSearchableColumns();

		$bannerAds = new BannerAds();
		$bannerAdsTable = $bannerAds->getTable();

		$this->joinTables[]=array('table'=>$imagesTable,'searchable'=>$searchable,'searchableColumns'=>$searchableColumns);
		$this->queryBuilder->join($imagesTable,function($join) use($imagesTable, $bannerAdsTable) {
			$join->on($bannerAdsTable.'.image_id', '=', $imagesTable.'.image_id');
		});
		return $this;
	}

	public function addgroupBy($groupByName){
		
		$this->queryBuilder->groupBy($groupByName);	
		return $this;
	}
	public function addOrderBy($_sortedBy, $_sortedOrder){

		$this->queryBuilder->orderBy($_sortedBy, $_sortedOrder);
		return $this;
	}
	
	public function addPositionFilter($position)
	{
		$this->queryBuilder->where('position',$position);
		return $this;
	}
   	public function getBannerAdsCategoryWithAds($reservation_category_for, $position){

   		$bannerAds = new BannerAds();
		$bannerAdsTable = $bannerAds->getTable();
		
	    $return = $this->setSelect()
		  		 	 ->addReservationCategoryForFilter($reservation_category_for)
		  		 	 ->addPositionFilter($position)
		  		 	 ->joinBannerAds()
		  		 	 ->joinImages()
		  		 	 ->addOrderBy($bannerAdsTable.'.sort', 'asc')
		  		 	 ->get();
		return $return;
   	}

   	public function getBannerAdsCategoryByIdWithAds($banner_ads_category_id, $position){

   		$bannerAds = new BannerAds();
		$bannerAdsTable = $bannerAds->getTable();

	   $return =$this->setSelect()
		  		 	 ->addBannerAdsCategoryIdFilter($banner_ads_category_id)	
		  		 	 ->addPositionFilter($position)
		  		 	 ->joinBannerAds()
		  		 	 ->joinImages()
		  		 	 ->addOrderBy($bannerAdsTable.'.sort', 'asc')
		  		 	 ->get();
		return $return;
   	}
}