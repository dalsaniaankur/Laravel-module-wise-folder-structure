<?php
namespace App\Classes\Models\AgeGroup;
use DB;
use App\Classes\Models\BaseModel;
use App\Classes\Helpers\AgeGroup\Helper;

class AgeGroup extends BaseModel
{
    protected $table = 'sbc_age_group';
    protected $primaryKey = 'age_group_id';
    protected $entity='age_group';
	protected $searchableColumns=['name'];
		
	protected $fillable = ['age_group_id', 'name', 'short_order', 'status','module_id','created_at', 'updated_at'];
	protected $_helper;

	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
    }
	
	public function addStatusFilter($status = -1){
	    if($status != -1) {
            $this->queryBuilder->where('status', $status);
        }
		return $this;
	}

    public function getStatusToString(){
        return ($this->status == 1) ? 'Active' : 'Inactive';
    }

	public function addNameFilter($name)
	{
		$this->queryBuilder->where('name',$name);
		return $this;
	}

    public function addModuleIdFilter($module_id = 0)
    {
        if($module_id > 0) {
            $this->queryBuilder->where('module_id', $module_id);
        }
        return $this;
    }

	public function addOrderBy($columeName, $orderBy){
		
		$this->queryBuilder->orderBy($columeName, $orderBy);
		return $this;
	}

	public function getAgeGroupCheckboxListByModuleId($module_id)
	{
	    $this->reset();
		$checkboxList=$this->setSelect()
			   			  ->addStatusFilter(1)
						  ->addModuleIdFilter($module_id)
                          ->addOrderBy('short_order', 'ASC')
			   		      ->get()
						  ->pluck('name', 'age_group_id');
	    return $checkboxList;
	}
	public function addAgeGroupIdFilter($age_group_id)
	{
		$this->queryBuilder->where('age_group_id',$age_group_id);
		return $this;
	}

	public function getAllAgeGropuForCampOrClinic($age_group_id){
		$ageGroupTable = $this->getTable();
		$selectedColumns = [$ageGroupTable.'.name'];
		
		return  $this->setSelect()
		   			  ->addAgeGroupIdFilter($age_group_id)
		   		      ->get($selectedColumns)
		   		      ->first();
	}

	public function addAgeGroupCsvIdFilter($age_group_id){

	    if(!empty($age_group_id)) {
            $fieldName = $this->table . '.age_group_id';
            $this->queryBuilder->whereIn($fieldName, $age_group_id);
        }

		return $this;
	}

	public function list($search='',$page=0, $age_group_id ='', $module_id = 0, $status = -1){
		$per_page=$this->_helper->getConfigPerPageRecord();
		$list=$this->setSelect()
  				   ->addAgeGroupCsvIdFilter($age_group_id)
                   ->addModuleIdFilter($module_id)
                   ->addStatusFilter($status)
  				   ->addSearch($search)
				   ->addPaging($page,$per_page)
				   ->get();

		return $list;
   	}
	
	public function listTotalCount($search='', $age_group_id ='', $module_id = 0, $status = -1){
		$this->reset();
		$count=$this->setSelect()
				  ->addAgeGroupCsvIdFilter($age_group_id)
                  ->addModuleIdFilter($module_id)
                  ->addStatusFilter($status)
				  ->addSearch($search)
				  ->get()
				  ->count();
		
		return $count;
	}
	
	public function preparePagination($total,$basePath){

		$perpage=$this->_helper->getConfigPerPageRecord();
		$pageHelper=new \App\Classes\PageHelper($perpage,'page');
		$pageHelper->set_total($total); 
		$pageHelper->page_links($basePath);
		return $pageHelper->page_links($basePath);
	}

	public function addAgeGroupArrayIdFilter($age_group_id){
		
		$fieldName = $this->table.'.age_group_id';
		$this->queryBuilder->whereIn($fieldName, $age_group_id);
		return $this;
	}
	public function getAgeGroupByArrayId($age_group_id)
	{
	    $this->reset();
		$ageGroupList = $this->setSelect()
				   			  ->addStatusFilter(1)
							  ->addAgeGroupArrayIdFilter($age_group_id)
				   		      ->get()
							  ->pluck('name', 'age_group_id');
	    return $ageGroupList;
	}

    public function getAgeGroupNameById($age_group_id){

        return $this->setSelect()
            ->addAgeGroupIdFilter($age_group_id)
            ->addStatusFilter(1)
            ->get(['name'])
            ->pluck('name')
            ->first();
    }

    public function getAgeGroupIdByName($name, $module_id){

        return $this->setSelect()
            ->addNameFilter($name)
            ->addStatusFilter(1)
            ->addModuleIdFilter($module_id)
            ->get(['age_group_id'])
            ->pluck('age_group_id')
            ->first();
    }

    public function load($age_group_id){

        $this->beforeLoad($age_group_id);

        $return =$this->setSelect()
            ->addAgeGroupIdFilter($age_group_id)
            ->get()
            ->first();

        $this->afterLoad($age_group_id, $return);

        return $return;
    }

    public function display($age_group_id){

        return $this->load($age_group_id);
    }

    public function saveRecord($data)
    {
        $rules=[
            'name' => 'required',
            'short_order' => 'required',
            'status' => 'required',
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

        $this->beforeSave($data);
        if(isset($data['id']) && $data['id'] !=''){
            $ageGroup = AgeGroup::findOrFail($data['id']);
            $ageGroup->update($data);
            $this->afterSave($data,$ageGroup);
            $result['id']=$ageGroup->age_group_id;
        }else{
            $ageGroup = AgeGroup::create($data);
            $this->afterSave($data,$ageGroup);
            $result['id']=$ageGroup->age_group_id;
        }
        $result['success']=true;
        $result['message']="Age Group Saved Successfully.";
        return $result;
    }

    public function removed($age_group_id)
    {
        $this->beforeRemoved($age_group_id);
        $deleteAgeGroupObj = $this->display($age_group_id);
        if(!empty($deleteAgeGroupObj)){
            $deleted = $deleteAgeGroupObj->delete();
            $this->afterRemoved($age_group_id);
            return $deleted;
        }
        return false;
    }
	
}
