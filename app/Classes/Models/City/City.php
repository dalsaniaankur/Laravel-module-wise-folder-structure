<?php
namespace App\Classes\Models\City;

use App\Classes\Models\BaseModel;
use App\Classes\Models\State\State;

class City extends BaseModel{

	protected $table      ='sbc_city';
    protected $primaryKey ='city_id';
  	protected $entity     ='sbc_city';
	protected $searchableColumns=[];
    protected $fillable=['city_id','city','state', 'country','latitude', 'longitude'];

    protected $stateObj;

	public function __construct(array $attributes = []){

        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->stateObj = new State();
    }


	public function addOrderBy($columeName, $orderBy)
	{
		$this->queryBuilder->orderBy($columeName, $orderBy);
		return $this;
	}


	public function getCityDropdown($state=''){

	    return  $this->setSelect()
		   			  ->addOrderBy('city', 'asc')
		   			  ->addStateFilter($state)
                      ->get()
	                  ->pluck('city', 'city_id');
  	}	
  	public function addStateFilter($state){
		
		if(!empty($state)){
			$this->queryBuilder->where('state','=',$state);
		}
		return $this;
	}
	public function addCityNameFilter($city){

		$this->queryBuilder->where('city','=',$city);
		return $this;
	}

	public function addCityNameLikeFilter($city){

		$this->queryBuilder->where($this->table.'.city', 'like', '%'.$city.'%');
		return $this;
	}

	public function addCityIdFilter($city_id){

		$this->queryBuilder->where('city_id','=',$city_id);
		return $this;
	}
	public function joinState($searchable=false){	

		$stateTable = $this->stateObj->getTable();
		$searchableColumns = $this->stateObj->getSearchableColumns();

		$this->joinTables[]=array('table'=>$stateTable,'searchable'=>$stateTable,'searchableColumns'=>$searchableColumns);
		$this->queryBuilder->join($stateTable,function($join) use($stateTable) {
			$join->on($this->table.'.state', '=', $stateTable.'.code');
		});
		return $this;
	}

	public function addStateIdFilter($state_id = 0){
		$stateTable = $this->stateObj->getTable();
		
		if(!empty($state_id)){
			$this->queryBuilder->where($stateTable.'.state_id','=',$state_id);
		}
		return $this;
	}

	public function getCityDropdownByStateIdAndCityName($state_id, $city){
		return  $this->setSelect()
					 ->joinState()
		   			 ->addOrderBy($this->table.'.city', 'asc')
		   			 ->addStateIdFilter($state_id)
		   			 ->addCityNameLikeFilter($city)
		   			 ->get()	
	                 ->pluck('city', 'city_id');
	}
	public function getCityDropdownByStateIdWithNoneOption($state_id){
		return  $this->setSelect()
					 ->joinState()
		   			 ->addOrderBy($this->table.'.city', 'asc')
		   			 ->addStateIdFilter($state_id)
		   			 ->get()
	                 ->pluck('city', 'city_id')
	                 ->prepend(trans('quickadmin.qa_none'), 0);
	} 

	public function getCityLongitudeLatitudeByName($city){
		return  $this->setSelect()
				     ->addCityNameFilter($city)
		   			 ->get(['latitude', 'longitude'])
		   			 ->first();	
	                 
	}

	public function getCityIdWithStateByName($city, $state){
		return  $this->setSelect()
				     ->addCityNameFilter($city)
				     ->addStateFilter($state)
		   			 ->get()
		   			 ->first();	
	                 
	}
	public function getCityStateByCityId($city_id){
		return  $this->setSelect()
					 ->joinState()
		   			 ->addCityIdFilter($city_id)
		   			 ->get()	
	                 ->first();
	}

	public function getCityDropdownByStateIdNameWise($state_id, $city){
		
		return  $this->setSelect()
					 ->joinState()
		   			 ->addOrderBy($this->table.'.city', 'asc')
		   			 ->addStateIdFilter($state_id)
		   			 ->addCityNameLikeFilter($city)
		   			 ->get()	
	                 ->pluck('city', 'city')
	                 ->prepend('All', '0');
	}
	public function getCityDropdownByCityId($city_id){ 
		return  $this->setSelect()
		   			 ->addOrderBy($this->table.'.city', 'asc')
		   			 ->addCityIdFilter($city_id)
		   			 ->get()	
	                 ->pluck('city', 'city_id');
	}

	public function getCityDropdownByCityIdForFront($city_id){ 
		return  $this->setSelect()
		   			 ->addOrderBy($this->table.'.city', 'asc')
		   			 ->addCityIdFilter($city_id)
		   			 ->get()	
	                 ->pluck('city', 'city');
	}

	public function getCityDropdownByCityIdWithNoneOption($city_id){
		return  $this->setSelect()
		   			 ->addOrderBy($this->table.'.city', 'asc')
		   			 ->addCityIdFilter($city_id)
		   			 ->get()	
	                 ->pluck('city', 'city_id')
	                 ->prepend(trans('quickadmin.qa_none'), 0);
	}

    public function recordCount($cityName, $state_id){
        return $this->setSelect()
            ->joinState()
            ->addStateIdFilter($state_id)
            ->addCityNameFilter($cityName)
            ->get(['city_id'])
            ->pluck('city_id')
            ->first();
    }
}