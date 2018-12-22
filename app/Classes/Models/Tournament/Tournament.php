<?php
namespace App\Classes\Models\Tournament;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Models\BaseModel;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Helpers\Tournament\Helper;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\TournamentOrganization\TournamentOrganization;
use App\Classes\Models\AgeGroupEntryFee\AgeGroupEntryFee;
use App\Classes\Models\Members\Members;


class Tournament extends BaseModel{
    
	protected $table = 'sbc_tournament';
    protected $primaryKey = 'tournament_id';
    
  	protected $entity='sbc_tournament';
	protected $searchableColumns=['tournament_name'];

	protected $_helper;
	protected $stateObj;
	protected $ageGroupObj;
    protected $ageGroupEntryFee;
    protected $memberObj;
    protected $tournamentOrganizationObj;
    protected $cityObj;


    protected $fillable = [ 'tournament_organization_id',
							'submitted_by_id',
							'organizer_name',
							'tournament_name',
							'url_key',
							'competition_level_id',
							'contact_name',
							'start_date',
							'end_date',
							'address_1',
							'address_2',
							'stadium_or_field_name',
							'city_id',
							'state_id',
							'zip',
							'guaranteed_games',
							'hotel_required',
							'phone_number',
							'email',
							'longitude',
							'latitude',
							'event_website_url',
							'age_group_id',
							'field_surface',
							'information',
                            ];


	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {	
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
        $this->stateObj = new State();
        $this->cityObj = new City();
        $this->ageGroupObj = new AgeGroup();
        $this->ageGroupEntryFee = new AgeGroupEntryFee();
        $this->memberObj = new Members();
        $this->tournamentOrganizationObj = new TournamentOrganization();
    }

	/**
	**	Model Relation Methods 
	*/

    public function setStartDateAttribute($value){

        if(!empty($value)) {
            $this->attributes['start_date'] = date("Y-m-d", strtotime($value));
        }else{
            $this->attributes['start_date'] = NULL;
        }
    }

    public function setEndDateAttribute($value){

        if(!empty($value)) {
            $this->attributes['end_date'] = date("Y-m-d", strtotime($value));
        }else{
            $this->attributes['end_date'] = NULL ;
        }
    }

    public function getDatesAttribute(){

        if( !empty($this->start_date) && !empty($this->end_date) ) {
            return date("m/d/Y", strtotime($this->start_date)) . ' - ' . date("m/d/Y", strtotime($this->end_date));
        }
        return '';
    }

