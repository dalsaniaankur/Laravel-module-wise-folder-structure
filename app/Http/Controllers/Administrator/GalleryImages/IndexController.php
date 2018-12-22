<?php
namespace App\Http\Controllers\Administrator\GalleryImages;

use Auth;
use App\Classes\Models\PhotoGallery\Gallery;
use App\Classes\Models\PhotoGallery\GalleryImages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\Images\Images;
use App\Classes\Helpers\GalleryImages\Helper;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller{
 
 	protected $galleryObj;
	protected $galleryImageObj;
	protected $imagesObj;
		
	protected $gallerries;
	protected $_helper;
	
	public function __construct(GalleryImages $galleryImages)
	{	
	    $this->galleryImageObj = $galleryImages;
		$this->galleryObj = new \App\Classes\Models\PhotoGallery\Gallery;
		$this->imagesObj = new Images();
		$this->_helper=new Helper();
	}
  
    public function index(Request $request)
	{
		if (!Gate::allows('team_photo_gallery')){ return abort(404); }

		$page=0;
		$search='';
		$gallary_id=0;
		if($request->get('page')){
            $page=$request->get('page');
        }
        if($request->get('search')){
            $search=trim($request->get('search'));
        }
		if($request->get('id')){
            $gallary_id=trim($request->get('id'));
        }

		$galleryImage = $this->galleryImageObj->list($search,$page,$gallary_id);
		$totalRecordCount= $this->galleryImageObj->listTotalCount($search,$gallary_id);
		$basePath=\Request::url().'?search='.$search.'&id='.$gallary_id.'&';
		$paging=$this->galleryImageObj->preparePagination($totalRecordCount,$basePath);
	
		return view('administrator.photogallery.galleryimages.index',compact('galleryImage','paging'));
    }

	public function save(Request $request)
	{
        $submitData = $request->all();
        $data = $submitData;
        $result = $this->galleryImageObj->saveRecord($data);
		$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
		$gallerries=$this->galleryObj->getAllGallaryDropdown();
		
		if(isset($result['id'])){
			$galleryImage = $this->galleryImageObj->display($result['id']);
			if($result['success']==false){
			    return view('administrator.photogallery.galleryimages.create',compact('gallerries','galleryImage','images'))->withErrors($result['message']);
			}else{
			 	$request->session()->flash('success', $result['message']);
			 	return view('administrator.photogallery.galleryimages.create',compact('gallerries','galleryImage','images')); 		
			}
		}else{ 
			if($result['success']==false){
				return view('administrator.photogallery.galleryimages.create',compact('gallerries','galleryImage','images'))->withErrors($result['message']);
			}else{
				$request->session()->flash('success', $result['message']);
				return view('administrator.photogallery.galleryimages.create',compact('gallerries','galleryImage','images'));
			}
		}
	}
  
    public function create()
	{
		if (!Gate::allows('team_photo_gallery_add')){ return abort(404); }

	    $gallerries=$this->galleryObj->getAllGallaryDropdown();
        $images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId()); 
	  
	  return view('administrator.photogallery.galleryimages.create',compact('gallerries','images'));
	}
    
	public function edit($id){ 

		if (!Gate::allows('team_photo_gallery_edit')){ return abort(404); }

	    $galleryImage = $this->galleryImageObj->display($id);
		$images=$this->imagesObj->getImagesByModuleId($this->_helper->getModuleId());
		$gallerries=$this->galleryObj->getAllGallaryDropdown();
	    
	    return view('administrator.photogallery.galleryimages.create',compact('gallerries','galleryImage','images'));
    }

    public function destroy($id)
	{
		if (!Gate::allows('team_photo_gallery_delete')){ return abort(404); }

		$isdelete =$this->galleryImageObj->removed($id);
		if($isdelete){
			 return redirect()->route('administrator.gallery_images.index')->with('success','Record Deleted.');
		}else{
			 return redirect()->route('administrator.gallery_images.index')->with('error','Record not deleted.');
		}
    }
}
