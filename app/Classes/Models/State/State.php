<?php
namespace App\Classes\Models\State;

use App\Classes\Models\BaseModel;

class State extends BaseModel
{
	protected $table      ='sbc_state';
    protected $primaryKey ='state_id';
  	protected $entity     ='state';
	protected $searchableColumns=[];
    protected $fillable=['state_id','code','name', 'status', 'created_at', 'updated_at'];

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

	public function addOrderBy($columeName, $orderBy)
	{
		$this->queryBuilder->orderBy($columeName, $orderBy);
		return $this;
	}

	public function addStateCodeFilter($code){
		
		$this->queryBuilder->where('code',$code);
		return $this;
	}

	public function getStateDropdown()
	{
	    return  $this->setSelect()
		   			  ->addStatusFilter(1)	
		   			  ->addOrderBy('name', 'asc')
                      ->get()
	                  ->pluck('name', 'state_id');
  	}	

  	public function addStateIdFilter($state_id)
	{
	    if(!empty($state_id)) {
            $this->queryBuilder->where('state_id', '=', $state_id);
        }
		return $this;
	}

  	public function getStateNameById($state_id){

	    $return = $this->setSelect()
		   			  ->addStateIdFilter($state_id)
		   			  ->get(['name'])
		   			  ->first();	

		return !empty($return->name) ? $return->name : ''; 
  	}

  	public function addStateNameFilter($name){
	
		$this->queryBuilder->where('name','=',$name);
		return $this;
	}

  	public function getStateIdByName($stateName){
		return  $this->setSelect()
				     ->addStateNameFilter($stateName)
		   			 ->get(['state_id'])
		   			 ->first();	
	                 
	}
  	public function getStateDropdownWithAddOption(){
  		
		return $this->setSelect()
			  ->addStatusFilter(1)	
			  ->addOrderBy('code', 'asc')
              ->get()
			  ->pluck('code', 'code')
			  ->prepend('All','');
		
  	}
  	public function getStateByCode($stateCode){
		return  $this->setSelect()
				     ->addStateCodeFilter($stateCode)
		   			 ->get()
		   			 ->first();	
	}

    public function getStateCodeById($state_id){

        return $this->setSelect()
                    ->addStateIdFilter($state_id)
                    ->get(['code'])
                    ->pluck('code')
                    ->first();
    }

    public function recordCount($stateName){
        return $this->setSelect()
            ->addStateNameFilter($stateName)
            ->get(['state_id'])
            ->pluck('state_id')
            ->first();
    }
}