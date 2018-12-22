<?php
namespace App\Classes\Models\Review;

use App\Classes\Models\BaseModel;
use App\Classes\Models\Members\Members;
use App\Classes\Models\Team\Team;
use App\Classes\Models\Academies\Academies;

class Review extends BaseModel{
    
	protected $table = 'sbc_review';
    protected $primaryKey = 'review_id';
    
  	protected $entity='sbc_review';
	protected $searchableColumns=['member_first_name','member_last_name'];
    protected $fillable = ['review_id',
							'member_id',
							'member_first_name',
							'member_last_name',
							'type',
							'review_for_id',
							'review_for_first_name',
							'review_for_last_name',
							'review_summary',
							'review_detail',
							'is_active'];


	/*Copy from parent*/
	public function __construct(array $attributes = []){

        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper =new \App\Classes\Helpers\Helper();
    }

    public function addRevireIdFilter($review_id=0){

		$this->queryBuilder->where('review_id',$review_id);
		return $this;
	}

    public function addMemberIdFilter($member_id=0){
        if($member_id > 0) {
            $this->queryBuilder->where($this->table.'.member_id', $member_id);
        }
        return $this;
    }


	public function addTypeFilter($type){

		$this->queryBuilder->where('type',$type);
		return $this;
	}

   	public function listForInstructor($search='',$page=0, $type){

  		$per_page=$this->_helper->getConfigPerPageRecord();

  		$list = $this->setSelect()
  				   ->addTypeFilter($type)
  				   ->addSearch($search)
				   ->addPaging($page,$per_page)
				   ->get();
		
		return $list;
   	}

   	public function listForTeam($search='',$page=0, $type, $member_id=0){

  		$per_page=$this->_helper->getConfigPerPageRecord();

  		$list = $this->setSelect()
  				   ->addTypeFilter($type)
                   ->addMemberIdFilter($member_id)
  				   ->joinTeam()
  				   ->addSearch($search)
				   ->addPaging($page,$per_page)
				   ->get();
		
		return $list;
   	}

   	public function listForTournamentOrganization($search='',$page=0, $type, $member_id=0){

  		$per_page=$this->_helper->getConfigPerPageRecord();

  		$list = $this->setSelect()
  				   ->addTypeFilter($type)
                   ->addMemberIdFilter($member_id)
  				   ->joinTeam()
  				   ->addSearch($search)
				   ->addPaging($page,$per_page)
				   ->get();
		
		return $list;
   	}
	
