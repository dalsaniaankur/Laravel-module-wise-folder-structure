<?php
namespace App\Http\Controllers\Administrator\BannerTracking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Helpers\BannerTracking\Helper;
use App\Classes\Models\BannerTracking\BannerTracking;
use App\Classes\Models\BannerAds\BannerAds;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{
  
	protected $bannerTrackingObj;
	 
	public function __construct(BannerTracking $bannerTracking){	

        $this->bannerTrackingObj = $bannerTracking;
        $this->bannerAdsObj = new BannerAds();
        $this->_helper = new Helper();
    }
  
    public function index(Request $request){
    	
		if (!Gate::allows('banner_trackings')){ return abort(404); }
		
		$page=0;
		$search='';
		if($request->get('page')){ $page=$request->get('page'); }
        if($request->get('search')){ $search=trim($request->get('search'));}
        $start_date = !empty($request->get('start_date')) ? $request->get('start_date') : '';
        $end_date = !empty($request->get('end_date')) ? $request->get('end_date') : '';

		$bannerTracking = $this->bannerTrackingObj->list($search,$page, $start_date, $end_date);
		$totalRecordCount= $this->bannerTrackingObj->listTotalCount($search, $start_date, $end_date);
		$basePath=\Request::url().'?search='.$search.'&start_date='.$start_date.'&end_date='.$end_date.'&';
		$paging=$this->bannerTrackingObj->preparePagination($totalRecordCount,$basePath);
		
		return view('administrator.banner_tracking.index',compact('bannerTracking','paging'));
    }

    public function getDownloadCsvForBannerTracking(Request $request, $fileName){
		$resultFilePath=public_path("/exports/banner_tracking/".$fileName);
		return response()->download("{$resultFilePath}");
	}

    public function postExportCsv(Request $request){

		$data = $request->all();
		
		$search = !empty(trim($data['search'])) ? $data['search'] : '';
		$start_date = !empty(trim($data['start_date'])) ? $data['start_date'] : '';
		$end_date = !empty(trim($data['end_date'])) ? $data['end_date'] : '';

		$bannerTracking = $this->bannerTrackingObj->listWithoutPagination($search, $start_date, $end_date);

		$headerLable = ['Banner Ads', '	Page Url', 'IP Address', 'Redirect Link', 'Banner Tracking Date']; 
		
		$filePath = public_path("/exports/banner_tracking/");
		$filename = "banner_tracking-".time().".csv";
		$filePath = $filePath.$filename;
		$handle = fopen($filePath, 'w+');
		$headerLable = ['Banner Ads', '	Page Url', 'IP Address', 'Redirect Link', 'Banner Tracking Date']; 
		fputcsv($handle, $headerLable);

		if(!empty($bannerTracking)){
			
			foreach ($bannerTracking as $key => $value) {
				
				$csvRow = [$value->banner_ads_title, 
						  $value->page_url, 
						  $value->ip_address, 
						  $value->banner_redirect_link, 
						  \DateFacades::dateFormat($value->created_at,'formate-4')]; 

				fputcsv($handle,$csvRow);		 
			}
		}
		fclose($handle);
		$fileUrl = \URL::to('administrator/download_csv_for_banner_tracking/'.$filename);
		return response()->json([ 'success' => true, 'file_url' => $fileUrl, ]);
    }	
    

	public function save(Request $request){
		
		$submitData = $request->all();
		$data = $submitData;
		$result = $this->bannerTrackingObj->saveRecord($data);
		$bannerAdsList = $this->bannerAdsObj->getBannerAdsDropdown();

		if(isset($result['id']))
		{
			$bannerTracking =$this->bannerTrackingObj->display($result['id']);
			if($result['success']==false){
			    return view('administrator.banner_tracking.create',compact('bannerTracking','bannerAdsList'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.banner_tracking.create',compact('bannerTracking','bannerAdsList')); 
			}
		}else{
			if($result['success']==false){
			    return view('administrator.banner_tracking.create',compact('bannerAdsList'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.banner_tracking.create',compact('bannerAdsList'));
			}
		}
	}
    
	public function edit($id){ 

	   if (!Gate::allows('banner_tracking_edit')){ return abort(404); }
	   
	   $bannerTracking = $this->bannerTrackingObj->display($id);
	   $bannerAdsList = $this->bannerAdsObj->getBannerAdsDropdown();
	   return view('administrator.banner_tracking.create',compact('bannerTracking','bannerAdsList'));
    }

    public function destroy($id){

		if (!Gate::allows('banner_tracking_delete')){ return abort(404); }
		
		$isdelete = $this->bannerTrackingObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.banner_tracking.index')->with('success','Banner Tracking Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.banner_tracking.index')->with('error','Banner Tracking Is Not Deleted Successfully.');
		}
    }
}
