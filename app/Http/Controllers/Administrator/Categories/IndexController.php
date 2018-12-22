<?php
namespace App\Http\Controllers\Administrator\Categories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Helpers\Categories\Helper;
use App\Classes\Models\Categories\Categories;
use Illuminate\Support\Facades\Gate;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;

class IndexController extends Controller{
  
	protected $categoriesObj;
	protected $bannerAdsCategoryObj;
	 
	public function __construct(Categories $categories){	

        $this->categoriesObj = $categories;
        $this->bannerAdsCategoryObj = new BannerAdsCategory();
        $this->_helper = new Helper();
    }
  
    public function index(Request $request){
    	
		if (!Gate::allows('categories')){ return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$categories = $this->categoriesObj->list($search,$page);
		$totalRecordCount= $this->categoriesObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->categoriesObj->preparePagination($totalRecordCount,$basePath);

		return view('administrator.categories.index',compact('categories','paging'));
    }

	public function save(Request $request){
		
		$submitData = $request->all();
		$data = $submitData;
		$result = $this->categoriesObj->saveRecord($data);
		$parentCategories = $this->categoriesObj->getCategoriesDropdownWithNoneOption();
	    $bannerAdsCategory = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithNoneOptionDropdown();

		if(isset($result['id']))
		{
			$categories =$this->categoriesObj->display($result['id']);
			if($result['success']==false){
			    return view('administrator.categories.create',compact('categories','bannerAdsCategory','parentCategories'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.categories.create',compact('categories','bannerAdsCategory','parentCategories')); 
			}
		}else{
			if($result['success']==false){
			    return view('administrator.categories.create',compact('bannerAdsCategory','parentCategories'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.categories.create',compact('bannerAdsCategory','parentCategories'));
			}
		}
	}
  
    public function create()
	{
		if (!Gate::allows('category_add')){ return abort(404); }

		$parentCategories = $this->categoriesObj->getCategoriesDropdownWithNoneOption();
	    $bannerAdsCategory = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithNoneOptionDropdown();
    	return view('administrator.categories.create',compact('bannerAdsCategory','parentCategories'));
	}
    
	public function edit($id)
	{ 
	   if (!Gate::allows('category_edit')){ return abort(404); }

	   $categories = $this->categoriesObj->display($id);
	   $parentCategories = $this->categoriesObj->getCategoriesDropdownWithNoneOption();
	   $bannerAdsCategory = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithNoneOptionDropdown();
	   return view('administrator.categories.create',compact('categories','bannerAdsCategory','parentCategories'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('category_delete')){ return abort(404); }

		$isdelete = $this->categoriesObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.categories.index')->with('success','Category Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.categories.index')->with('error','Category Is Not Deleted Successfully.');
		}
    }
}
