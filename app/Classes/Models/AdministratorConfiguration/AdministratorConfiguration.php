<?php
namespace App\Classes\Models\AdministratorConfiguration;

use App\Classes\Models\BaseModel;

class AdministratorConfiguration extends BaseModel{
    
	protected $table = 'sbc_configuration';
    protected $primaryKey = 'id';

    protected $fillable = ['id',
						   'key',
						   'value',
						   'label',
						   'user_id',
						  ];

	public function addKeyFilter($key){

		$this->queryBuilder->where('key','=',$key);
		return $this;
	}
	  
	public function getValueByKey($key){

	    return $this->setSelect()
		   			  ->addKeyFilter($key)
		   			  ->get(['value'])
		   			  ->first();	
	    
  	}  
}