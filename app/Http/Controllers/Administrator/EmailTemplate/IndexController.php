<?php
namespace App\Http\Controllers\Administrator\EmailTemplate;

use App\Classes\Models\EmailTemplate\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Helpers\EmailTemplate\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller
{
	protected $emailTemplateObj;
	protected $_helper;

	public function __construct(EmailTemplate $emailTemplate)
	{	
        $this->emailTemplateObj = $emailTemplate;
        $this->_helper = new \App\Classes\Helpers\EmailTemplate\Helper();
    }

    public function index(Request $request)
	{
		if (!Gate::allows('email_templates')){ return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$email_template = $this->emailTemplateObj->list($search,$page);
		$totalRecordCount= $this->emailTemplateObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->emailTemplateObj->preparePagination($totalRecordCount,$basePath);
		return view('administrator.email_template.index',compact('email_template','paging'));
    }

	public function save(Request $request){

        $submitData = $request->all();
        $data = $submitData;
        
        $entity = $this->_helper->getEntityTypeDropDown();
        $result = $this->emailTemplateObj->saveRecord($data);
		if(isset($result['id']))
		{
			$email_template =$this->emailTemplateObj->display($result['id']);
			if($result['success']==false){
			    return view('administrator.email_template.create',compact('email_template','entity'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.email_template.create',compact('email_template','entity')); 
			}
		}else{
			if($result['success']==false){
			    return view('administrator.email_template.create',compact('entity'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.email_template.create',compact('entity'));
			}
		}
	}
  
    public function create()
	{
    	if (!Gate::allows('email_template_add')){ return abort(404); }

		$entity = $this->_helper->getEntityTypeDropDown();
    	return view('administrator.email_template.create',compact('entity'));
	}
    
	public function edit($id)
	{ 
		if (!Gate::allows('email_template_edit')){ return abort(404); }

	   $email_template = $this->emailTemplateObj->display($id);
	   $entity = $this->_helper->getEntityTypeDropDown();
	   return view('administrator.email_template.create',compact('email_template','entity'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('email_template_delete')){ return abort(404); }
		
		$isdelete =$this->emailTemplateObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.email_template.index')->with('success','Email Template Deleted.');
		}else{
			 return redirect()->route('administrator.email_template.index')->with('error','Email Template Is Not deleted.');
		}
    }
	
}