<?php
namespace App\Classes\Models\PhotoGallery;

use DB;
use App\Classes\Models\BaseModel;
use App\Classes\Models\Images\Images;
use App\Classes\Models\State\State;
use App\Classes\Helpers\Gallery\Helper;

class Gallery extends BaseModel{
    
	protected $table = 'sbc_gallery';
    protected $primaryKey = 'gallery_id';
    
  	protected $entity='gallery';
	protected $searchableColumns=['name'];

    protected $fillable = ['name','sort','image_alt_Text','image_id'];
    protected $_helper;

	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new \App\Classes\Helpers\Gallery\Helper();
    }
	
	public function Images()
    {
        return $this->belongsTo(Images::class, 'image_id', 'image_id');
    }

	/**
	**	Model Filter Methods 
	*/	
	public function addGalleryId($gallery_id=0)
	{
		$this->queryBuilder->where('gallery_id',$gallery_id);
		return $this;
	}
	/*
	**	Logic Methods
	*/
	public function load($gallery_id)
    {
    	$this->beforeLoad($gallery_id);

	    $return =$this->setSelect()
	   			  ->addGalleryId($gallery_id)	
				  ->get()
				  ->first();
				  
		$this->afterLoad($gallery_id, $return);		  
		return $return;
   	}
	
	public function list($search='',$page=0)
	{
		$per_page=$this->_helper->getConfigPerPageRecord();
  		$list=$this->setSelect()
				   ->addSearch($search)
				   ->addPaging($page,$per_page)
				   ->get();
		
		return $list;
   	}
	
	public function listTotalCount($search='')
	{
		$this->reset();
		$count=$this->setSelect()
				  ->addSearch($search)
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
			  'name'=> 'required',
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
		if(isset($data['id']) && $data['id'] !=''){   
		  	$gallary = self::findOrFail($data['id']);
		    $gallary ->update($data);	
			$result['id']=$gallary ->gallery_id;	
		}else{
		 	$gallary  = self::create($data);
			$result['id'] = $gallary->gallery_id;
		}
		$result['success']=true;
		$result['message']="Gallery Saved Successfully.";
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
	
	public function getAllGallaryDropdown()
    {	
	   $return =$this->setSelect()
	  		 	  ->orderBy('sort', 'asc')
				  ->pluck('name', 'gallery_id');
		return $return;
   	}

}