	public function state(){

        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function city(){

        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }
    public function tournamentOrganization(){

        return $this->belongsTo(TournamentOrganization::class, 'tournament_organization_id', 'tournament_organization_id');
    }
	
	/**
	**	Model Filter Methods 
	*/	
	public function addTournamentIdFilter($tournament_id=0)
	{
		$this->queryBuilder->where($this->table.'.tournament_id',$tournament_id);
		return $this;
	}

    public function addGuaranteedGamesFilter($guaranteed_games = 0){

        if($guaranteed_games > 0) {
            $this->queryBuilder->where($this->table.'.guaranteed_games', $guaranteed_games);
        }
        return $this;
    }
    public function addHotelRequiredFilter($hotel_required = -1){

        if($hotel_required != -1) {
            $this->queryBuilder->where($this->table.'.hotel_required', $hotel_required);
        }
        return $this;
    }


	public function addTournamentOrganizationIdFilter($tournament_organization_id=0){
		if($tournament_organization_id > 0){
			$this->queryBuilder->where($this->table.'.tournament_organization_id',$tournament_organization_id);
		}
		return $this;
	}
	
	/*
	**	Logic Methods
	*/
	public function load($tournament_id)
    {
    	$this->beforeLoad($tournament_id);
	    
	    $return = $this->setSelect()
	   			  ->addTournamentIdFilter($tournament_id)	
				  ->get()
				  ->first();

		$this->afterLoad($tournament_id, $return);		  
		
		return $return;
   	}

   	public function addAgeGroupIdFilter($age_group_id_array = array())
	{
		
    	if(!empty($age_group_id_array)){
    		$fieldName = $this->table.'.age_group_id';

    		$this->queryBuilder->where(function($q) use ($age_group_id_array, $fieldName) {
    			foreach ($age_group_id_array as $key => $value) {
    				$q->orWhereRaw("find_in_set('".$value."',".$fieldName.")");
	    		}
			});
    	}
		
		return $this;
	}

    public function addEntryFeeFilter($entry_fee = '', $age_group_id)
    {
        if(!empty($entry_fee) && !empty($age_group_id)){

            $ageGroupEntryFeeTable = $this->ageGroupEntryFee->getTable();

            $this->queryBuilder->Where(function($query) use ($ageGroupEntryFeeTable,$age_group_id, $entry_fee) {
                $query->where($ageGroupEntryFeeTable.'.entry_fee', '<', $entry_fee);
                $query->whereIn($ageGroupEntryFeeTable.'.age_group_id', $age_group_id);
            });

        }

        return $this;
    }

    public function joinAgeGroupEntryFee($entry_fee='', $age_group_id,$searchable=false)
    {
        if( count($age_group_id)>0 && !empty($entry_fee) ){
            $ageGroupEntryFeeTable = $this->ageGroupEntryFee->getTable();
            $searchableColumns     = $this->ageGroupEntryFee->getSearchableColumns();
            $this->joinTables[]=array('table'=>$ageGroupEntryFeeTable,'searchable'=>$searchable,'searchableColumns'=>$searchableColumns);
            $this->queryBuilder->leftJoin($ageGroupEntryFeeTable,function($join) use($ageGroupEntryFeeTable) {
                $join->on($this->table.'.tournament_id','=',$ageGroupEntryFeeTable.'.tournament_id');
            });
        }
        return $this;
    }

	public function addFieldSurfaceFilter($field_surface = ''){

    	if(!empty(trim($field_surface)) && $field_surface != '0'){
    		$this->queryBuilder->where($this->table.'.field_surface',$field_surface);
    	}
		return $this;
	}
    public function addDateFilter($start_date='', $end_date=''){

        if(!empty($start_date)){
            $start_date = date("Y-m-d", strtotime($start_date));
            $this->queryBuilder->where($this->table.'.start_date', '>=', $start_date);
        }

        if(!empty($end_date)){
            $end_date = date("Y-m-d", strtotime($end_date));
            $this->queryBuilder->where($this->table.'.end_date', '<=', $end_date);
        }
        return $this;
    }

	public function addCompetitionLevelIdFilter($competition_level_id_array = array()){

		if(!empty($competition_level_id_array)){
    		$fieldName = $this->table.'.competition_level_id';

    		$this->queryBuilder->where(function($q) use ($competition_level_id_array, $fieldName) {
    			foreach ($competition_level_id_array as $key => $value) {
    				$q->orWhereRaw("find_in_set('".$value."',".$fieldName.")");
	    		}
			});
    	}
    	return $this;
	}
	public function addMileRadiusFilter($redius, $latitude='', $longitude=''){

		if(!empty($redius) && $redius > 0 && !empty($latitude) && !empty($longitude)){

		    $tableName = $this->table;

			$this->queryBuilder->selectRaw( $tableName.'.*, ( 6371 *
												        acos(
												            cos( radians( '.$latitude.' ) ) *
												            cos( radians( '.$tableName.'.latitude ) ) *
												            cos(
												                radians( '.$tableName.'.longitude ) - radians( '.$longitude.' )
												            ) +
												            sin(radians( '.$latitude.' )) *
												            sin(radians('.$tableName.'.latitude))
												        )
												    ) `distance`');

    		$this->queryBuilder->having('distance', '<' , $redius);
		}

		return $this;
	}

    public function addSubmittedByIdFilter($submitted_by_id = 0){

        if($submitted_by_id > 0){
            $this->queryBuilder->where($this->table.'.submitted_by_id',$submitted_by_id);
        }
        return $this;
    }

    public function joinTournamentOrganization($searchable=false){

        $tournamentOrganizationObj = new TournamentOrganization();
        $tournamentOrganizationTable = $tournamentOrganizationObj->getTable();
        $searchableColumns = $tournamentOrganizationObj->getSearchableColumns();

        $this->joinTables[] = array('table' => $tournamentOrganizationTable, 'searchable' => $searchable, 'searchableColumns' => $searchableColumns);
        $this->queryBuilder->join($tournamentOrganizationTable, function ($join) use ($tournamentOrganizationTable) {
            $join->on($this->table . '.tournament_organization_id', '=', $tournamentOrganizationTable . '.tournament_organization_id');
        });

        return $this;
    }
	public function list($search='',$page=0, $tournament_organization_id=0, $state_id=0, $city_id =0, $redius=0, $isFront=0, $latitude ='', $longitude='', $age_group_id = array(), $competition_level_id = array(), $entry_fee='', $start_date='', $end_date='',$field_surface='', $tournament_name='', $sortedBy='', $sortedOrder='', $per_page=0, $selectColoumn=array('*'), $submitted_by_id=0, $guaranteed_games=0, $hotel_required=-1){

		$per_page = $per_page == 0 ? $this->_helper->getConfigPerPageRecord() : $per_page;

		/* Sort By Change Table */
		if(!empty($sortedBy)){

		    switch ($sortedBy) {
                case "name":
                    $sortedBy = $this->tournamentOrganizationObj->getTable().'.'.$sortedBy;
                    break;
                default:
                    $sortedBy = $this->table.'.'.$sortedBy;
            }
        }
  		$list=$this->setSelect()
                   ->joinAgeGroupEntryFee($entry_fee, $age_group_id)
                   ->joinTournamentOrganization($searchable=false)
                   ->addEntryFeeFilter($entry_fee, $age_group_id)
  				   ->addTournamentOrganizationIdFilter($tournament_organization_id)
  				   ->addTournamentNameFilter($tournament_name)
                   ->addSubmittedByIdFilter($submitted_by_id)
  				   ->addStateIdFilter($state_id)
  				   ->addCityIdFilter($city_id)
  				   ->addAgeGroupIdFilter($age_group_id)
  				   ->addMileRadiusFilter($redius, $latitude, $longitude)
  				   ->addCompetitionLevelIdFilter($competition_level_id)
  				   ->addDateFilter($start_date, $end_date)
  				   ->addFieldSurfaceFilter($field_surface)
                   ->addGuaranteedGamesFilter($guaranteed_games)
                   ->addHotelRequiredFilter($hotel_required)
  				   ->addSearch($search)
  				   ->addOrderBy($sortedBy, $sortedOrder)
				   ->addPaging($page,$per_page)
				   ->addgroupBy($this->table.'.tournament_id')
				   ->get($selectColoumn);
	   
		if(count($list)>0){
			
			foreach($list as $row){
				
				if(!empty($row->age_group_id)){
					$age_group_id_array = $this->getCSVToArray($row->age_group_id);
				}

				$age_group_list = array();
				
				if(!empty($age_group_id_array)){
					foreach ($age_group_id_array as $row_age_group_id) {
						$age_group_result = $this->ageGroupObj->getAllAgeGropuForCampOrClinic($row_age_group_id);		
						$age_group_list[] = $age_group_result->name;
					}
				}
				
				if($isFront == 0){
					$row['age_group'] = $age_group_list;
				}else{
					$row['age_group'] = $this->getColumnList($age_group_list, ', ');
				}
			}
		}	

		return $list;
   	}
	
	public function listTotalCount($search='', $tournament_organization_id=0, $state_id=0, $city_id =0, $redius=0, $isFront=0, $latitude ='', $longitude='', $age_group_id = array(), $competition_level_id = array(), $entry_fee='', $start_date='', $end_date='',$field_surface='', $tournament_name='', $sortedBy='', $sortedOrder='', $submitted_by_id=0, $guaranteed_games=0, $hotel_required=-1){

        /* Sort By Change Table */
        if(!empty($sortedBy)){

            switch ($sortedBy) {
                case "name":
                    $sortedBy = $this->tournamentOrganizationObj->getTable().'.'.$sortedBy;
                    break;
                default:
                    $sortedBy = $this->table . '.' . $sortedBy;
            }
        }

		$this->reset();
		$count=$this->setSelect()
                       ->joinAgeGroupEntryFee($entry_fee, $age_group_id)
                       ->joinTournamentOrganization($searchable=false)
                       ->addEntryFeeFilter($entry_fee, $age_group_id)
					   ->addTournamentOrganizationIdFilter($tournament_organization_id)
					   ->addTournamentNameFilter($tournament_name)
                       ->addSubmittedByIdFilter($submitted_by_id)
	  				   ->addStateIdFilter($state_id)
	  				   ->addCityIdFilter($city_id)
	  				   ->addAgeGroupIdFilter($age_group_id)
	  				   ->addMileRadiusFilter($redius, $latitude, $longitude)
	  				   ->addCompetitionLevelIdFilter($competition_level_id)
	  				   ->addDateFilter($start_date, $end_date)
	  				   ->addFieldSurfaceFilter($field_surface)
                       ->addGuaranteedGamesFilter($guaranteed_games)
                       ->addHotelRequiredFilter($hotel_required)
	  				   ->addSearch($search)
	  				   ->addOrderBy($sortedBy, $sortedOrder)
					   ->addgroupBy($this->table.'.tournament_id')
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

	public function saveRecord($data, $tournament_organization_id){

        /* Check Duplicate or Form Submit Call */
        $is_form_submit=0;
        if(!empty($data['_token'])){
            $is_form_submit=1;
        }

		$rules=array();	
		$rules=[
				'submitted_by_id'   => 'required',
				'tournament_organization_id'   => 'required',
				'tournament_name'   => 'required',
				'url_key'           => 'required|unique:'.$this->table,  
				'contact_name'      => 'required',
				'address_1'         => 'required',
				'city_id'           => 'required',
				'state_id'          => 'required',
				'zip'               => 'required',
				'phone_number'      => 'required',
				'email'             => 'required|email',
				'event_website_url' => 'required',
				'age_group_id'      => 'required',
				'longitude'         => 'required',  
				'latitude'          => 'required',  
		];

		if(!empty($data['age_group_id'])){
			$data['age_group_id'] = $this->getArrayToCSV($data['age_group_id']);
		}else{
			$data['age_group_id'] ='';
		}
		
		$data['url_key'] = str_slug($data['url_key']);
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',tournament_id';
		}

		$validationResult=$this->validateData($rules,$data);
		$result=array();
		$result['id']='';
		if($validationResult['success']==false){
			$result['success']=false;
			$result['message']=$validationResult['message'];
			$result['id']=$data['id'];
			return $result;
		}
		
		if(!empty($data['competition_level_id'])){
            $competition_level_id = $this->unsetArrayByValue($data['competition_level_id'], 0 );
			$data['competition_level_id'] = $this->getArrayToCSV($competition_level_id);
		}else{
			$data['competition_level_id'] ='';
		}

        if(!empty($data['hotel_required']) && $data['hotel_required'] ='on'){
            $data['hotel_required'] = 1;
        }else{
            $data['hotel_required'] = 0;
        }

		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$tournament = self::findOrFail($data['id']);
		    $tournament ->update($data);	
		    $this->afterSave($data,$tournament);
			$result['id']=$tournament->tournament_id;	
		}else{
		 	$tournament  = self::create($data);
			$result['id']=$tournament->tournament_id;
			$this->afterSave($data,$tournament);
		}
        if($is_form_submit == 1) {
            $this->ageGroupEntryFee->updateAgeGroupEntryFee($result['id'], $data);
        }
		$result['success']=true;
		$result['message']="Tournament Saved Successfully.";
		return $result;
	}
	
