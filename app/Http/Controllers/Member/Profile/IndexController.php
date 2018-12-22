<?php
namespace App\Http\Controllers\Member\Profile;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Classes\Models\Members\Members;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\Description\Description;
use App\Classes\Helpers\Member\Helper;
use App\Classes\Models\MemberModulePermission\MemberModulePermission;

class IndexController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */

    protected $memberObj;
    protected $descriptionObj;
    protected $stateObj;
    protected $cityObj;
    protected $_helper;
    protected $memberModulePermission;

    public function __construct(Members $members)
    {
        $this->memberObj = $members;
        $this->descriptionObj = new Description();
        $this->stateObj = new State();
        $this->cityObj = new City();
        $this->_helper = new Helper();
        $this->memberModulePermission = new MemberModulePermission();
    }

   /**
     * Show the form for profile.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showProfile()
    {
        $member_id = Auth::guard('member')->user()->member_id;
        $member =$this->memberObj->display($member_id);
        $state = $this->stateObj->getStateDropdown();
        $description = $this->descriptionObj->checkboxList();
        $city = $this->cityObj->getCityDropdownByCityId($member->city_id);
        $moduleList = $this->_helper->getModuleList();
        $memberModuleList = $this->memberModulePermission->getMemberModuleListByMemberId($member_id);
        return view('member.profile',compact('member','state','description','city','moduleList','memberModuleList'));
    }

    public function changeProfile(Request $request)
    {
        $submitData = $request->all();
        $data = $submitData;
        $result=$this->memberObj->saveRecord($data);

        $state = $this->stateObj->getStateDropdown();
        $description = $this->descriptionObj->checkboxList();
        $city = array();
        if(isset($result['id'])){
            $member =$this->memberObj->display($result['id']);
            $city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
            if($result['success']==false){
                return view('member.profile',compact('state','member','description','city'))->withErrors($result['message']);
            }else{
                return redirect()->route('member_profile')->with('success', 'Profile change Successfully','');
            }
        }else{
            if($result['success']==false){
                return view('member.profile',compact('state','description','city'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return redirect()->route('member_profile')->with('success', 'Profile change Successfully','');
            }
        }
    }
}
