<?php
namespace App\Http\Controllers\Administrator\InstructorReview;

use App\Classes\Models\Review\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\Instructors\Instructors;
use App\Classes\Helpers\InstructorReview\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller
{
    protected $instructorReviewObj;
	protected $_helper;		

	public function __construct(Review $instructorReview)
	{	
        $this->instructorReviewObj = $instructorReview;
        $this->_helper = new Helper();
    }
  
    public function index(Request $request)
	{
		if (!Gate::allows('instructor_review_submissions')){
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
		$instructor_review = $this->instructorReviewObj->listForInstructor($search,$page,$type);
		$totalRecordCount= $this->instructorReviewObj->listTotalCount($search,$type);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->instructorReviewObj->preparePagination($totalRecordCount,$basePath);
		return view('administrator.instructor_review.index',compact('instructor_review','paging'));
    }

    public function edit($id)
	{ 
	   if (!Gate::allows('instructor_review_submissions')){
           return abort(404);
       }

       $instructor_review = $this->instructorReviewObj->displayInstructor($id);
	   return view('administrator.instructor_review.create',compact('instructor_review'));
    }

    public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->instructorReviewObj->saveRecord($data);
		$instructor_review =$this->instructorReviewObj->displayInstructor($result['id']);

		if($result['success']==false){
		    return view('administrator.instructor_review.create',compact('instructor_review'))->withErrors($result['message']);
		}else{
		 	$request->session()->flash('success', $result['message']);
		 	return view('administrator.instructor_review.create',compact('instructor_review')); 
		}
	}
}