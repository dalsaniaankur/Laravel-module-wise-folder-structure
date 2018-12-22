<?php
namespace App\Http\Controllers\Member\ShowcaseOrganization;

use Auth;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization;
use App\Classes\Models\Members\Members;
use App\Classes\Models\Images\Images;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Helpers\ShowcaseOrganization\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{

    protected $showcaseOrganizationObj;
    protected $imagesObj;
    protected $stateObj;
    protected $memberObj;
    protected $cityObj;
    protected $_helper;

    public function __construct(ShowcaseOrganization $showcaseOrganization)
    {
        $this->showcaseOrganizationObj = $showcaseOrganization;
        $this->stateObj = new State();
        $this->imagesObj = new Images();
        $this->memberObj = new Members();
        $this->cityObj = new City();
        $this->_helper = new Helper();
    }

    public function index(Request $request)
    {
        if (!Gate::allows('member_showcase')){ return abort(404); }

        $page=0;
        $search='';
        if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
        $submitted_by_id = $this->showcaseOrganizationObj->isLoginMember();
        $showcaseOrganizations = $this->showcaseOrganizationObj->list($search, $page, $submitted_by_id);
        $totalRecordCount= $this->showcaseOrganizationObj->listTotalCount($search, $submitted_by_id);
        $basePath=\Request::url().'?search='.$search.'&';
        $paging=$this->showcaseOrganizationObj->preparePagination($totalRecordCount,$basePath);
        $entity = $this->showcaseOrganizationObj->getEntity();
        return view('member.showcase_organization.organization.index',compact('showcaseOrganizations','paging','entity'));
    }

    public function save(Request $request)
    {
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->showcaseOrganizationObj->saveRecord($data);

        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
        $city = array();
        $submitted_by_id = $this->showcaseOrganizationObj->isLoginMember();

        if(isset($result['id'])){
            $showcaseOrganization =$this->showcaseOrganizationObj->load($result['id']);
            $city = $this->cityObj->getCityDropdownByCityId($submitData['city_id']);
            if($result['success']==false){
                return view('member.showcase_organization.organization.create',compact('showcaseOrganization','state','submitted_by_id','images','city'))->withErrors($result['message']);
            }else{
                $request->session()->flash('success', $result['message']);
                return view('member.showcase_organization.organization.create',compact('showcaseOrganization','state','submitted_by_id','images','city'));
            }
        }else{
            if($result['success']==false){
                return view('member.showcase_organization.organization.create',compact('state','submitted_by_id','images','city'))->withErrors($result['message']);

            }else{
                $request->session()->flash('success', $result['message']);
                return view('member.showcase_organization.organization.create',compact('state','submitted_by_id','images','city'));
            }
        }
    }

    public function create(){

        if (!Gate::allows('member_showcase')){ return abort(404); }

        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
        $city = array();
        $submitted_by_id = $this->showcaseOrganizationObj->isLoginMember();
        return view('member.showcase_organization.organization.create',compact('state','submitted_by_id','images','city'));
    }

    public function edit($id){

        if (!Gate::allows('member_showcase')){ return abort(404); }

        $showcaseOrganization = $this->showcaseOrganizationObj->display($id);
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
        $state = $this->stateObj->getStateDropdown();
        $city = $this->cityObj->getCityDropdownByCityId($showcaseOrganization->city_id);
        $submitted_by_id = $this->showcaseOrganizationObj->isLoginMember();

        return view('member.showcase_organization.organization.create',compact('showcaseOrganization','state','submitted_by_id','images','city'));
    }

    public function destroy($id){

        if (!Gate::allows('member_showcase')){ return abort(404); }
        $isdelete =$this->showcaseOrganizationObj->removed($id);
        if($isdelete){
            return redirect()->route('member.showcase_organization.index')->with('success','Showcase Organization Deleted Successfully.');
        }else{
            return redirect()->route('member.showcase_organization.index')->with('error','Showcase Organization Is Not Deleted Successfully');
        }
    }
}
