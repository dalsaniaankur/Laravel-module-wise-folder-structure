<?php
namespace App\Http\Controllers\Member\TeamReview;

use App\Classes\Models\Review\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Helpers\TeamReview\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{

    protected $teamReviewObj;
    protected $_helper;

    public function __construct(Review $teamReview){

        $this->teamReviewObj = $teamReview;
        $this->_helper = new Helper();
    }

    public function index(Request $request)
    {
        if (!Gate::allows('member_teams')){ return abort(404); }

        $page=0;
        $search='';
        if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
        $type = $this->_helper->getType();
        $member_id = $this->teamReviewObj->isLoginMember();
        $team_review = $this->teamReviewObj->listForTeam($search,$page,$type,$member_id);
        $totalRecordCount= $this->teamReviewObj->listTotalCount($search,$type,$member_id);
        $basePath=\Request::url().'?search='.$search.'&';
        $paging=$this->teamReviewObj->preparePagination($totalRecordCount,$basePath);
        return view('member.team_review.index',compact('team_review','paging'));
    }

    public function edit($id)
    {
        if (!Gate::allows('member_teams')){ return abort(404); }

        $team_review = $this->teamReviewObj->displayTeam($id);
        return view('member.team_review.create',compact('team_review'));
    }

    public function save(Request $request)
    {
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->teamReviewObj->saveRecord($data);
        $team_review =$this->teamReviewObj->displayTeam($result['id']);

        if($result['success']==false){
            return view('member.team_review.create',compact('team_review'))->withErrors($result['message']);
        }else{
            $request->session()->flash('success', $result['message']);
            return view('member.team_review.create',compact('team_review'));
        }
    }
}