	public function display($tournament_id)
    {
	    $return =$this->load($tournament_id);

	   if(!empty($return->age_group_id)){		  
	   	
			$return->age_group_id = $this->getCSVToArray($return->age_group_id);
		}

		if(!empty($return->competition_level_id)){		  
	   	
			$return->competition_level_id = $this->getCSVToArray($return->competition_level_id);
		}
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
		
    public function addIsActiveFilter($is_active=1)
	{
		$this->queryBuilder->where($this->table.'.is_active',$is_active);
		return $this;
	}

	public function addgroupBy($groupByName){
		
		$this->queryBuilder->groupBy($groupByName);	
		return $this;
	}

	public function addOrderBy($_sortedBy='', $_sortedOrder=''){

		if(!empty($_sortedBy) && !empty($_sortedOrder)){
			$this->queryBuilder->orderBy($_sortedBy, $_sortedOrder);
		}
		return $this;
	}
	
	public function convertDataToHtml($tournaments){
		$htmlContent="";
		if(count($tournaments) > 0){
			foreach ($tournaments as $key => $data) {
                $htmlContent .="<tr>
							        <td data-title='".trans('front.tournaments_grid.fields.tournament')."'><a href='".$data->getUrl()."'>".$data->tournament_name."</a></td>
							        <td data-title='".trans('front.tournaments_grid.fields.location')."'>".$data->city->city.', '.$data->state->name."</td>
							        <td data-title='".trans('front.tournaments_grid.fields.organizer')."'>".$data->tournamentOrganization->name."</td>
							        <td data-title='".trans('front.tournaments_grid.fields.start_date')."'>".(($data->start_date !='0000-00-00') ? $data->start_date : '')."</td>
							        <td data-title='".trans('front.tournaments_grid.fields.end_date')."'>".(($data->end_date !='0000-00-00') ? $data->end_date : '')."</td>
							        <td data-title='".trans('front.tournaments_grid.fields.age_groups')."'>".$data->age_group."</td>
							    </tr>";
			}
		}else{
			$htmlContent .="<tr>
								<td colspan='100'>".trans("quickadmin.qa_no_entries_in_table")."</td>
							</tr>";
		}
		return $htmlContent;
	}

	/* Page Builder */

	public function addStateIdFilter($state_id=0){	
		
		if($state_id > 0){
			$this->queryBuilder->where($this->table.'.state_id',$state_id);
		}
		return $this;
	}
	
	public function addCityIdFilter($city_id=0){	

		if(!empty($city_id) && $city_id > 0){
			$this->queryBuilder->where($this->table.'.city_id',$city_id);
		}

		return $this;
	}

	public function addUrlKeyFilter($url_key){

	    if(!empty(trim($url_key))) {
            $this->queryBuilder->where($this->table.'.url_key', $url_key);
        }
		return $this;
	}

	public function getDetailPageByUrlKey($url_key){

	    $tournament = $this->setSelect()
	   			     ->addUrlKeyFilter($url_key)	
				     ->get()
				     ->first();
		
		if(!empty($tournament)){
			/* Age group */
			$age_group_list = array();
			$age_group_id_array = $this->getCSVToArray($tournament->age_group_id);
			if(!empty($age_group_id_array)){
				foreach ($age_group_id_array as $row_age_group_id) {
					$age_group_result = $this->ageGroupObj->getAllAgeGropuForCampOrClinic($row_age_group_id);
					$age_group_list[] = $age_group_result->name;
				}
			}

			$tournament['age_group'] = $this->getColumnList($age_group_list, ' - ');
            $tournament['age_group_entry_fee'] = $this->ageGroupEntryFee->getAgeGroupEntryFeeByTournamentIdForFront($tournament->tournament_id);
		}
		return $tournament;     
   	}

   	public function getUrl(){
        return \URL::to('tournaments') .'/'.$this->url_key;
   	}

   	public function addTournamentNameFilter($tournament_name=''){
   		
   		if(!empty($tournament_name)){

			$this->queryBuilder->where($this->table.'.tournament_name', 'like', '%'.$tournament_name.'%');
		}
		return $this;
	}

	public function HeaderSearch( $search, $city_id, $state_id, $latitude, $longitude, $redius ){
		
		$per_page = $this->_helper->getConfigRecordForTopsearch();

		$selectColoumn = [$this->table.'.tournament_id',$this->table.'.tournament_name',$this->table.'.url_key'];

		$list = $this->list($search, $page=0, $tournament_organization_id=0, $state_id, $city_id, $redius, $isFront=1, $latitude, $longitude, $age_group_id = array(), $competition_level_id = array(), $entry_fee='', $start_date='', $end_date='',$field_surface='', $tournament_name='', $sortedBy='tournament_name', $sortedOrder='ASC' , $per_page, $selectColoumn );
		
		$listArray = array();
	    if(!empty($list)) {
    		foreach ($list as $key => $value) {
    			$data['title'] = $value->tournament_name;
    			$data['url'] = $value->getUrl();
    			$listArray[] = $data;
    		}
	    }
		return $listArray;		     
	}

    public function checkDuplicateUrlKey($url_key){
        return $this->setSelect()
            ->addUrlKeyFilter($url_key)
            ->get()
            ->count();
    }

    public function loadByTournamentIdForEnquiry($tournament_id){

	    $selectColoumn = [$this->table.'.contact_name',
                          $this->table.'.email'];

	    $return = $this->setSelect()
            ->addTournamentIdFilter($tournament_id)
            ->get($selectColoumn)
            ->first();

        return $return;
    }

    public function exportCSV($entity, $search, $page){

        $selectedColumns = ['tournament_id', 'tournament_organization_id', 'submitted_by_id', 'tournament_name', 'competition_level_id', 'contact_name', 'start_date', 'end_date', 'address_1', 'address_2', 'stadium_or_field_name', 'state_id', 'city_id', 'zip', 'phone_number', 'email', 'guaranteed_games', 'hotel_required', 'event_website_url', 'age_group_id', 'longitude', 'latitude', 'field_surface', 'information'];
        $csvHeaderLable = ['tournament_id' => 'Tournament Id',
                            'tournament_organization_id' => 'Tournament Organization',
                            'submitted_by_id' => 'Member',
                            'tournament_name' => 'Tournament Name',
                            'competition_level_id' => 'Competition Level Id',
                            'contact_name' => 'Contact Name',
                            'start_date' => 'Start Date',
                            'end_date' => 'End Date',
                            'address_1' => 'Address 1',
                            'address_2' => 'Address 2',
                            'stadium_or_field_name' => 'Stadium / Field Name',
                            'state_id' => 'State',
                            'city_id' => 'City',
                            'zip' => 'Zip',
                            'phone_number' => 'Phone Number',
                            'email' => 'Email',
                            'guaranteed_games' => 'Guaranteed Games',
                            'hotel_required' => 'Hotel Required',
                            'event_website_url' => 'Event Website',
                            'age_group_id' => 'Age Groups',
                            'longitude' => 'Longitude',
                            'latitude' => 'Latitude',
                            'field_surface' => 'Field Surface',
                            'information' => 'Information'];

        $results = $this->list($search,$page, $tournament_organization_id=0, $state_id=0, $city_id =0, $redius=0, $isFront=0, $latitude ='', $longitude='', $age_group_id = array(), $competition_level_id = array(), $entry_fee='', $start_date='', $end_date='',$field_surface='', $tournament_name='', $sortedBy='', $sortedOrder='', $per_page=0, $selectColoumn=array('*'), $submitted_by_id=0);
        $csvExportPath = $this->_helper->getCsvExportFolderPath();

        /* Data Format */
        if(!empty($results)){
            foreach ($results as $value) {

                if($value->submitted_by_id > 0){
                    $value->submitted_by_id = $this->memberObj->getMemberEmailByMemberId($value->submitted_by_id);
                }else{
                    $value->submitted_by_id = "";
                }

                if(!empty($value->age_group_id)){
                    $ageGroupWithEntryFee = array();
                    $age_group_array =  explode(',', $value->age_group_id);

                    foreach ($age_group_array  as $key=> $value_age_group_Id ) {
                        $entryFee = '';
                        $ageGroupName = $this->ageGroupObj->getAgeGroupNameById($value_age_group_Id);
                        $entryFee = $this->ageGroupEntryFee->getEntryFeeByAgeGroupAndTournamentId($value_age_group_Id, $value->tournament_id);
                        if(!empty($entryFee)){
                            $ageGroupName = $ageGroupName.'-'.$entryFee;
                        }
                        $ageGroupWithEntryFee[] = $ageGroupName;
                    }
                    $value->age_group_id = implode($ageGroupWithEntryFee,':');
                }

                $value->tournament_organization_id= $this->tournamentOrganizationObj->getNameByTournamentOrganizationId($value->tournament_organization_id);

                if(!empty($value->competition_level_id)){
                    $competition_level_id = $value->competition_level_id;
                    $competitionLevellist = $this->_helper->getCompetitionLevellist();
                    $competition_level_array = explode(',',$competition_level_id);
                    $listOfCompetitionLevel =array();
                    foreach ($competition_level_array as $key => $data){
                        $listOfCompetitionLevel[] = $competitionLevellist[$data];
                    }
                    $competition_level_id = implode(':',$listOfCompetitionLevel);
                    $value->competition_level_id = $competition_level_id;
                }

                $value->state_id = $value->state->name;
                $value->city_id = $value->city->city;
                $value->hotel_required = ($value->hotel_required == 1) ? 'Yes' : 'No';
            }
        }

        $response = $this->generateCSV( $results, $entity, $csvHeaderLable, $selectedColumns, $csvExportPath );
        return $response;
    }

    public function checkUrlKeyDuplicate($url_key){

        $result = $this->checkDuplicateUrlKey($url_key);
        if($result == 1 || $result == '1'){
            $url_key = $this->generateDuplidateUrlKey($url_key);
            $url_key = $this->checkUrlKeyDuplicate($url_key);
        }
        return $url_key;

    }
    public function importCSV($data){

        $response = array('success' => false);

        $csvImportFolderPath = $this->_helper->getCsvImportFolderPath();
        $csvImportResultsFolderPath = $this->_helper->getCsvImportResultsFolderPath();

        /* Upload File */
        $file = $data['csv_file'];
        $results = $this->uploadCSV( $file, $csvImportFolderPath, $csvImportResultsFolderPath );

        if(!empty($results)){

            $rowErrors = array();
            $successCount  = 0;
            $errorCount	   = 0;
            $totalCsvRecord= 0;

            if(!empty($results['FilePath'])) {

                $filePath = $results['FilePath'];
                $resultFilePath = $results['ResultsFilePath'];

                $file = fopen( public_path( $filePath ) , "r");
                $resultFile = fopen( public_path( $resultFilePath ) , "w+");

                $csvHeader = fgetcsv($file); //CSV Header Columns
                $unsetBlankCsvColumnsIndexes = array();

                foreach($csvHeader as $key=>$value){
                    $value = trim($value);
                    if($value==''){
                        $unsetBlankCsvColumnsIndexes[] = $key;
                        unset($csvHeader[$key]);
                    }else {
                        $csvHeader[$key] = trim($value);
                    }
                }

                /* Header Column */
                $header = array(
                    'Tournament Id'=>array('db_column'=>'tournament_id'),
                    'Tournament Organization'=>array('db_column'=>'tournament_organization_id', 'required'=>true, 'reference_key'=>true,'reference_function'=>'checkTournamentOrganizationReference'),
                    'Member'=>array('db_column'=>'submitted_by_id','reference_key_with_zero_allow'=>true,'reference_function'=>'checkMemberReference'),
                    'Tournament Name'=>array('db_column'=>'tournament_name'),
                    'Competition Level Id'=>array('db_column'=>'competition_level_id','custom_function'=>'checkCompetitionLevel'),
                    'Contact Name'=>array('db_column'=>'contact_name'),
                    'Start Date'=>array('db_column'=>'start_date','custom_function'=>'checkDateReference'),
                    'End Date'=>array('db_column'=>'end_date','custom_function'=>'checkDateReference'),
                    'Address 1'=>array('db_column'=>'address_1','required'=>true),
                    'Address 2'=>array('db_column'=>'address_2'),
                    'Stadium / Field Name'=>array('db_column'=>'stadium_or_field_name'),
                    'State'=>array('db_column'=>'state_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkStateReference'),
                    'City'=>array('db_column'=>'city_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkCityReference'),
                    'Zip'=>array('db_column'=>'zip','required'=>true),
                    'Phone Number'=>array('db_column'=>'phone_number','required'=>true),
                    'Email'=>array('db_column'=>'email','required'=>true),
                    'Guaranteed Games'=>array('db_column'=>'guaranteed_games'),
                    'Hotel Required'=>array('db_column'=>'hotel_required'),
                    'Event Website'=>array('db_column'=>'event_website_url','required'=>true),
                    'Age Groups'=>array('db_column'=>'age_group_id','required'=>true,'custom_function'=>'checkAgeGroupReference'),
                    'Longitude'=>array('db_column'=>'longitude','required'=>true),
                    'Latitude'=>array('db_column'=>'latitude','required'=>true),
                    'Field Surface'=>array('db_column'=>'field_surface','required'=>true),
                    'Information'=>array('db_column'=>'information'),

                );

                // CSV Columns validation
                $rowErrors = $this->checkCsvColumnValidation($csvHeader, $header);

                if(count($rowErrors)>0){
                    return response()->json([
                            'success' => false,
                            'message' => 'Could not import csv file due to following errors. Please try again. <br>'.$rowErrors[0],
                        ]
                    );
                }

                /* Result add column. */
                $resultCsvHeader = $csvHeader;
                $resultCsvHeader[]='result';
                $resultCsvHeader[]='result_message';
                fputcsv($resultFile,$resultCsvHeader);

                while($row = fgetcsv($file)) {
                    foreach ($row as $index => $value) {
                        if (in_array($index, $unsetBlankCsvColumnsIndexes)) {
                            unset($row[$index]);
                        } else {
                            $row[$index] = trim($value);
                        }
                    }

                    $totalCsvRecord++;
                    $csvDataValue=array_combine($csvHeader,$row);
                    $tableEntryRow=array();
                    $resultRow = $csvDataValue;
                    $resultRow['result']='success';
                    $resultRow['result_message']='';
                    $validRow = true;

                    foreach($header as $headerKey => $headerValue) {
                        $csvValue = trim($csvDataValue[$headerKey]);
                        $dbColumnName = $headerValue['db_column'];

                        if (!$validRow) {
                            continue;
                        }

                        if (!empty($headerValue['required']) && $headerValue['required']) {

                            if ($csvValue == '') {
                                $resultRow['result_message'] = $headerKey.' is required.';
                                $validRow = false;
                                continue;
                            }
                        }

                        if (!empty($headerValue['reference_key']) && $headerValue['reference_key']) {

                            $reference_function = $headerValue['reference_function'];

                            if($reference_function == "checkCityReference"){
                                $isExistReferenceID = $this->{$reference_function}($csvValue, $tableEntryRow['state_id']);

                            }else{
                                $isExistReferenceID = $this->{$reference_function}($csvValue);
                            }

                            if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                $resultRow['result_message'] = 'Reference ' . $headerKey. ' is not exist';
                                $validRow = false;
                                continue;
                            }

                            $csvValue = $isExistReferenceID;
                        }

                        if ( !empty($headerValue['reference_key_with_zero_allow']) && $headerValue['reference_key_with_zero_allow'] ) {

                            $reference_function = $headerValue['reference_function'];

                            if(!empty($csvValue) && $csvValue !== 0){

                                $isExistReferenceID = $this->{$reference_function}($csvValue);

                                if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                    $resultRow['result_message'] = 'Reference ' . $headerKey. ' is not exist';
                                    $validRow = false;
                                    continue;
                                }

                                $csvValue = $isExistReferenceID;

                            }else{
                                $csvValue = 0;
                            }
                        }

                        if (!empty($headerValue['custom_function']) && $headerValue['custom_function']) {

                            $custom_function = $headerValue['custom_function'];

                            if($custom_function == "checkCompetitionLevel"){
                                
                                if (!empty($csvValue)) {
                                    $competitionLevelId = array();
                                    $competitionLevelList = explode(':', $csvValue);
                                    foreach ($competitionLevelList as $key => $competitionName){
                                        $isExistReferenceID = $this->_helper->getCompetitionLevelIdByName($competitionName);

                                        if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                            $resultRow['result_message'] = 'Reference '.$headerKey.' in '.$competitionName.' is not exist';
                                            $validRow = false;
                                            continue;
                                        }

                                        $competitionLevelId[] = $isExistReferenceID;
                                    }
                                    $csvValue = implode(',', $competitionLevelId);
                                }
                            }

                            if($custom_function == "checkAgeGroupReference") {

                                if (!empty($csvValue)) {
                                    $ageGroupWithEntryFee = explode(':', $csvValue);
                                    $ageGroupId = array();
                                    foreach ($ageGroupWithEntryFee as $key => $ageGroupValue) {
                                        $ageGroup = explode('-', $ageGroupValue);
                                        $ageGroupName = $ageGroup[0];
                                        $isExistReferenceID = $this->{$custom_function}($ageGroupName);
                                        $ageGroupId[] = $isExistReferenceID;

                                        if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                            $resultRow['result_message'] = 'Reference ' . $ageGroupName . ' is not exist';
                                            $validRow = false;
                                            continue;
                                        }
                                    }

                                    $csvValue = implode(',', $ageGroupId);
                                }
                            }

                            if($custom_function == "checkDateReference") {

                                if(empty($resultRow['Start Date']) && !empty($resultRow['End Date'])){
                                    $resultRow['result_message'] = 'If enter End Date then must be required Start Date';
                                    $validRow = false;
                                    continue;
                                }

                                if(!empty($resultRow['Start Date']) && empty($resultRow['End Date'])){
                                    $resultRow['result_message'] = 'If enter Start Date then must be required End Date';
                                    $validRow = false;
                                    continue;
                                }

                                /* Date Rang */
                                if(!empty($resultRow['Start Date']) || !empty($resultRow['End Date'])) {
                                    if ((strtotime($resultRow['Start Date'])) >= (strtotime($resultRow['End Date']))) {
                                        $resultRow['result_message'] = 'Invalid Start Date End Date range';
                                        $validRow = false;
                                        continue;
                                    }
                                }
                            }
                        }
                        $tableEntryRow[$headerValue['db_column']] = $csvValue;
                    }

                    if($validRow){

                        $tableEntryRow['hotel_required'] = ( ($tableEntryRow['hotel_required'] == 'Yes') ? 1 : 0 );

                        if(!empty($tableEntryRow[$this->primaryKey]) && $tableEntryRow[$this->primaryKey] > 0){
                            $id = $tableEntryRow[$this->primaryKey];
                            $results = self::findOrFail($id);
                            $results ->update($tableEntryRow);

                        }else {

                            $StartDate = "";
                            if (!empty($resultRow['Start Date'])) {
                                $date = explode('/', $resultRow['Start Date']);
                                $StartDate = $date[2] . '-' . $date[1] . '-' . $date[0];
                            }
                            $tableEntryRow['url_key'] = $this->generateUrlKey([$resultRow['Tournament Name'], $resultRow['City'], $resultRow['State'], $StartDate]);
                            $tableEntryRow['url_key'] = $this->checkUrlKeyDuplicate($tableEntryRow['url_key']);
                            $results = $this::create($tableEntryRow);
                        }

                        /*Age Group Entry Fee */
                        if(!empty($resultRow['Age Groups'])){
                            $tournament_id = $results->{$this->primaryKey};
                            $ageGroupWithEntryFee = explode(':', $resultRow['Age Groups']);
                            $age_group_array = array();
                            foreach ($ageGroupWithEntryFee as $key => $ageGroupValue) {
                                $ageGroup = explode('-', $ageGroupValue);
                                $entryFee = 0;
                                $ageGroupName = $ageGroup[0];
                                if(!empty($ageGroup[1])){
                                    $entryFee = $ageGroup[1];
                                }
                                $ageGroupId = $this->checkAgeGroupReference($ageGroupName);
                                $age_group_array[] = $ageGroupId;

                                /* Create Or Update*/
                                $ageGroupEntryFee = AgeGroupEntryFee::firstOrNew(array('age_group_id' => $ageGroupId,
                                                                                        'tournament_id' => $tournament_id));
                                $ageGroupEntryFee->entry_fee = $entryFee;
                                $ageGroupEntryFee->save();
                            }

                            /* Delete Record */
                            AgeGroupEntryFee::where('tournament_id', $tournament_id)
                                ->whereNotIn('age_group_id', $age_group_array)
                                ->delete();
                        }
                    }

                    if(!$validRow){
                        $resultRow['result']='failed';
                    }else if(!empty($results->{$this->primaryKey}) && $results->{$this->primaryKey}>0){

                        $successCount++;
                    }
                    fputcsv($resultFile,$resultRow);
                    $errorCount++;
                }

