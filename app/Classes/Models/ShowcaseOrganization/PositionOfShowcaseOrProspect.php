<?php
namespace App\Classes\Models\ShowcaseOrganization;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Models\BaseModel;

class PositionOfShowcaseOrProspect extends BaseModel
{
    protected $table = 'pb_position_of_showcase_or_prospect';
    protected $primaryKey = 'position_of_showcase_or_prospect_id';
	protected $fillable = [ 'name', 'status','created_at', 'updated_at'];

	protected $entity='position_of_showcase_or_prospect';
	protected $searchableColumns=['name'];


	public function setStatusAttribute($value)
	{
    	$this->attributes['status'] = ($value == 'on') ? 1 : 0;
	}

	/**
	**	Model Filter Methods 
	*/	
	public function addPositionOfShowcaseOrProspectIdFilter($position_of_showcase_or_prospect_id=0)
	{
		$this->queryBuilder->where('position_of_showcase_or_prospect_id',$position_of_showcase_or_prospect_id);
		return $this;
	}
	
	/*
	**	Logic Methods
	*/
	public function load($position_of_showcase_or_prospect_id)
    {
	   $return =$this->setSelect()
	  		 	  ->addPositionOfShowcaseOrProspectIdFilter($position_of_showcase_or_prospect_id)	
	   			  ->get()
				  ->first();
		return $return;
   	}

	public function getAllPositionCheckBoxList()
    {
	   $return =$this->setSelect()
	  		 	  ->orderBy('name', 'asc')
				  ->pluck('name', 'position_of_showcase_or_prospect_id');
			return $return;
   	}
}
