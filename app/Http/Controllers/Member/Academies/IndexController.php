<?php
namespace App\Http\Controllers\Member\Academies;

use App\Classes\Models\Academies\Academies;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\Members\Members;
use App\Classes\Models\Images\Images;
use App\Classes\Models\Services\Services;
use App\Classes\Helpers\Academies\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{

    protected $academyObj;
    protected $imagesObj;
    protected $stateObj;
    protected $memberObj;
    protected $servicesObj;
    protected $cityObj;
    protected $_helper;

    public function __construct(Academies $academies)
    {
        $this->academyObj = $academies;
        $this->imagesObj = new Images();
        $this->stateObj = new State();
        $this->memberObj = new Members();
        $this->servicesObj = new Services();
        $this->cityObj = new City();
        $this->_helper = new Helper();
    }

    public function index(Request $request)
    {
        if (!Gate::allows('member_academies')){ return abort(404); }

        $page=0;
        $search='';
        if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
        $member_id = $this->academyObj->isLoginMember();
        $academies = $this->academyObj->list($search,$page, $state_id=0, $city_id=0, $redius=0, $isFront=0, $is_active=2, $latitude='', $longitude='', $academy_name='', $service_id=array(), $sortedBy='', $sortedOrder='', $per_page=0, $selectColoumn=array('*'), $member_id);
        $totalRecordCount= $this->academyObj->listTotalCount($search,$page=0, $state_id=0, $city_id=0, $redius=0, $isFront=0, $is_active=2, $latitude='', $longitude='', $academy_name='', $service_id=array(), $sortedBy='', $sortedOrder='', $member_id);
        $basePath=\Request::url().'?search='.$search.'&';
        $paging=$this->academyObj->preparePagination($totalRecordCount,$basePath);
        $entity = $this->academyObj->getEntity();

        return view('member.academies.index',compact('academies','paging','member_id','entity'));
    }

    public function save(Request $request)
    {
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->academyObj->saveRecord($data);

        $show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
        $services = $this->servicesObj->getServicesCheckboxListByModuleId($this->_helper->getModuleId());
        $city = array();
        $member_id = $this->academyObj->isLoginMember();
        if(isset($result['id'])){

            $academies =$this->academyObj->display($result['id']);
            $city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
            if($result['success']==false){
                return view('member.academies.create',compact('academies','state','member_id','show_advertise','images','services','city'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('member.academies.create',compact('academies','state','member_id','show_advertise','images','services','city'));
            }
        }else{

            if($result['success']==false){
                return view('member.academies.create',compact('state','member_id','show_advertise','images','services','city'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('member.academies.create',compact('state','member_id','show_advertise','images','services','city'));
            }
        }
    }

    public function create()
    {
        if (!Gate::allows('member_academies')){ return abort(404); }

        $show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
        $services = $this->servicesObj->getServicesCheckboxListByModuleId($this->_helper->getModuleId());
        $member_id = $this->academyObj->isLoginMember();
        $city = array();
        $defaultApprovalStatus = $this->_helper->getDefaultApprovalStatus();

        return view('member.academies.create',compact('state','member_id','show_advertise','images','services','city','defaultApprovalStatus'));
    }

    public function edit($id){

        if (!Gate::allows('member_academies')){ return abort(404); }

        $academies = $this->academyObj->display($id);
        $show_advertise = $this->_helper->getShowAdvertise();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
        $member_id = $this->academyObj->isLoginMember();
        $services = $this->servicesObj->getServicesCheckboxListByModuleId($this->_helper->getModuleId());
        $city = $this->cityObj->getCityDropdownByCityId($academies->city_id);

        return view('member.academies.create',compact('academies','state','member_id','show_advertise','images','services','city'));
    }

    public function destroy($id){

        if (!Gate::allows('member_academies')){ return abort(404); }

        $isdelete =$this->academyObj->removed($id);
        if($isdelete){
            return redirect()->route('member.academies.index')->with('success','Academy Deleted Successfully.');
        }else{
            return redirect()->route('member.academies.index')->with('error','Academy Is Not Deleted Successfully.');
        }
    }
}
