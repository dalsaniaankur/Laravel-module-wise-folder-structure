<?php
namespace App\Http\Controllers\Administrator\PageBuilder;

use App\Classes\Models\PageBuilder\PageBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\State\State;
use App\Classes\Helpers\PageBuilder\Helper;
use Illuminate\Support\Facades\Gate;
use App\Classes\Models\BannerAdsCategory\BannerAdsCategory;
use App\Classes\Models\Categories\Categories;
use App\Classes\Models\City\City;

class IndexController extends Controller{
    
	protected $pageBuilderObj;
	protected $stateObj;
	protected $bannerAdsCategoryObj;
	protected $categoriesObj;
	protected $cityObj;
	protected $_helper;
 
	public function __construct(PageBuilder $pageBuilder){	

		$this->pageBuilderObj = $pageBuilder;
		$this->bannerAdsCategoryObj = new BannerAdsCategory();
		$this->stateObj = new State();
		$this->categoriesObj = new Categories();
		$this->cityObj = new City();
		$this->_helper = new Helper();
    }
  
    public function index(Request $request)
	{
		if (!Gate::allows('page_builder')){ return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$page_builder = $this->pageBuilderObj->list($search,$page);
		$totalRecordCount= $this->pageBuilderObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->pageBuilderObj->preparePagination($totalRecordCount,$basePath);
		return view('administrator.page_builder.index',compact('page_builder','paging'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->pageBuilderObj->saveRecord($data);

        $state = $this->stateObj->getStateDropdown();
	    $filterTable = $this->_helper->getFilterTable();
	    $displayBannerAds = $this->_helper->getDisplayBannerAds();
	    $bannerAdsCategory = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithNoneOptionDropdown();
	    $categories = $this->categoriesObj->getCategoriesDropdownWithNoneOption();
	    $city = array(0 => 'None');
		if(isset($result['id'])){

			$page_builder =$this->pageBuilderObj->display($result['id']);
			if(!empty($submitData['city_id'])) {
                $city = $this->cityObj->getCityDropdownByCityIdWithNoneOption($submitData['city_id']);
            }
			if($result['success']==false){
			    return view('administrator.page_builder.create',compact('page_builder','state','filterTable','categories','displayBannerAds','bannerAdsCategory','city'))->withErrors($result['message']);
			}else{ 
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.page_builder.create',compact('page_builder','state','filterTable','categories','displayBannerAds','bannerAdsCategory','city')); 
			}
		}else{ 

			if($result['success']==false){
			    return view('administrator.page_builder.create',compact('state','filterTable','displayBannerAds','categories','bannerAdsCategory','city'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.page_builder.create',compact('state','filterTable','displayBannerAds','categories','bannerAdsCategory','city'));
			}
		}
	}
  
    public function create()
	{
	   if (!Gate::allows('page_builder_add')){ return abort(404); }

       $state = $this->stateObj->getStateDropdown();
	   $filterTable = $this->_helper->getFilterTable();
	   $displayBannerAds = $this->_helper->getDisplayBannerAds();
	   $bannerAdsCategory = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithNoneOptionDropdown();
	   $categories = $this->categoriesObj->getCategoriesDropdownWithNoneOption();
	   $city = array(0 => 'None');
    	return view('administrator.page_builder.create',compact('state','filterTable','categories','displayBannerAds','bannerAdsCategory','city'));
	}
    
	public function edit($id){ 
	  
	   if (!Gate::allows('page_builder_edit')){ return abort(404); }

	   $page_builder = $this->pageBuilderObj->display($id);
       $state = $this->stateObj->getStateDropdown();
	   $filterTable = $this->_helper->getFilterTable();
	   $displayBannerAds = $this->_helper->getDisplayBannerAds();
	   $bannerAdsCategory = $this->bannerAdsCategoryObj->getBannerAdsCategoryWithNoneOptionDropdown();
	   $categories = $this->categoriesObj->getCategoriesDropdownWithNoneOption();
	   $city = $this->cityObj->getCityDropdownByCityIdWithNoneOption($page_builder->city_id);
	   return view('administrator.page_builder.create',compact('page_builder','state','filterTable','categories','displayBannerAds','bannerAdsCategory','city'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('page_builder_delete')){ return abort(404); }
		
		$isdelete =$this->pageBuilderObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.page_builder.index')->with('success','Page Builder Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.page_builder.index')->with('error','Page Builder Is Not Deleted Successfully.');
		}
    }
    public function duplicate($id){
        $event =$this->pageBuilderObj->display($id);
        $data = $event->toArray();
        unset($data['page_builder_id']);
        $data['url_key'] = $this->duplicateRecord($data);
        $this->pageBuilderObj->saveRecord($data);
        return redirect()->route('administrator.page_builder.index')->with('success','Page Builder duplicate created successfully.');
    }

    function duplicateRecord($data){
        $data['url_key'] = $this->pageBuilderObj->generateDuplidateUrlKey($data['url_key']);
        $result = $this->pageBuilderObj->checkDuplicateUrlKey($data['url_key']);
        if($result == 1 || $result == '1'){
            $data['url_key'] = $this->duplicateRecord($data);
        }
        return $data['url_key'];
    }
}
