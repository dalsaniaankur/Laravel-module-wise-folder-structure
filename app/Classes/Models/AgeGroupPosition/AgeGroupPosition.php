<?php
namespace App\Classes\Models\AgeGroupPosition;

use Illuminate\Database\Eloquent\Model;
use App\Classes\Models\BaseModel;
use App\Classes\Helpers\AgeGroupPosition\Helper;
use App\Classes\Models\Position\Position;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\Tryout\Tryout;

class AgeGroupPosition extends BaseModel{
    
	protected $table = 'sbc_agegroup_position';
    protected $primaryKey = 'agegroup_position_id';
    
  	protected $entity='sbc_agegroup_position';
	protected $searchableColumns=[];

	protected $_helper;
	
    protected $fillable = [ 'tryout_id',
							'age_group_id',
							'position_id'];


	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {	
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
    }

    public function position(){

        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }

    public function agegroup(){

        return $this->belongsTo(AgeGroup::class, 'age_group_id', 'age_group_id');
    }
    public function tryout(){

        return $this->belongsTo(Tryout::class, 'tryout_id', 'tryout_id');
    }
	
	public function addAgeGroupIdFilter($age_group_id=0){

		$this->queryBuilder->where('age_group_id',$age_group_id);
		return $this;
	}

	public function addPositionIdFilter($position_id=0){

		$this->queryBuilder->where('position_id',$position_id);
		return $this;
	}

	public function addAgeGroupPositionIdFilter($agegroup_position_id=0){

		$this->queryBuilder->where('agegroup_position_id',$agegroup_position_id);
		return $this;
	}
	public function addNotAgeGroupPositionIdFilter($agegroup_position_id=0){

		$this->queryBuilder->where('agegroup_position_id', '!=' , $agegroup_position_id);
		return $this;
	}

	public function addTryoutIdFilter($tryout_id=0){
		
		$this->queryBuilder->where('tryout_id',$tryout_id);
		return $this;
	}
	
	/*
	**	Logic Methods
	*/
	public function load($agegroup_position_id)
    {
    	$this->beforeLoad($agegroup_position_id);
	    
	    $return = $this->setSelect()
	   			  ->addAgeGroupPositionIdFilter($agegroup_position_id)	
				  ->get()
				  ->first();

		$this->afterLoad($agegroup_position_id, $return);		  
		
		return $return;
   	}

   	public function joinPosition($searchable=false)
	{	
		$position = new Position();
		$positionTable = $position->getTable();
		$searchableColumns = $position->getSearchableColumns();

		$this->joinTables[]=array('table'=>$positionTable,'searchable'=>$searchable,'searchableColumns'=>$searchableColumns);
		$this->queryBuilder->join($positionTable,function($join) use($positionTable) {
			$join->on($this->table.'.position_id', '=', $positionTable.'.position_id');
		});
		return $this;
	}
   	
	public function list($search='',$page=0, $age_group_id, $tryout_id){

		$per_page=$this->_helper->getConfigPerPageRecord();
  		
  		$list=$this->setSelect()
  				   ->joinPosition(true)
  				   ->addAgeGroupIdFilter($age_group_id)
  				   ->addTryoutIdFilter($tryout_id)
  				   ->addSearch($search)
				   ->addPaging($page,$per_page)
				   ->get();
		
		return $list;
   	}
	
	public function listTotalCount($search='', $age_group_id, $tryout_id){
		$this->reset();
		$count=$this->setSelect()
					->joinPosition(true)
				    ->addSearch($search)
 				    ->addAgeGroupIdFilter($age_group_id)
 				    ->addTryoutIdFilter($tryout_id)
 				    ->get()
				    ->count();
		
		return $count;
	}
	
	public function preparePagination($total,$basePath){

		$perpage=$this->_helper->getConfigPerPageRecord();
		$pageHelper=new \App\Classes\PageHelper($perpage,'page');
		$pageHelper->set_total($total); 
		$pageHelper->page_links($basePath);
		return $pageHelper->page_links($basePath);
	}
	
	public function checkDuplicateRecords($data, $age_group_id, $tryout_id){
		
		$this->reset();
		$count = $this->setSelect()
 				   ->addAgeGroupIdFilter($age_group_id)
 				   ->addTryoutIdFilter($tryout_id)
 				   ->addPositionIdFilter($data['position_id'])
 				   ->addNotAgeGroupPositionIdFilter($data['id'])
 				   ->get()
				   ->count();
		
		return $count;
	}
	
