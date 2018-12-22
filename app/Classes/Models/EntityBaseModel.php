<?php
namespace App\Classes\Models;

Use DB;
use Auth;
use Illuminate\Http\Request;
use App\Taskassignment;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;
use Hash;

class EntityBaseModel extends Authenticatable
{
	use Notifiable;
	protected $queryBuilder;
	protected $joinTables=array();
	
	protected $entity='task';
	protected $searchableColumns=['title'];
		
	/* Model Query Making Methods */
	public function getEntity()
	{
	 	return $this->entity;
	}
	
	public function getSearchableColumns()
	{
		return $this->searchableColumns;
	}
	
	public function reset()
	{
		$this->queryBuilder='';
//		$this->queryBuilder=$this->query();
		return $this;
	}
	public function setSelect()
	{  
		$this->queryBuilder=$this->query();
		return $this;
	}
	
	public function addSearch($search='')
	{
		$search=trim($search);
		$searchKeyword=explode(" ",$search);
		$searchKeywordArray=array();
		if(count($searchKeyword)>0){
			foreach($searchKeyword as $keyword){
				$searchKeywordArray[]=trim($keyword);
			}
			array_unique($searchKeywordArray);
		}
		if(count($searchKeywordArray)>0){
			$this->queryBuilder->where(function($query) use ($searchKeywordArray){
				  $i=0;
				  foreach($searchKeywordArray as $keyword){ //first table
					 if($i==0){
						 $query->where(function($query) use ($keyword){
							 $j=0;
							 foreach($this->searchableColumns as $column){
								 if($j==0)
									$query->where($this->table.'.'.$column, 'like', '%' . $keyword . '%');
								  else 
									 $query->orWhere($this->table.'.'.$column, 'like', '%' . $keyword . '%');
								  $j++;
							 }
						 });
					 }else{
						$query->orWhere(function($query) use ($keyword){
							 $j=0;
							 foreach($this->searchableColumns as $column){
								 if($j==0)
									$query->where($this->table.'.'.$column, 'like', '%' . $keyword . '%');
								  else 
									 $query->orWhere($this->table.'.'.$column, 'like', '%' . $keyword . '%');
								  $j++;
							 }
						 });	 
					 }
					 $i++;
				  }
				  if(count($this->joinTables)>0){
					  foreach($this->joinTables as $tableRow){
						if($tableRow['searchable']){  
							foreach($searchKeywordArray as $keyword){ 
							 if($i==0){
								 $query->where(function($query) use ($keyword,$tableRow){
									 $j=0;
									 foreach($tableRow['searchableColumns'] as $column){
										 if($j==0)
											$query->where($tableRow['table'].'.'.$column, 'like', '%' . $keyword . '%');
										  else 
											 $query->orWhere($tableRow['table'].'.'.$column, 'like', '%' . $keyword . '%');
										  $j++;
									 }
								 });
							 }else{
								$query->orWhere(function($query) use ($keyword,$tableRow){
									 $j=0;
									 foreach($tableRow['searchableColumns'] as $column){
										 if($j==0)
											$query->where($tableRow['table'].'.'.$column, 'like', '%' . $keyword . '%');
										  else 
											 $query->orWhere($tableRow['table'].'.'.$column, 'like', '%' . $keyword . '%');
										  $j++;
									 }
								 });	 
							 }
							 $i++;
						  }
						}
					 }
				  }
			});
		}
		return $this;
	}
	
	public function addPaging($page=0,$per_page)
	{
        if($page != -1 || $page != '-1') {
            $limit = (($page > 0) ? ($page - 1) : $page) * $per_page;
            $this->queryBuilder->skip($limit)->take($per_page);
        }
		  return $this;
	}
	
	public function get($selectColoumn=array('*'))
    {
	 	return $this->queryBuilder->get($selectColoumn);
	}

	/*Validate data*/
	public function validateData($rules,$data){
		$validator = '';
		$validationResult=array();
		$validationResult['success']=false;
		$validationResult['message']=array();
		
		$validator = \Validator::make($data, $rules);
        if($validator->passes()){
			$validationResult['success']=true;
			return $validationResult;
		}
	    $errors=json_decode($validator->errors());
     	$validationResult['success']=false;
		$validationResult['message']=$errors;
		return $validationResult;
	}

	public function getArrayToCSV($array){

		return implode(",",$array);
    }

    public function getCSVToArray($csv){

    	return explode(",",$csv);
    }

    public function getCuttentData()
    {
        return \Carbon\Carbon::now()->toDateTimeString();
    }

   	/**
	**	Model befor after Methods 
	*/
	protected function beforeSave($data=array())
	{
	
	}
	
	protected function afterSave($data=array(),$object='')
	{
	}
	
	protected function beforeRemoved($id=0)
	{
	}
	
	protected function afterRemoved($id=0)
	{
	}

	protected function beforeLoad($id=0)
	{
	}
	
	protected function afterLoad($id=0, $data)
	{
	}

	
}
