<?php
namespace App\Classes\Models\Description;

use App\Classes\Models\BaseModel;

class Description extends BaseModel{
	
	protected $table = 'sbc_description';
    protected $primaryKey = 'description_id';
  	protected $entity='sbc_description';
	protected $searchableColumns=[];
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */	
    protected $fillable = ['description_id', 'name', 'status', 'created_at', 'updated_at'];

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

	public function addOrderBy($columeName, $orderBy){
		
		$this->queryBuilder->orderBy($columeName, $orderBy);
		return $this;
	}

	public function checkboxList(){

	    $dropdown = $this->setSelect()
		   			  ->addStatusFilter(1)	
		   			  ->addOrderBy('name', 'asc')
                      ->get()
	                  ->pluck('name', 'description_id');
                  
	    return $dropdown;
	}	
}