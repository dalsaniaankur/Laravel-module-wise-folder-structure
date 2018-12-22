<?php
namespace App\Classes\Models\ShowcaseOrganization;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

use App\Classes\Models\BaseModel;

class Camporclinicservices extends BaseModel
{
    protected $table = 'pb_camp_or_clinic_services';
    protected $primaryKey = 'camp_or_clinic_service_id';
	protected $fillable = [ 'name', 'status','created_at', 'updated_at'];

	protected $entity='camp_or_clinic_services';
	protected $searchableColumns=['name'];

	public function setStatusAttribute($value)
	{
    	$this->attributes['status'] = ($value == 'on') ? 1 : 0;
	}

	/**
	**	Model Filter Methods 
	*/	
	public function addCampOrClinicServiceIdFilter($camp_or_clinic_service_id=0)
	{
		$this->queryBuilder->where('camp_or_clinic_service_id',$camp_or_clinic_service_id);
		return $this;
	}

	/*
	**	Logic Methods
	*/
	
	public function load($camp_or_clinic_service_id)
    {
	   $return = $this->beforeLoad($camp_or_clinic_service_id);
	    
	    $return =$this->setSelect()
	  		 	  ->addCampOrClinicServiceIdFilter($camp_or_clinic_service_id)	
	   			  ->get()
				  ->first();
		
		$this->afterLoad($camp_or_clinic_service_id, $return);
		
		return $return;
   	}

	public function getAllCampClinicServicesCheckBoxList()
    {
	   $return =$this->setSelect()
	    		  ->orderBy('name', 'asc')
				  ->pluck('name', 'camp_or_clinic_service_id');
	
		return $return;
   	}
}
