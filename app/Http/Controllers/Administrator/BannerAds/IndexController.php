<?php
namespace App\Http\Controllers\Administrator\BannerAds;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\BannerAds\BannerAds;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;
use App\Classes\Helpers\BannerAds\Helper;
use App\Classes\Models\Images\Images;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{

	protected $bannerAds;
	protected $bannerAdsCategoryObj;
	protected $imagesObj;
	protected $_helper;

	public function __construct(BannerAds $bannerAds){

    	$this->bannerAds = $bannerAds;
    	$this->bannerAdsCategoryObj = new BannerAdsCategory();
    	$this->imagesObj = new Images();
		$this->_helper = new Helper();

    }

  public function index(Request $request, $banner_ads_category_id){		

  		if (!Gate::allows('banner_ads')){return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		
        $bannerAds = $this->bannerAds->list($search,$page,$banner_ads_category_id);
		$totalRecordCount= $this->bannerAds->listTotalCount($search, $banner_ads_category_id);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->bannerAds->preparePagination($totalRecordCount,$basePath);
		return view('administrator.banner_ads.index',compact('bannerAds','paging','banner_ads_category_id'));
  }

  public function save(Request $request, $banner_ads_category_id){		

		$submitData = $request->all();
		$data = $submitData;
		$result = $this->bannerAds->saveRecord($data,$banner_ads_category_id);
		
		$type = $this->_helper->getTypeDropdown();
	    $position = $this->_helper->getPositionDropdown();
	    $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
	    $bannerAdsCategory = $this->bannerAdsCategoryObj->getBannerAdsCategoryDropdown();

		if(isset($result['id'])){
			$bannerAds =$this->bannerAds->display($result['id']);
			if($result['success']==false){
			    return view('administrator.banner_ads.create',compact('bannerAds','type','position','images','banner_ads_category_id','bannerAdsCategory'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.banner_ads.create',compact('bannerAds','type','position','images','banner_ads_category_id','bannerAdsCategory'));
			}
		}else{
			if($result['success']==false){
				return view('administrator.banner_ads.create',compact('type','position','images','banner_ads_category_id','bannerAdsCategory'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.banner_ads.create',compact('type','position','images','banner_ads_category_id','bannerAdsCategory'));
				}
			}
		}

    public function create($banner_ads_category_id){
		
		if (!Gate::allows('banner_ads_add')){ return abort(404); }

        $type = $this->_helper->getTypeDropdown();
	    $position = $this->_helper->getPositionDropdown();
	    $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
	    $bannerAdsCategory = $this->bannerAdsCategoryObj->getBannerAdsCategoryDropdown();

	   return view('administrator.banner_ads.create',compact('banner_ads_category_id','type','position','images','bannerAdsCategory'));
	   
	}

	public function edit($id, $banner_ads_category_id){

		if (!Gate::allows('banner_ads_edit')){ return abort(404); }
        
 	    $bannerAds =$this->bannerAds->display($id);
	  	$type = $this->_helper->getTypeDropdown();
	    $position = $this->_helper->getPositionDropdown();
	    $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
	    $bannerAdsCategory = $this->bannerAdsCategoryObj->getBannerAdsCategoryDropdown();

	   return view('administrator.banner_ads.create',compact('bannerAds','banner_ads_category_id','type','position','images','bannerAdsCategory'));
    }

    public function destroy($id, $banner_ads_category_id){	
    	
		if (!Gate::allows('banner_ads_delete')){ return abort(404); }
        
		$isdelete =$this->bannerAds->removed($id);
		if($isdelete){
			return redirect('/administrator/banner_ads/'.$banner_ads_category_id)->with('success','Banner Ads Deleted.');
		}else{
			 return redirect('/administrator/banner_ads/'.$banner_ads_category_id)->with('error','Banner Ads Is Not deleted.');
		}
    }
}