                fclose($resultFile);
                $fileUrl = \URL::to('administrator/download_csv?filepath='.$resultFilePath);
                return response()->json([
                    'success' => true,
                    'message' => $successCount.' out of '.$totalCsvRecord.' records imported sucessfully. </br> Click on <b>"Download Result File"</b> button to view import result.',
                    'resultFilePath'=>$fileUrl,

                ]);
            }
        }

        return $response;
    }

    /* Add Dynamic Column filter */
    public function addDynamicColumnFilter($DBFieldName, $DBValue){
        return $this->queryBuilder->where($DBFieldName, $DBValue);
    }

    /* check duplicate record */
    public function checkDuplicateRecord($DBFieldName, $DBValue){
        return $this->setSelect()
            ->addDynamicColumnFilter($DBFieldName, $DBValue)
            ->get()
            ->count();
    }

    /* check Images model reference key */
    public function checkImageReference($imagePath){
        $module_id = $this->_helper->getModuleId();
        return $this->ImagesObj->recordCount($imagePath, $module_id);
    }

    /* check State model reference key */
    public function checkStateReference($stateName){
        return $this->stateObj->recordCount($stateName);
    }

    /* check City model reference key */
    public function checkCityReference($cityName, $state_id){
        return $this->cityObj->recordCount($cityName, $state_id);
    }

    /* check Member model reference key */
    public function checkMemberReference($memberEmail){
        return $this->memberObj->recordCount($memberEmail);
    }

    /* check AgeGroup model reference key */
    public function checkAgeGroupReference($name){
        $module_id = $this->_helper->getModuleId();
        return $this->ageGroupObj->getAgeGroupIdByName($name, $module_id);
    }

    /* check TournamentOrganization model reference key */
    public function checkTournamentOrganizationReference($name){
        return $this->tournamentOrganizationObj->recordCount($name);
    }

}
