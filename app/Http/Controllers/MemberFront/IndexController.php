<?php
namespace App\Http\Controllers\MemberFront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Models\Members\Members;
use App\Classes\Models\MemberModulePermission\MemberModulePermission;
use App\Classes\Helpers\Member\Helper;

class IndexController extends Controller{

    protected $memberObj;
    protected $_helper;
    protected $memberModulePermission;

    public function __construct(){

        $this->memberObj = new Members();
        $this->_helper = new Helper();
        $this->memberModulePermission = new MemberModulePermission();
    }

    public function index(Request $request, $member_id, $url_key){
        $memberModuleList = $this->memberModulePermission->getMemberModuleListByMemberId($member_id);
        return view('member_front.index',compact('url_key','memberModuleList'));
    }
}
