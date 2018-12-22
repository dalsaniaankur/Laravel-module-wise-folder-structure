<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Classes\Helpers\Categories\Helper;
use App\Classes\Models\Categories\Categories;
use App\Classes\Models\PageBuilder\PageBuilder;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;

class IndexController extends Controller{

    protected $_helper;
    protected $categoriesObj;
    protected $pageBuilderObj;
    protected $bannerAdsCategoryObj;
    
    public function __construct(Categories $categories){

        $this->categoriesObj = $categories;
        $this->pageBuilderObj = new PageBuilder();
        $this->bannerAdsCategoryObj = new BannerAdsCategory();
        $this->_helper = new Helper();
    }

    public function index(Request $request, $url_key){

        $data = $request->all();

        $page = !empty($request->get('page')) ? $request->get('page') : 0;

        $categories = $this->categoriesObj->getCategoryByUrlKey($url_key);
        
        if(empty($categories)){ return abort(404); }

        $category_id = $categories->category_id;
        $parent_category_id = $categories->category_id;

        $childCategories = $this->categoriesObj->getCategoryListByParentCategoryId($parent_category_id);
        
        $page_builder = $this->pageBuilderObj->list('', $page, $category_id, $status=1, $sortedBy ='page_builder_id', $sortedOrder='DESC');
        $totalRecordCount= $this->pageBuilderObj->listTotalCount('', $category_id, $status=1);
        $basePath=\Request::url().'?';
        $paging=$this->pageBuilderObj->preparePagination($totalRecordCount,$basePath);

        $topBannerAds = ($categories->banner_ads_category_id >0 ) ? $this->bannerAdsCategoryObj->getBannerAdsCategoryByIdWithAds($categories->banner_ads_category_id,'top') : array();
        $sideBannerAds = ($categories->banner_ads_category_id >0 ) ? $this->bannerAdsCategoryObj->getBannerAdsCategoryByIdWithAds($categories->banner_ads_category_id,'side') : array();
        
        $categories->meta_title = !empty(trim($categories->meta_title)) ? $categories->meta_title : $this->_helper->getDefaultMetaTitle(); 
        $categories->meta_keyword = !empty(trim($categories->meta_keyword)) ? $categories->meta_keyword : $this->_helper->getDefaultMetaKeywords(); 
        $categories->meta_description = !empty(trim($categories->meta_description)) ? $categories->meta_description : $this->_helper->getDefaultMetaDescription(); 
        $pageTitle = $this->_helper->getPageTitle($categories->title);

        return view('categories.index',compact('page_builder','paging','childCategories','categories','topBannerAds','sideBannerAds','pageTitle'));
    }
    
}
