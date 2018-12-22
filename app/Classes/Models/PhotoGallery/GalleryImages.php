<?php
namespace App\Classes\Models\PhotoGallery;

use DB;
use App\Classes\Models\BaseModel;
use App\Classes\Models\Images\Images;
use App\Classes\Models\State\State;
use App\Classes\Models\PhotoGallery\Gallery;
use App\Classes\Helpers\GalleryImages\Helper;

class GalleryImages extends BaseModel{
    
	protected $table = 'sbc_gallery_images';
    protected $primaryKey = 'gallery_image_id';
    
  	protected $entity='gallery_images';
	protected $searchableColumns=['title'];

    protected $fillable = ['title','sort','image_alt_Text','image_id','gallery_id'];
    protected $_helper;

	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new \App\Classes\Helpers\GalleryImages\Helper();
    }
	
	public function gallary()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id', 'gallery_id');
    }
	
	public function Images()
    {
        return $this->belongsTo(Images::class, 'image_id', 'image_id');
    }

	/**
	**	Model Filter Methods 
	*/	
	public function addGalleryFilter($gallery_id=0)
	{
		$this->queryBuilder->where('gallery_id',$gallery_id);
		return $this;
	}
	
	public function addGalleryImageIdFilter($gallery_image_id=0)
	{
		$this->queryBuilder->where('gallery_image_id',$gallery_image_id);
		return $this;
	}
	/*
	**	Logic Methods
	*/
	public function load($gallery_image_id)
    {
    	$this->beforeLoad($gallery_image_id);

	     $return =$this->setSelect()
	   			  ->addGalleryImageIdFilter($gallery_image_id)	
				  ->get()
				  ->first();

		$this->afterLoad($gallery_image_id, $return);	  

		return $return;
   	}

	public function list($search='',$page=0,$gallery_id)
	{
		$per_page=$this->_helper->getConfigPerPageRecord();
  		$list=$this->setSelect()
				   ->addSearch($search)
				   ->addGalleryFilter($gallery_id)
				   ->addPaging($page,$per_page)
				   ->get();
		
		return $list;
   	}
	
	public function listTotalCount($search='',$gallery_id)
	{
		$this->reset();
		$count=$this->setSelect()
				  ->addSearch($search)
				  ->addGalleryFilter($gallery_id)
				  ->get()
				  ->count();
		
		return $count;
	}
	
	public function preparePagination($total,$basePath)
	{
		$perpage=$this->_helper->getConfigPerPageRecord();
		$pageHelper=new \App\Classes\PageHelper($perpage,'page');
		$pageHelper->set_total($total); 
		$pageHelper->page_links($basePath);
		return $pageHelper->page_links($basePath);
	}
	
	public function saveRecord($data)
	{
		$rules=array();	
		$rules=[
			  'title'=> 'required',
			  'gallery_id'=>'required',
		];
		$validationResult=$this->validateData($rules,$data);
		$result=array();
		$result['id']='';
		if($validationResult['success']==false){
			$result['success']=false;
			$result['message']=$validationResult['message'];
			$result['id']=$data['id'];
			return $result;
		}
		
		if(!empty($data['gallery_image']))
		{
			$image = $data['gallery_image'];
			$gallary_image_name = $data['gallery_image']->getClientOriginalName();
			$gallary_image_name = str_replace('.'.$data['gallery_image']->getClientOriginalExtension().'','_'.time().'.'.$data['gallery_image']->getClientOriginalExtension(),$gallary_image_name);
			
			$destinationPath = public_path('/images/gallary');
			$image->move($destinationPath, $gallary_image_name);

			$image_data['image_name'] = $gallary_image_name;
			$image_data['image_path'] = 'images/gallary/'.$gallary_image_name;
			$image_data['module_id'] = $this->_helper->getModuleId();
	
			$image_for_module_wise = \App\Classes\Models\Images\Images::insert($image_data);
			$inserted_image_id = DB::getPdo()->lastInsertId();
		}
		
		if(!empty($inserted_image_id)){
			$data['image_id'] = $inserted_image_id;
		}
		if(empty($data['sort'])){
			unset($data['sort']);
		}
		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$gallery_images = self::findOrFail($data['id']);
		    $gallery_images ->update($data);	
		    $this->afterSave($data,$gallery_images);
			$result['id']=$gallery_images ->gallery_image_id;	
		}else{
		 	$gallery_images  = self::create($data);
			$result['id'] = $gallery_images->gallery_image_id;
			$this->afterSave($data,$gallery_images);
		}
		$result['success']=true;
		$result['message']="Gallery image Saved Successfully.";
		return $result;
	}
	
	public function display($id)
    {
	    $return =$this->load($id);
		return $return;
   	}
	
	public function removed($id)
	{
		$this->beforeRemoved($id);
		$galary=$this->load($id);
		if(!empty($galary)){
			 $delete = $galary->delete();
			 $this->afterRemoved($id);
			 return $delete;
		}
		return false;
	}
	
}