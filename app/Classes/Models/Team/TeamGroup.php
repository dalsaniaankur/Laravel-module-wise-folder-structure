<?php
namespace App\Classes\Models\Team;

use DB;
use App\Classes\Models\BaseModel;
use App\Classes\Models\Images\Images;
use App\Classes\Models\Team\TeamToGroup;
use App\Classes\Helpers\Team\Helper;

class TeamGroup extends BaseModel
{
	protected $table = 'sbc_team_group';
    protected $primaryKey = 'team_group_id';
   
    protected $entity='team_group';
	protected $searchableColumns=['name'];

    protected $fillable = ['name','image_id'];

	protected $_helper;
	
	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
		
		$this->_helper =new Helper();
    }
	
	public function Images()
    {
        return $this->belongsTo(Images::class, 'image_id', 'image_id');
    }

	public function TeamToGroup()
    {
		  return $this->hasMany('\App\Classes\Models\Team\TeamToGroup');
    }
	
	/**
	**	Model Filter Methods 
	*/	
	public function addTeamGroupIdFilter($team_group_id=0)
	{
		$this->queryBuilder->where('team_group_id',$team_group_id);
		return $this;
	}
	/*
	**	Logic Methods
	*/
	
	public function load($team_group_id)
    {
    	$this->beforeLoad($team_group_id);

	    $return =$this->setSelect()
	   			  ->addTeamGroupIdFilter($team_group_id)
				  ->get()
				  ->first();

		$this->afterLoad($team_group_id, $return);
				  
		return $return;
   	}
	
	public function list($search='',$page=0)
	{
		$perpage=$this->_helper->getConfigPerPageRecord();
  		$list=$this->setSelect()
				   ->addSearch($search)
				   ->addPaging($page,$perpage)
				   ->get();
		
		if(count($list)>0)
		{
			$teamToGroupObj = new \App\Classes\Models\Team\TeamToGroup();
			foreach($list as $row){
				$team_list=array();
				$team_list = $teamToGroupObj->getAllTeamForGroup($row->team_group_id);
				$row['teams']=$team_list;	
			}
		}
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
			
			$destinationPath = public_path('/images/group');
			$image->move($destinationPath, $gallary_image_name);

			$image_data['image_name'] = $gallary_image_name;
			$image_data['image_path'] = 'images/group/'.$gallary_image_name;
			$image_data['module_id'] =$this->_helper->getModuleId();
	
			$image_for_module_wise = \App\Classes\Models\Images\Images::insert($image_data);
			$inserted_image_id = DB::getPdo()->lastInsertId();
		}
		
		$teamGroupData=array();
		$teamGroupData['name']=$data['name'];
		
		$teamToGroupData=array();
		if(isset($data['sort'])){
			$teamToGroupData['sort']=$data['sort'];
		}
		if(!empty($inserted_image_id)){
			$data['image_id'] = $inserted_image_id;
			$teamGroupData['image_id']=$inserted_image_id;
		}
	
		if(empty($data['sort'])){
			unset($data['sort']);
			unset($teamToGroupData['sort']);
		}

		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$teamGroup = self::findOrFail($data['id']);
		    $teamGroup ->update($teamGroupData);
		    $this->afterSave($data,$teamGroup);
			$result['id']=$teamGroup ->team_group_id;
		}else{
		 	$teamGroup  = self::create($data);
			$result['id'] = $teamGroup->team_group_id;
		}
		if(!empty($data['team_id'])){
			$teamToGroupData['team_id']=$data['team_id'];
			$teamToGroupData['team_group_id']=$result['id'];
			$teamToGroupEntity=\App\Classes\Models\Team\TeamToGroup::create($teamToGroupData);
			$this->afterSave($data,$teamGroup);
		}
		
		$result['success']=true;
		$result['message']="Team Group Saved Successfully.";
		return $result;
	}
	
	public function display($id)
    {
	    $return =$this->load($id);
		$teamToGroupObj = new \App\Classes\Models\Team\TeamToGroup();
		$team_list = $teamToGroupObj->getAllTeamForGroup($id);
		$return['teams']=$team_list;	
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