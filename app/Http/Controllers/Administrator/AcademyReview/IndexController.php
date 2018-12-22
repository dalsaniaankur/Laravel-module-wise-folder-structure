<?php
namespace App\Http\Controllers\Administrator\AcademyReview;

use App\Classes\Models\Review\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Helpers\AcademyReview\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller
{
	protected $academyReviewObj;
    protected $_helper;
		 
	public function __construct(Review $academyReview)
	{	
        $this->academyReviewObj = $academyReview;
        $this->_helper = new Helper();
    }
  
    public function index(Request $request)
	{
		if (!Gate::allows('academie_review_submissions')){
           return abort(404);
        }
		
		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }

        $type = $this->_helper->getType();
		$academy_review = $this->academyReviewObj->listForAcademy($search,$page,$type);
		$totalRecordCount= $this->academyReviewObj->listTotalCount($search,$type);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->academyReviewObj->preparePagination($totalRecordCount,$basePath);

		return view('administrator.academy_review.index',compact('academy_review','paging'));
    }

    public function edit($id)
	{ 
       if (!Gate::allows('academie_review_submissions')){
          return abort(404);
       }
	   $academy_review = $this->academyReviewObj->displayAcademy($id);
	   return view('administrator.academy_review.create',compact('academy_review'));
    }

    public function save(Request $request){
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->academyReviewObj->saveRecord($data);
		$academy_review =$this->academyReviewObj->displayAcademy($result['id']);

		if($result['success']==false){
		    return view('administrator.academy_review.create',compact('academy_review'))->withErrors($result['message']);
		}else{
		 	$request->session()->flash('success', $result['message']);
		 	return view('administrator.academy_review.create',compact('academy_review')); 
		}
	}
}