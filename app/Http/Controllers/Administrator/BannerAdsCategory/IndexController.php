<?php
namespace App\Http\Controllers\Administrator\BannerAdsCategory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Helpers\BannerAdsCategory\Helper;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{
  
	protected $bannerAdsCategoryObj;
	 
	public function __construct(BannerAdsCategory $bannerAdsCategory){	

        $this->bannerAdsCategoryObj = $bannerAdsCategory;
        $this->_helper = new Helper();
    }
  
    public function index(Request $request){
    	
		if (!Gate::allows('banner_ads_category')){ return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$bannerAdsCategory = $this->bannerAdsCategoryObj->list($search,$page);
		$totalRecordCount= $this->bannerAdsCategoryObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->bannerAdsCategoryObj->preparePagination($totalRecordCount,$basePath);
		
		return view('administrator.banner_ads_category.index',compact('bannerAdsCategory','paging'));
    }

	public function save(Request $request){
		
		$submitData = $request->all();
		$data = $submitData;
		$result = $this->bannerAdsCategoryObj->saveRecord($data);
		$reservationCategoryFor = $this->_helper->getReservationCategoryForDropdown();

		if(isset($result['id']))
		{
			$bannerAdsCategory =$this->bannerAdsCategoryObj->display($result['id']);
			if($result['success']==false){
			    return view('administrator.banner_ads_category.create',compact('bannerAdsCategory','reservationCategoryFor'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.banner_ads_category.create',compact('bannerAdsCategory','reservationCategoryFor')); 
			}
		}else{
			if($result['success']==false){
			    return view('administrator.banner_ads_category.create',compact('reservationCategoryFor'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.banner_ads_category.create',compact('reservationCategoryFor'));
			}
		}
	}
  
    public function create()
	{
		if (!Gate::allows('banner_ads_category_add')){ return abort(404); }

		$reservationCategoryFor = $this->_helper->getReservationCategoryForDropdown();
    	return view('administrator.banner_ads_category.create',compact('reservationCategoryFor'));
	}
    
	public function edit($id)
	{ 
	   if (!Gate::allows('banner_ads_category_edit')){ return abort(404); }

	   $bannerAdsCategory = $this->bannerAdsCategoryObj->display($id);
	   $reservationCategoryFor = $this->_helper->getReservationCategoryForDropdown();
	   return view('administrator.banner_ads_category.create',compact('bannerAdsCategory','reservationCategoryFor'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('banner_ads_category_delete')){ return abort(404); }
		
		$isdelete = $this->bannerAdsCategoryObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.banner_ads_category.index')->with('success','Banner Ads Category Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.banner_ads_category.index')->with('error','Banner Ads Category Is Not Deleted Successfully.');
		}
    }
}
