<?php
namespace App\Http\Controllers\Administrator\Gallery;

use Auth;
use App\Classes\Models\PhotoGallery\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\Images\Images;
use App\Classes\Helpers\Gallery\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller
{
	protected $galleryObj;
	protected $imagesObj;
	
	protected $_helper;
	
	public function __construct(Gallery $gallery)
	{	
	 	$this->imagesObj = new Images();
        $this->galleryObj = $gallery;
		$this->_helper = new Helper();
    }
  
    public function index(Request $request)
	{
		if (!Gate::allows('team_photo_gallery')){ return abort(404); }

		$page=0;
		$search='';
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		$gallery = $this->galleryObj->list($search,$page);
		$totalRecordCount= $this->galleryObj->listTotalCount($search);
		$basePath=\Request::url().'?search='.$search.'&';
		$paging=$this->galleryObj->preparePagination($totalRecordCount,$basePath);
	
		return view('administrator.photogallery.index',compact('gallery','paging'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->galleryObj->saveRecord($data);
		$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
		if(isset($result['id'])){

			$gallery = $this->galleryObj->display($result['id']);
			if($result['success']==false){
			    return view('administrator.photogallery.create',compact('gallery','images'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.photogallery.create',compact('gallery','images')); 		
			}
		}else{ 
			if($result['success']==false){
				return view('administrator.photogallery.create',compact('gallery','images'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.photogallery.create',compact('gallery','images'));
			}
		}
	}
  
    public function create()
	{
		if (!Gate::allows('team_photo_gallery_add')){ return abort(404); }

	   $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
       return view('administrator.photogallery.create',compact('gallery','images'));
	}
    
	public function edit($id)
	{ 
		if (!Gate::allows('team_photo_gallery_edit')){ return abort(404); }

	    $gallery = $this->galleryObj->display($id);
		$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
	    return view('administrator.photogallery.create',compact('gallery','images'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('team_photo_gallery_delete')){ return abort(404); }
		
		$isdelete =$this->galleryObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.gallery.index')->with('success','Photo Gallery Deleted Successfully.');
		}else{
			 return redirect()->route('administrator.gallery.index')->with('error','Photo Is Not Gallery Deleted Successfully.');
		}
    }
}
