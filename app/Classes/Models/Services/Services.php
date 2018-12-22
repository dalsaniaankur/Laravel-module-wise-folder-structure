<?php

namespace App\Classes\Models\Services;

use App\Classes\Models\BaseModel;

class Services extends BaseModel
{
    protected $table = 'sbc_services';
    protected $primaryKey = 'service_id';
    protected $entity='services';
	protected $searchableColumns=[];
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */	
	protected $fillable = ['service_id', 'name', 'status', 'module_id', 'created_at', 'updated_at'];


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

	public function addModuleIdFilter($module_id)
	{
		$this->queryBuilder->where('module_id',$module_id);
		return $this;
	}

	public function addOrderBy($columeName, $orderBy)
	{
		$this->queryBuilder->orderBy($columeName, $orderBy);
		return $this;
	}
	
	public function getServicesCheckboxListByModuleId($module_id)
	{
		$this->reset();
	    $checkboxList =$this->setSelect()
		   			  ->addModuleIdFilter($module_id)
                      ->addOrderBy('name','ASC')
					  ->get()
	                  ->pluck('name','service_id');
                  
	    return $checkboxList;
	}

	public function addServiceIdFilter($service_id)
	{
		$this->queryBuilder->where('service_id',$service_id);
		return $this;
	}
    public function addNameFilter($name){

        $this->queryBuilder->where('name','=',$name);
        return $this;
    }
	public function getServicesByServiceId($service_id){
		$serviceTable = $this->getTable();

		$selectedColumns = [$serviceTable.'.name'];
		
		return  $this->setSelect()
		   			  ->addServiceIdFilter($service_id)
		   		      ->get($selectedColumns)
		   		      ->first();
	}

    public function getServiceNameById($service_id, $module_id){

        return $this->setSelect()
            ->addServiceIdFilter($service_id)
            ->addModuleIdFilter($module_id)
            ->addStatusFilter(1)
            ->get(['name'])
            ->pluck('name')
            ->first();
    }

    public function getServiceIdByName($name, $module_id){

        return $this->setSelect()
            ->addNameFilter($name)
            ->addStatusFilter(1)
            ->addModuleIdFilter($module_id)
            ->get(['service_id'])
            ->pluck('service_id')
            ->first();
    }
}