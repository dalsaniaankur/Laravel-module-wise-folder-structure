<?php
namespace App\Classes\Models\Position;

use App\Classes\Models\BaseModel;

class Position extends BaseModel
{
    protected $table = 'sbc_position';
    protected $primaryKey = 'position_id';
   
    protected $entity='position';
	protected $searchableColumns=['name'];
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */	
	protected $fillable = ['position_id', 'name','module_id','status', 'created_at', 'updated_at'];

	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
    }
	
	public function addStatusFilter($status=1)
	{
		$this->queryBuilder->where('status',$status);
		return $this;
	}
	public function addPositionIdFilter($position_id=0)
	{
		$this->queryBuilder->where('position_id',$position_id);
		return $this;
	}
    public function addModuleIdFilter($module_id)
    {
        $this->queryBuilder->where('module_id',$module_id);
        return $this;
    }
    public function addNameFilter($name)
    {
        $this->queryBuilder->where('name','=',$name);
        return $this;
    }

	public function addOrderBy($columeName, $orderBy){
		
		$this->queryBuilder->orderBy($columeName, $orderBy);
		return $this;
	}

	public function addModuleFilter($module_id)
	{
		$this->queryBuilder->where('module_id',$module_id);
		return $this;
	}
	public function getPositionCheckboxListByModuleId($module_id = 1)
	{
	    $dropdown = $this->setSelect()
		   			  ->addStatusFilter(1)	
		   			  ->addModuleFilter($module_id)	
		   			  ->addOrderBy('name', 'asc')	
					  ->get()
	                  ->pluck('name', 'position_id');
                  
	    return $dropdown;
	}
	public function getPositionCheckboxListByModuleIdForFrontSide($module_id){

	     $dropdown = $this->setSelect()
		   			  ->addStatusFilter(1)	
		   			  ->addModuleFilter($module_id)	
		   			  ->addOrderBy('name', 'asc')	
					  ->get()
	                  ->pluck('name', 'position_id')
	                  ->prepend(trans('front.qa_all'), 0);
                  
	    return $dropdown;
	}
	public function getPositionDropDownByModuleId($module_id = 1){

	   		return $this->setSelect()
		   			  ->addStatusFilter(1)	
		   			  ->addModuleFilter($module_id)	
		   			  ->addOrderBy('short_order', 'asc')
					  ->get()
	                  ->pluck('name', 'position_id');
	                  //->prepend(trans('quickadmin.qa_all'), 0);
  	}	

  	public function getAllPositionById($position_id){
		
		$selectedColumns = [$this->table.'.name'];
		
		return  $this->setSelect()
		   			  ->addPositionIdFilter($position_id)
		   		      ->get($selectedColumns)
		   		      ->first();
	}

    public function getPositionNameById($position_id){

        return $this->setSelect()
            ->addPositionIdFilter($position_id)
            ->addStatusFilter(1)
            ->get(['name'])
            ->pluck('name')
            ->first();
    }

    public function getPositionIdByName($name, $module_id){

        return $this->setSelect()
            ->addNameFilter($name)
            ->addStatusFilter(1)
            ->addModuleIdFilter($module_id)
            ->get(['position_id'])
            ->pluck('position_id')
            ->first();
    }
  	
}
