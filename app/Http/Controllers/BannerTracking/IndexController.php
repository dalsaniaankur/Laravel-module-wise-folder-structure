<?php

namespace App\Http\Controllers\BannerTracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Models\BannerTracking\BannerTracking;
use App\Classes\Helpers\BannerTracking\Helper;
use DB;

class IndexController extends Controller{

    protected $bannerTrackingObj;
    protected $_helper;

    public function __construct(BannerTracking $bannerTrackingObj){

        $this->BannerTracking = $bannerTrackingObj;
        $this->_helper = new Helper();
    }

    public function postBannerTracking(Request $request){
        $data = $request->all();
        $response = $this->BannerTracking->postBannerTracking($data);
        return response()->json($response);
    }
}