	public function listTotalCount($search='', $type, $member_id=0){
		$this->reset();
		$count=$this->setSelect()
				    ->addSearch($search)
                    ->addTypeFilter($type)
                    ->addMemberIdFilter($member_id)
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
	
	public function saveRecord($data){

		if(!empty($data['is_active']) && $data['is_active'] == 'on'){
			$data['is_active'] = 1;
		}else{
			$data['is_active'] = 0;
		}
		$this->beforeSave($data);
	  	$review = \App\Classes\Models\Review\Review::findOrFail($data['id']);
	  	$review->update($data);	
	  	$this->afterSave($data,$review);
		$result['id']=$review->review_id;	
		
		$result['success']=true;
		$result['message']="Review Saved Successfully.";
		return $result;
	}

   	public function joinMembers($searchable=false){

		$members = new Members;
		$membersTable = $members->getTable();
		$searchableColumns = $members->getSearchableColumns();

		$this->joinTables[]=array('table'=>$membersTable,'searchable'=>$searchable,'searchableColumns'=>$searchableColumns);
		$this->queryBuilder->join($membersTable,function($join) use($membersTable) {
			$join->on($this->table.'.member_id', '=', $membersTable.'.member_id');
		});
		return $this;
	}

	public function joinTeam($searchable=false){

		$team = new Team;
		$teamTable = $team->getTable();
		$searchableColumns = $team->getSearchableColumns();

		$this->joinTables[]=array('table'=>$teamTable,'searchable'=>$searchable,'searchableColumns'=>$searchableColumns);
		$this->queryBuilder->join($teamTable,function($join) use($teamTable) {
			$join->on($this->table.'.member_id', '=', $teamTable.'.team_id');
		});
		return $this;
	}

	public function joinAcademy($searchable=false){

		$academies = new Academies;
		$academiesTable = $academies->getTable();
		$searchableColumns = $academies->getSearchableColumns();

		$this->joinTables[]=array('table'=>$academiesTable,'searchable'=>$searchable,'searchableColumns'=>$searchableColumns);
		$this->queryBuilder->join($academiesTable,function($join) use($academiesTable) {
			$join->on($this->table.'.member_id', '=', $academiesTable.'.academy_id');
		});

		return $this;
	}

   	public function displayInstructor($review_id){

	    $return =$this->setSelect()
	   			  ->addRevireIdFilter($review_id)	
	   			  ->joinMembers()
				  ->get()
				  ->first();

		return $return;
   	}

   	public function displayTeam($review_id){

   		$team = new Team();

   		$reviewTable = $this->getTable();
		$teamTable = $team->getTable();

		$selectedColumns=array();
		$selectedColumns = [

			$reviewTable.'.review_id',
			$reviewTable.'.member_id',
			$reviewTable.'.member_first_name',
			$reviewTable.'.member_last_name',
			$reviewTable.'.type',
			$reviewTable.'.review_summary',
			$reviewTable.'.review_detail',
			$reviewTable.'.is_active'
		];

	    $return =$this->setSelect()
	   			  ->addRevireIdFilter($review_id)	
	   			  ->joinTeam()
				  ->get($selectedColumns)
				  ->first();
		
		return $return;
   	}

   	public function displayAcademy($review_id){

   		$academies = new Academies;
		$academiesTable = $academies->getTable();

   		$reviewTable = $this->getTable();

		$selectedColumns=array();
		
		$selectedColumns = [

			$reviewTable.'.review_id',
			$reviewTable.'.member_id',
			$reviewTable.'.member_first_name',
			$reviewTable.'.member_last_name',
			$reviewTable.'.type',
			$reviewTable.'.review_for_first_name',
			$reviewTable.'.review_summary',
			$reviewTable.'.review_detail',
			$reviewTable.'.is_active',

			$academiesTable.'.email'

		];

	    $return =$this->setSelect()
	   			  ->addRevireIdFilter($review_id)	
	   			  ->joinAcademy()	
				  ->get($selectedColumns)
				  ->first();
		
		return $return;
   	}

   	public function listForAcademy($search='',$page=0, $type, $member_id=0){

  		$per_page=$this->_helper->getConfigPerPageRecord();

  		$academies = new Academies;
		$academiesTable = $academies->getTable();

   		$reviewTable = $this->getTable();

		$selectedColumns=array();
		
		$selectedColumns = [

			$reviewTable.'.review_id',
			$reviewTable.'.member_id',
			$reviewTable.'.member_first_name',
			$reviewTable.'.member_last_name',
			$reviewTable.'.type',
			$reviewTable.'.review_for_first_name',
			$reviewTable.'.review_summary',
			$reviewTable.'.review_detail',
			$reviewTable.'.is_active',
			
		];

  		$list = $this->setSelect()
  				   ->addTypeFilter($type)
                   ->addMemberIdFilter($member_id)
  				   ->joinAcademy()
  				   ->addSearch($search)
				   ->addPaging($page,$per_page)
				   ->get($selectedColumns);
		
		return $list;
   	}

   	public function displayTournamentOrganization($review_id){

   		$team = new Team();

   		$reviewTable = $this->getTable();
		$teamTable = $team->getTable();

		$selectedColumns=array();
		$selectedColumns = [

			$reviewTable.'.review_id',
			$reviewTable.'.member_id',
			$reviewTable.'.member_first_name',
			$reviewTable.'.member_last_name',
			$reviewTable.'.type',
			$reviewTable.'.review_summary',
			$reviewTable.'.review_detail',
			$reviewTable.'.is_active'
		];

	    $return =$this->setSelect()
	   			  ->addRevireIdFilter($review_id)	
	   			  ->joinTeam()
				  ->get($selectedColumns)
				  ->first();
		
		return $return;
   	}
}