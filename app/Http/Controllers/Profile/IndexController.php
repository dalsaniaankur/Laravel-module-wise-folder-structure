<?php
namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\State\State;
use App\Classes\Models\Description\Description;
use App\Classes\Models\Members\Members;

class IndexController extends Controller{
    
    protected $memberObj;
    protected $descriptionObj;
    protected $stateObj;

    public function __construct()
    {
        $this->middleware('member');
        $this->descriptionObj = new Description();
        $this->stateObj = new State();
        $this->memberObj = new \App\Classes\Models\Members\Members();
        
    }
    public function showProfile(){

        $member = $this->memberObj->getCurrentLoginMember();
        $state = $this->stateObj->getStateDropdown();
        $description = $this->descriptionObj->checkboxList();
        return view('auth.member.profile', compact('member','state','description'));
    }
     public function changeProfile(Request $request){
        
        $data = $request->all();
        $result = $this->memberObj->changeProfile($data);
        
        $member =$this->memberObj->display($result['id']);
        $state = $this->stateObj->getStateDropdown();
        $description = $this->descriptionObj->checkboxList();

        if($result['success']==false){
            return view('auth.member.profile', compact('member','state','description'))->withErrors($result['message']);;
        }else{
            $request->session()->flash('success', $result['message']);
            return redirect('member/profile');
        }
    }
}
