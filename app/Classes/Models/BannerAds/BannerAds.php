<?php
namespace App\Classes\Models\BannerAds;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Models\BaseModel;
use App\Classes\Helpers\BannerAds\Helper;
use App\Classes\Models\Images\Images;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;

class BannerAds extends BaseModel{
    
	protected $table = 'sbc_banner_ads';
    protected $primaryKey = 'banner_ads_id';
    
  	protected $entity='sbc_banner_ads';
	protected $searchableColumns=['title'];

	protected $_helper;
	protected $bannerAdsCategoryObj;
	
    protected $fillable = [ 'banner_ads_id',
							'banner_ads_category_id',
							'title',
							'type',
							'position',
							'sort',
							'alt_image_text',
							'forward_url',
							'image_id',
						];


	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {	
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
        $this->bannerAdsCategoryObj = new BannerAdsCategory();
    }

	/**
	**	Model Relation Methods 
	*/
	public function Images()
    {
        return $this->belongsTo(Images::class,'image_id','image_id');
    }
    public function bannerAdsCategory(){

        return $this->belongsTo(BannerAdsCategory::class, 'banner_ads_category_id', 'banner_ads_category_id');
    }
	
	/**
	**	Model Filter Methods 
	*/	
	public function addBannerAdsIdFilter($banner_ads_id=0)
	{
		$this->queryBuilder->where('banner_ads_id',$banner_ads_id);
		return $this;
	}

	public function addBannerAdsCategoryIdFilter($banner_ads_category_id=0)
	{
		$this->queryBuilder->where('banner_ads_category_id',$banner_ads_category_id);
		return $this;
	}
	
	
	/*
	**	Logic Methods
	*/
	public function load($banner_ads_id)
    {
    	$this->beforeLoad($banner_ads_id);
	    
	    $return = $this->setSelect()
	   			  ->addBannerAdsIdFilter($banner_ads_id)	
				  ->get()
				  ->first();

		$this->afterLoad($banner_ads_id, $return);		  
		
		return $return;
   	}
	public function list($search='',$page=0, $banner_ads_category_id=0)
	{
		$per_page=$this->_helper->getConfigPerPageRecord();
  		
  		$list=$this->setSelect()
  				   ->addBannerAdsCategoryIdFilter($banner_ads_category_id)
  				   ->addSearch($search)
				   ->addPaging($page,$per_page)
				   ->get();

		return $list;
   	}
	
	public function listTotalCount($search='', $banner_ads_category_id=0){
		$this->reset();
		$count=$this->setSelect()
				  ->addSearch($search)
				  ->addBannerAdsCategoryIdFilter($banner_ads_category_id)
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
	
	public function saveRecord($data, $banner_ads_category_id){
		
		$rules=array();	
		$rules=[
				'banner_ads_category_id' => 'required',
				'title'                  => 'required',
				'type'                   => 'required',  
				'position'               => 'required',
				'sort'                   => 'required',
				'forward_url'            => 'required',
				'image'                  => 'mimes:jpeg,jpg,png,gif',
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
		
		$imageUploadPath = $this->_helper->getImageUploadPath();
		
		if(!empty($data['image'])){
			$image = $data['image'];
			$image_name = $data['image']->getClientOriginalName();
			$image_name = str_replace('.'.$data['image']->getClientOriginalExtension().'','_'.time().'.'.$data['image']->getClientOriginalExtension(),$image_name);
			
			$destinationPath = public_path($imageUploadPath);
			$image->move($destinationPath, $image_name);
			
			$image_data['image_name'] = $image_name;
			$image_data['image_path'] = $imageUploadPath.'/'.$image_name;
			$image_data['module_id'] = $this->_helper->getModuleId();			
			
			$images = \App\Classes\Models\Images\Images::insert($image_data);
			$inserted_image_id = DB::getPdo()->lastInsertId();
		}

		if(!empty($inserted_image_id)){
			$data['image_id'] = $inserted_image_id;
		}

		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$bannerAds = self::findOrFail($data['id']);
		    $bannerAds ->update($data);	
		    $this->afterSave($data,$bannerAds);
			$result['id']=$bannerAds->banner_ads_id;	
		}else{
		 	$bannerAds  = self::create($data);
			$result['id']=$bannerAds->banner_ads_id;
			$this->afterSave($data,$bannerAds);
		}
		$result['success']=true;
		$result['message']="Banner Ads Saved Successfully.";
		return $result;
	}
	
	public function display($banner_ads_id){

	    $return =$this->load($banner_ads_id);
		return $return;
   	}
	
	public function removed($id){

		$this->beforeRemoved($id);
		$team=$this->load($id);
		if(!empty($team)){
			 $delete = $team->delete();
			 $this->afterRemoved($id);
			 return $delete;
		}
		return false;
	}

	public function addOrderBy($columeName, $orderBy)
	{
		$this->queryBuilder->orderBy($columeName, $orderBy);
		return $this;
	}

	public function getBannerAdsDropdown()
	{
	    return  $this->setSelect()
		   			  ->addOrderBy('title', 'asc')	
	                  ->pluck('title', 'banner_ads_id');
  	}

  	public function getBannerAdsByBannerAdsId($banner_ads_id){
  		
		return $this->setSelect()
	   			  ->addBannerAdsIdFilter($banner_ads_id)	
				  ->get()
				  ->first();


	}
}