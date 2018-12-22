<?php

namespace App\Http\Controllers\Auth\Member;

use App\Http\Controllers\Controller;
use App\Classes\Models\Members\Members;
use App\Classes\Models\State\State;
use App\Classes\Models\Description\Description;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Classes\Models\City\City;
use App\Classes\Helpers\Member\Helper;
use App\Classes\Models\MemberModulePermission\MemberModulePermission;

class RegisterController extends Controller{
    
    use RegistersUsers;

    protected $redirectTo = '/member/home';
    protected $memberObj;
    protected $descriptionObj;
    protected $stateObj;
    protected $cityObj;
    protected $_helper;
    protected $memberModulePermission;

    public function __construct()
    {
        $this->descriptionObj = new Description();
        $this->stateObj = new State();
        $this->memberObj = new \App\Classes\Models\Members\Members();
        $this->cityObj = new City();
        $this->_helper = new Helper();
        $this->memberModulePermission = new MemberModulePermission();
    }

    public function showRegistrationForm()
	{
        $state = $this->stateObj->getStateDropdown();
        $description = $this->descriptionObj->checkboxList();
        $cityList  = array();
        $moduleList = $this->_helper->getModuleList();
        $memberModuleList = array();
        return view('auth.member.register',compact('state','description','cityList','moduleList','memberModuleList'));
    }
    
    public function register(Request $request){

        $data = $request->all();
        $result = $this->memberObj->saveRegistrationRecord($data);
        $cityList = array();
        if($result['success']==false){

            $cityList = $this->cityObj->getCityDropdownByCityId($data['city_id']);
            $state = $this->stateObj->getStateDropdown();
            $description = $this->descriptionObj->checkboxList();
            $moduleList = $this->_helper->getModuleList();
            $memberModuleList = array();
            return view('auth.member.register',compact('state','member','description','cityList','moduleList','memberModuleList'))->withErrors($result['message']);

        }else{
            
            $request->session()->flash('success', $result['message']);
            return redirect('/member/home');
        }
    }
}