	public function saveRecord($data, $age_group_id, $tryout_id){

		$rules=array();	
		$rules=[
			  'position_id'=> 'required',
		];

		$validationResult=$this->validateData($rules,$data);
		$result=array();
		$result['id']='';
		$customValidationStatus = $this->checkDuplicateRecords($data, $age_group_id, $tryout_id);
		if($validationResult['success']==false || $customValidationStatus == 1){
			
			$result['success']=false;
			$result['message']=$validationResult['message'];
			$result['id']=$data['id'];
			if($customValidationStatus == 1){
				$result['message'] = new \stdClass();
				$result['message']->position_id[]="The position must be a unique.";	
			}
			return $result;
		}

		
		if(isset($data['id']) && $data['id'] !=''){
		  	$ageGroupPosition = self::findOrFail($data['id']);
		    $ageGroupPosition ->update($data);	
		    $this->afterSave($data,$ageGroupPosition);
			$result['id']=$ageGroupPosition->agegroup_position_id;	
		}else{
			$data['age_group_id']= $age_group_id;
			$data['tryout_id']= $tryout_id;
		 	$ageGroupPosition  = self::create($data);
			$result['id']=$ageGroupPosition->agegroup_position_id;
			$this->afterSave($data,$ageGroupPosition);
		}
		$result['success']=true;
		$result['message']="Position Saved Successfully.";
		return $result;
	}
	
	public function display($agegroup_position_id)
    {
	    $return =$this->load($agegroup_position_id);
	   
		return $return;
   	}
	
	public function removed($id){

		$this->beforeRemoved($id);
		$team=$this->load($id);
		if(!empty($team)){
			 $delete = $team->delete();
			 $this->afterRemoved($id);
			 return $delete;
		}
		return false;
	}

	public function getAgeGroupPosition($tryout_id, $age_group_id)
	{
	    $this->reset();
		$ageGroupList = $this->setSelect()
							  ->addAgeGroupIdFilter($age_group_id)
							  ->addTryoutIdFilter($tryout_id)
							  ->joinPosition()
				   		      ->get()
							  ->pluck('name');
	    return $ageGroupList;
	}

    public function getSelectedAgeGroupPosition($tryout_id, $age_group_id)
    {
        $this->reset();
        return $this->setSelect()
                        ->addAgeGroupIdFilter($age_group_id)
                        ->addTryoutIdFilter($tryout_id)
                        ->get()
                        ->pluck('position_id');
    }

    public function ageGroupPositionCreateOrUpdate($data, $age_group_id_array, $tryout_id){

        /* Delete Record  Age Group Wise */
        AgeGroupPosition::where('tryout_id', $tryout_id)
            ->whereNotIn('age_group_id', $age_group_id_array)
            ->delete();

        /* Insert Update */
        foreach ($age_group_id_array as $age_group_id){

            $age_group_position = array();

            if(!empty($data['age_group_position_'.$age_group_id])) {
                $age_group_position = $data['age_group_position_' . $age_group_id];
            }

            if(!empty($age_group_position) && count($age_group_position) > 0) {

                foreach ($age_group_position as $position_id) {
                    AgeGroupPosition::updateOrCreate(array('tryout_id' => $tryout_id,
                        'age_group_id' => $age_group_id,
                        'position_id' => $position_id));
                }
            }

            /* Delete Record Position Wise */
            AgeGroupPosition::where('tryout_id', $tryout_id)
                ->where('age_group_id', $age_group_id)
                ->whereNotIn('position_id', $age_group_position)
                ->delete();
        }
    }
    public function getListByTryoutId($tryout_id){

        return $this->setSelect()
            ->addTryoutIdFilter($tryout_id)
            ->get();
    }

    public function getAgeGroupPositionNameByTryoutIdAndAgeGroupId($tryout_id, $age_group_id){
        return $this->setSelect()
            ->joinPosition()
            ->addTryoutIdFilter($tryout_id)
            ->addAgeGroupIdFilter($age_group_id)
            ->get(['name'])
            ->pluck('name')
            ->toArray();
    }
}