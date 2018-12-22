<?php
namespace App\Classes\Models\ShowcaseOrganization;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Models\BaseModel;

class Typeofcamporclinic extends BaseModel
{
    protected $table = 'sbc_type_of_camp_or_clinic';
    protected $primaryKey = 'type_of_camp_or_clinic_id';
	protected $fillable = [ 'name', 'status','created_at', 'updated_at'];

	protected $entity='type_of_camp_or_clinic';
	protected $searchableColumns=['name'];


	public function setStatusAttribute($value)
	{
    	$this->attributes['status'] = ($value == 'on') ? 1 : 0;
	}
	/**
	**	Model Filter Methods 
	*/	
	public function addCampOrClinicServiceIdFilter($showcase_organization_id=0)
	{
		$this->queryBuilder->where('type_of_camp_or_clinic_id',$type_of_camp_or_clinic_id);
		return $this;
	}
	
	/*
	**	Logic Methods
	*/
	public function load($type_of_camp_or_clinic_id)
    {
        $this->beforeLoad($type_of_camp_or_clinic_id);

	    $return =$this->setSelect()
	  		 	  ->addTypeOfCampOrClinicIdFilter($type_of_camp_or_clinic_id)	
	   			  ->get()
				  ->first();

		$this->afterLoad($type_of_camp_or_clinic_id, $return);
				  
		return $return;
   	}

	public function getAllTypeOfCampClinicCheckBoxList()
    {
	   $return =$this->setSelect()
	  		 	  ->orderBy('name', 'asc')
				  ->pluck('name', 'type_of_camp_or_clinic_id');
	
			return $return;
   	}

}
