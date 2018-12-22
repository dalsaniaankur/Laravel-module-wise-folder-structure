<?php
namespace App\Classes\Models\BatsOrThrows;
use App\Classes\Models\BaseModel;

class BatsOrThrows extends BaseModel
{
    protected $table = 'sbc_bats_or_throw';
    protected $primaryKey = 'bats_or_throw_id';
    protected $entity='bats_or_throw';
	protected $searchableColumns=[];
	

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */	
	protected $fillable = ['bats_or_throw_id', 'name', 'status', 'created_at', 'updated_at'];


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


	public function addModuleFilter($module_id)
	{
		$this->queryBuilder->where('module_id',$module_id);
		return $this;
	}

	public function addOrderBy($columeName, $orderBy){
		
		$this->queryBuilder->orderBy($columeName, $orderBy);
		return $this;
	}

	public function dropDownList($module_id = 1){

	    $dropDownList = $this->setSelect()
			   			  ->addStatusFilter(1)	
			   			  ->addModuleFilter($module_id)	
			   			  ->addOrderBy('name', 'asc')	
		                  ->pluck('name', 'bats_or_throw_id');
                  
	    return $dropDownList;
	}	
}
