<?php
namespace App\Http\Controllers\Administrator\Events;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\Images\Images;
use App\Classes\Helpers\Event\Helper;
use App\Classes\Models\Event\Event;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{
  
	protected $eventObj;
	protected $imagesObj;
	protected $_helper;

	public function __construct(Event $event)
	{	
        $this->eventObj = $event;
        $this->imagesObj = new Images();
        $this->_helper = new Helper();
    }
  
    public function index(Request $request)
	{
		if (!Gate::allows('events')){
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
		$events = $this->eventObj->list($search,$page);
		$totalRecordCount= $this->eventObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->eventObj->preparePagination($totalRecordCount,$basePath);

		return view('administrator.events.index',compact('events','paging'));
    }

	public function save(Request $request)
	{
		
        $submitData = $request->all();
        $data 	= $submitData;
        $result = $this->eventObj->saveRecord($data);
		$images = $this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());

		if(isset($result['id']))
		{
			$event =$this->eventObj->display($result['id']);
			if($result['success']==false){
			    return view('administrator.events.create',compact('images','event'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.events.create',compact('images','event')); 
			}
		}else{
			if($result['success']==false){
			    return view('administrator.events.create',compact('images'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.events.create',compact('images'));
			}
		}
	}
  
    public function create()
	{
		if (!Gate::allows('event_add')){
           return abort(404);
        }
    	$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
    	return view('administrator.events.create',compact('images'));
	}
    
	public function edit($id)
	{ 
	   if (!Gate::allows('event_edit')){
           return abort(404);
       }
	   $event = $this->eventObj->display($id);
	   $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
	   return view('administrator.events.create',compact('event','images'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('event_delete')){
           return abort(404);
        }
		$isdelete =$this->eventObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.events.index')->with('success','Event Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.events.index')->with('error','Event Is Not Deleted Successfully.');
		}
    }
    public function duplicate($id){
        $event =$this->eventObj->display($id);
        $data = $event->toArray();
        unset($data['event_id']);
        $data['url_key'] = $this->duplicateRecord($data);
        $this->eventObj->saveRecord($data);
        return redirect()->route('administrator.events.index')->with('success','Event duplicate created successfully.');
    }

    function duplicateRecord($data){
        $data['url_key'] = $this->eventObj->generateDuplidateUrlKey($data['url_key']);
        $result = $this->eventObj->checkDuplicateUrlKey($data['url_key']);
        if($result == 1 || $result == '1'){
            $data['url_key'] = $this->duplicateRecord($data);
        }
        return $data['url_key'];
    }
}
