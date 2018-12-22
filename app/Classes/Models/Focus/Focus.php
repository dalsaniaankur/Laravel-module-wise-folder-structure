<?php
namespace App\Classes\Models\Focus;

use App\Classes\Models\BaseModel;

class Focus extends BaseModel
{
    protected $table = 'sbc_focus';
    protected $primaryKey = 'focus_id';
    protected $entity='focus';
	protected $searchableColumns=[];
		
	protected $fillable = ['focus_id', 'name', 'status', 'module_id', 'created_at', 'updated_at'];

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


	public function addModuleFilter($module_id)
	{
		$this->queryBuilder->where('module_id',$module_id);
		return $this;
	}

	public function addOrderBy($columeName, $orderBy){
		
		$this->queryBuilder->orderBy($columeName, $orderBy);
		return $this;
	}

	public function getFocusCheckboxListByModuleId($module_id = 1)
	{
	    $checkboxList = $this->setSelect()
			   			  ->addStatusFilter(1)	
			   			  ->addModuleFilter($module_id)	
			   			  ->addOrderBy('name', 'asc')
                          ->get()
		                  ->pluck('name', 'focus_id');
	    return $checkboxList;
	}	
}