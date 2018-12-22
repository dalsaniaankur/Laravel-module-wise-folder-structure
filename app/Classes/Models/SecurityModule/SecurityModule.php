<?php

namespace App\Classes\Models\SecurityModule;

use App\Classes\Models\BaseModel;

class SecurityModule extends BaseModel{

    protected $table = 'sbc_security_module';
    protected $primaryKey = 'link_id';
    protected $entity='security_module';
	protected $searchableColumns=[];
	
	protected $fillable =['link_id', 'label', 'code','link','level','is_active','sort_order','parent_link_id','created_at','updated_at'];

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

	public function checkboxList(){

	    $dropdown=$this->setSelect()
		   			  ->addStatusFilter(1)	
		   		//	  ->addOrderBy('name', 'asc')	
	                  ->pluck('label', 'link_id');
                  
	    return $dropdown;
	}
}