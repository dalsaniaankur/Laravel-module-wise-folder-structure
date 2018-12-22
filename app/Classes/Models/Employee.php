<?php
namespace App\Classes\Models\Administrator;
use Hash;

use App\Classes\Models\EntityBaseModel;

class Employee extends EntityBaseModel
{
 	protected $table = 'sbc_employee';
    protected $primaryKey = 'employee_id';

	protected $fillable = ['first_name','last_name','company_name', 'email','password','gender','address1','address2','city','state','country','postal_code','telephone','mobile','profile_picture','date_of_birth','status','created_by','manager_id','category_id','facility_id','remember_token'];
    protected $hidden = ['password', 'remember_token',];
	
	protected $entity='employee';
	protected $searchableColumns=['first_name','last_name','email'];
	
	/**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
	public function category() 
    {
        return $this->belongsTo(Employeecategory::class, 'category_id');
    }
	public function setCategoryIdAttribute($input)
    {
        $this->attributes['category_id'] = $input ? $input : 0;
    }	
	public function setDateOfBirthAttribute($value)
	{
    	$this->attributes['date_of_birth'] = date("Y-m-d", strtotime($value));
	}
	public function setAddress2Attribute($input)
    {
        if ($input)
            $this->attributes['address2'] =  $input ? $input : ' ';
    }
    public function sendPasswordResetNotification($token)
    {
       $this->notify(new ResetPassword($token));
    }

	/* Filter Methods */
	public function addEmployeeFilter($employee_id)
	{
		$this->queryBuilder->where($this->table.'.employee_id',$employee_id);
		return $this;
	}
	
	public function addManagerFilter($manager_id)
	{
		$this->queryBuilder->where($this->table.'.created_by',$manager_id);
		return $this;
	}
	
	/* Logic Methods*/
	public function getEmployeeById($employee_id)
	{
		 return $this->setSelect()
					->addEmployeeFilter($employee_id)
					->get()
					->first();
	}
	
	public function getAllEmployeeByManagerId($manager_id)
	{
		 return $this->setSelect()
					->addManagerFilter($manager_id)
					->get();
	}
}
