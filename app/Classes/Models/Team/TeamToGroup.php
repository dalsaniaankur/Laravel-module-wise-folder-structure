<?php
namespace App\Classes\Models\Team;

use Auth;
use DB;
use App\Classes\Models\BaseModel;
use App\Classes\Models\Team\Team;
use App\Classes\Models\Team\TeamGroup;

class TeamToGroup extends BaseModel{
    
	protected $table = 'sbc_team_to_group';
    protected $primaryKey = 'team_to_group_id';
    
  	protected $entity='team_to_group';
	protected $searchableColumns=[];

    protected $fillable = ['team_id','team_group_id','sort'];

	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
    }
	
	public function Team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'team_id');
    }
	
	public function teamgroup()
    {
        return $this->belongsTo(TeamGroup::class, 'team_group_id', 'team_group_id');
    }
	
	/**
	**	Model Filter Methods 
	*/	
	public function addTeamToGroupId($team_to_group_id=0)
	{
		$this->queryBuilder->where('team_to_group_id',$team_to_group_id);
		return $this;
	}
	
	public function addTeamGroupId($team_group_id=0)
	{
		$this->queryBuilder->where('team_group_id',$team_group_id);
		return $this;
	}
	public function addTeamId($team_id=0)
	{
		$this->queryBuilder->where('team_id',$team_id);
		return $this;
	}
	/*
	**	Join Methods
	*/
	public function joinTeam($searchable=false)
	{
		$team=new \App\Classes\Models\Team\Team;
		$teamTable=$team->getTable();
		$searchableColumns=$team->getSearchableColumns();
	
		$this->joinTables[]=array('table'=>$teamTable,'searchable'=>$searchable,'searchableColumns'=>$searchableColumns);
		$this->queryBuilder->join($teamTable,function($join) use($teamTable) {
			$join->on($this->table.'.team_id', '=', $teamTable.'.team_id');
		});
		return $this;
	}
	
	/*
	**	Logic Methods
	*/
	public function load($team_to_group_id)
    {
    	$this->beforeLoad($team_to_group_id);

	    $return =$this->setSelect()
	   			  ->addTeamToGroupId($team_to_group_id)
				  ->get()
				  ->first();
		
		$this->afterLoad($team_to_group_id, $return);
		
		return $return;
   	}
	
	//groupwise team load
	public function getAllTeamForGroup($team_group_id)
	{
		$list=$this->setSelect()
				   ->joinTeam()
				   ->addTeamGroupId($team_group_id)
				   ->get();
		
		return $list;
	}

	public function removed($id)
	{
		$this->beforeRemoved($id);
		$galary=$this->load($id);
		if(!empty($galary)){
			  $delete = $galary->delete();
			  $this->afterRemoved($id);
			  return $delete;
		}
		return false;
	}
	
	public function unlinkTeam($team_group_id,$team_id)
    {

	    $record =$this->setSelect()
	   			  ->addTeamGroupId($team_group_id)
				  ->addTeamId($team_id)
				  ->get()
				  ->first();
		
		if(count((array)$record)>0){
			return $record->delete();
		}		  
		return false;		
   	}
}