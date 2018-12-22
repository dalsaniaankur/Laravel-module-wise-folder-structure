<?php
namespace App\Classes\Models\Experience;

use App\Classes\Models\BaseModel;

class Experience extends BaseModel
{
    protected $table = 'sbc_experience';
    protected $primaryKey = 'experience_id';
   
    protected $entity='experience';
	protected $searchableColumns=[];
	
	protected $fillable = ['experience_id', 'name', 'status', 'created_at', 'updated_at'];

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

	public function addOrderBy($columeName, $orderBy){
		
		$this->queryBuilder->orderBy($columeName, $orderBy);
		return $this;
	}

	public function addModuleFilter($module_id)
	{
		$this->queryBuilder->where('module_id',$module_id);
		return $this;
	}

	public function getExperienceDropdownByModuleId($module_id = 1)
	{
	    $dropdown = $this->setSelect()
		   			  ->addStatusFilter(1)	
		   			  ->addModuleFilter($module_id)	
					  ->addOrderBy('name', 'asc')	
	              	  ->get()
				      ->pluck('name', 'experience_id');
                  
	    return $dropdown;
	}	

}