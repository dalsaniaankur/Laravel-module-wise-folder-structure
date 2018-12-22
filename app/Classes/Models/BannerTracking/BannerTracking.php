<?php

namespace App\Classes\Models\BannerTracking;

use App\Classes\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Helpers\BannerTracking\Helper;
use App\Classes\Models\BannerAds\BannerAds;
use Carbon\Carbon;

class BannerTracking extends BaseModel{
    
	protected $table = 'sbc_banner_tracking';
    protected $primaryKey = 'banner_tracking_id';
  	protected $entity='sbc_banner_tracking';
    protected $searchableColumns=['banner_ads_title','page_url','banner_redirect_link'];

    protected $_helper;
	protected $bannerAdsObj;

    protected $fillable = ['banner_ads_id',
                            'banner_ads_title',
							'page_url',
							'ip_address',
							'banner_redirect_link',
							];


	public function __construct(array $attributes=[])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();

        $this->bannerAdsObj = new BannerAds();
    }

    public function addBannerTrackingIdFilter($banner_tracking_id=0)
    {
        $this->queryBuilder->where($this->table.'.banner_tracking_id',$banner_tracking_id);
        return $this;
    }

    public function addDateFilter($start_date='', $end_date=''){

        $bannerTrackingTable=$this->table;
        
        if(!empty(trim($start_date))){
            $start_date = date("Y-m-d", strtotime($start_date));
            $this->queryBuilder->whereDate($bannerTrackingTable.'.created_at', '>=', "$start_date");
        }

        if(!empty(trim($end_date))){
            $end_date = date("Y-m-d", strtotime($end_date));
            $this->queryBuilder->whereDate($bannerTrackingTable.'.created_at', '<=', "$end_date");
        }

        return $this;   
    }

    public function list($search='', $page=0, $start_date='', $end_date='')
    {
        $per_page=$this->_helper->getConfigPerPageRecord();
            $list=$this->setSelect()
                        ->addSearch($search)
                        ->addDateFilter($start_date, $end_date)    
                        ->addPaging($page,$per_page)
                        ->get();

        return $list;
    }

    public function listWithoutPagination($search='', $start_date='', $end_date=''){
        
            $list=$this->setSelect()
                        ->addSearch($search)
                        ->addDateFilter($start_date, $end_date)    
                        ->get();

        return $list;
    }
    
    public function listTotalCount($search='', $start_date='', $end_date='')
    {
        $this->reset();
        $count=$this->setSelect()
                    ->addSearch($search)
                    ->addDateFilter($start_date, $end_date)    
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
            'banner_ads_id'        => 'required',
            'page_url'             => 'required',
            'ip_address'           => 'required',
            'ip_address'           => 'required',
            'banner_redirect_link' => 'required',
            
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
            $bannerTracking = BannerTracking::findOrFail($data['id']);
            $bannerTracking->update($data);
             $this->afterSave($data,$bannerTracking);   
            $result['id']=$bannerTracking->banner_tracking_id;   
        }else{
             $bannerTracking = BannerTracking::create($data);
             $this->afterSave($data,$bannerTracking);
             $result['id']=$bannerTracking->banner_tracking_id;
        }
        $result['success']=true;
        $result['message']="Banner Tracking Saved Successfully.";
        return $result;
    }
    public function load($banner_tracking_id){

        $this->beforeLoad($banner_tracking_id);

        $return =$this->setSelect()
                  ->addBannerTrackingIdFilter($banner_tracking_id)   
                  ->get()
                  ->first();

        $this->afterLoad($banner_tracking_id, $return);
                  
        return $return;
    }
    
    public function display($banner_tracking_id)
    {
        return $this->load($banner_tracking_id);
        
    }
    
    public function removed($banner_tracking_id)
    {
        $this->beforeRemoved($banner_tracking_id);
        $deleteBannerTrackingObj=$this->display($banner_tracking_id);
        if(!empty($deleteBannerTrackingObj)){
             $deleted=$deleteBannerTrackingObj->delete();
             $this->afterRemoved($banner_tracking_id);
             return $deleted;
        }
        return false;
    }
    public function getBannerTrackingWidget(){

        return $this->setSelect()
                      ->get()
                      ->count();
    }

    public function postBannerTracking($data){

    	$result=array();
    	unset($data['_token']);
        $bannerAds = $this->bannerAdsObj->getBannerAdsByBannerAdsId($data['banner_ads_id']);
        
        $data['banner_ads_title'] = $bannerAds->title;
        $data['banner_redirect_link'] = $bannerAds->forward_url;
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $data['created_at'] = Carbon::now()->toDateTimeString();
        $data['updated_at'] = Carbon::now()->toDateTimeString();
    	$bannerTracking = BannerTracking::insert($data);
    	$result['success']=true;
    	$result['banner_redirect_link']=$data['banner_redirect_link'];

    	return $result;
    }

}	