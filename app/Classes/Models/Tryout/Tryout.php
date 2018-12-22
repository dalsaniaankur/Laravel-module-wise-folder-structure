<?php
namespace App\Classes\Models\Tryout;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Models\BaseModel;
use App\Classes\Models\State\State;
use App\Classes\Helpers\Tryout\Helper;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\Team\Team;
use App\Classes\Models\AgeGroupPosition\AgeGroupPosition;
use App\Classes\Models\City\City;
use App\Classes\Models\Members\Members;
use App\Classes\Models\Position\Position;
use App\Classes\Helpers\AgeGroupPosition\Helper as AgeGroupPositionHelper;
use App\Classes\Models\TryoutDate\TryoutDate;


class Tryout extends BaseModel{
    
	protected $table = 'sbc_tryout';
    protected $primaryKey = 'tryout_id';
    
  	protected $entity='tryout';
	protected $searchableColumns=['tryout_name'];

	protected $_helper;
	protected $stateObj;
	protected $ageGroupObj;
	protected $ageGroupPositionObj;
    protected $teamObj;
    protected $memberObj;
    protected $cityObj;
    protected $positionObj;
    protected $tryoutDateObj;
    protected $_ageGroupPositionHelper;

    protected $fillable = [ 'team_id',
							'submitted_by_id',
							'url_key',
							'tryout_name',
							'contact_name',
							'start_date',
							'end_date',
							'address_1',
							'address_2',
							'location',
							'city_id',
							'state_id',
							'zip',
							'phone_number',
							'email',
							'longitude',
							'latitude',
							'attachment_name_1',
							'attachment_path_1',
							'attachment_name_2',
							'attachment_path_2',
							'age_group_id',
							'information',
                            ];


	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {	
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
        $this->_ageGroupPositionHelper = new AgeGroupPositionHelper();
        $this->stateObj = new State();
        $this->ageGroupObj = new AgeGroup();
        $this->ageGroupPositionObj = new AgeGroupPosition();
        $this->teamObj = new Team();
        $this->stateObj = new State();
        $this->memberObj = new Members();
        $this->cityObj = new City();
        $this->positionObj = new Position();
        $this->tryoutDateObj = new TryoutDate();
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

    public function team(){

        return $this->belongsTo(Team::class, 'team_id', 'team_id');
    }
	
	/**
	**	Model Filter Methods 
	*/	
	public function addTournamentIdFilter($tryout_id=0)
	{
		$this->queryBuilder->where('tryout_id',$tryout_id);
		return $this;
	}

	public function addTryoutIdFilter($tryout_id=0)
	{
		$this->queryBuilder->where('tryout_id',$tryout_id);
		return $this;
	}

	public function addTeamIdFilter($team_id=0)
	{
		if(!empty($team_id) && $team_id > 0){
			$this->queryBuilder->where('team_id',$team_id);
		}
		return $this;
	}
	
	/*
	**	Logic Methods
	*/
	public function load($tryout_id)
    {
    	$this->beforeLoad($tryout_id);
	    
	    $return = $this->setSelect()
	   			  ->addTournamentIdFilter($tryout_id)	
				  ->get()
				  ->first();

		$this->afterLoad($tryout_id, $return);		  
		
		return $return;
   	}

    public function addDateFilter($start_date='', $end_date=''){

        if(!empty($start_date) || !empty($end_date)){

            $tryoutDateObj = new TryoutDate;
            $tryoutDateTable=$tryoutDateObj->getTable();
            $this->queryBuilder->Join($tryoutDateTable, function ($join) use ($tryoutDateTable, $start_date,$end_date) {
                $join->on($this->table.'.tryout_id', '=', $tryoutDateTable.'.tryout_id');

            });
        }

        if(!empty($start_date)){
            $start_date = date("Y-m-d", strtotime($start_date));
            $this->queryBuilder->where(function($q) use ($tryoutDateTable, $start_date, $end_date) {
                $q->where($tryoutDateTable.'.date', '>=', "$start_date");
            });
        }

        if(!empty($end_date)){
            $end_date = date("Y-m-d", strtotime($end_date));
            $this->queryBuilder->where(function($q) use ($tryoutDateTable, $start_date, $end_date) {
                $q->Where($tryoutDateTable.'.date', '<=', "$end_date");
            });
        }

        return $this;
    }

	public function addAgeGroupIdFilter($age_group_id_array=array()){

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

    public function addPositionFilter($position_id_array, $age_group_id)
    {
        if(!empty($position_id_array) && ($position_id_array[0] ==0 || $position_id_array[0] == '0')){ unset($position_id_array[0]); }

        if( !empty($position_id_array) && !empty($age_group_id) && count($position_id_array)>0 && count($age_group_id) > 0 ){

            $ageGroupPositionTable = $this->ageGroupPositionObj->getTable();

            $this->queryBuilder->Where(function($query) use ($ageGroupPositionTable,$position_id_array, $age_group_id) {
                $query->whereIn($ageGroupPositionTable.'.position_id',  $position_id_array);
                $query->whereIn($ageGroupPositionTable.'.age_group_id', $age_group_id);
            });
        }

        return $this;
    }

    public function joinAgeGroupPosition($position_id_array, $age_group_id, $searchable=false)
    {
        if(!empty($position_id_array) && ($position_id_array[0] ==0 || $position_id_array[0] == '0')){ unset($position_id_array[0]); }

        if( !empty($position_id_array) && !empty($age_group_id) && count($position_id_array)>0 && count($age_group_id) > 0 ){

            $ageGroupPositionTable = $this->ageGroupPositionObj->getTable();
            $searchableColumns     = $this->ageGroupPositionObj->getSearchableColumns();
            $this->joinTables[]=array('table'=>$ageGroupPositionTable,'searchable'=>$searchable,'searchableColumns'=>$searchableColumns);
            $this->queryBuilder->leftJoin($ageGroupPositionTable,function($join) use($ageGroupPositionTable) {
                $join->on($this->table.'.tryout_id','=',$ageGroupPositionTable.'.tryout_id');
            });
        }
        return $this;
    }

	public function addMileRadiusFilter($redius, $latitude='', $longitude=''){
		
		if(!empty($redius) && $redius > 0 && !empty($latitude) && !empty($longitude)){

			$this->queryBuilder->selectRaw('*, ( 6371 *
												        acos(
												            cos( radians( '.$latitude.' ) ) *
												            cos( radians( `latitude` ) ) *
												            cos(
												                radians( `longitude` ) - radians( '.$longitude.' )
												            ) +
												            sin(radians( '.$latitude.' )) *
												            sin(radians(`latitude`))
												        )
												    ) `distance`');

    		$this->queryBuilder->having('distance', '<' , $redius);

		}

		return $this;
	}

    public function addSubmittedByIdFilter($submitted_by_id = 0){

        if($submitted_by_id > 0){
            $this->queryBuilder->where('submitted_by_id',$submitted_by_id);
        }
        return $this;
    }

	public function list($search='',$page=0, $team_id=0, $state_id=0, $city_id=0, $redius=0, $isFront=0 , $latitude='', $longitude='', $age_group_id=array(), $position_id=array(), $start_date='',$end_date='', $tryout_name='', $sortedBy='', $sortedOrder='', $per_page=0, $selectColoumn=array('*'), $submitted_by_id=0){

		$per_page = $per_page == 0 ? $this->_helper->getConfigPerPageRecord() : $per_page;
  		$list=$this->setSelect()
                   ->joinAgeGroupPosition($position_id, $age_group_id)
  				   ->addTeamIdFilter($team_id)
  				   ->addTryoutName($tryout_name)
                   ->addSubmittedByIdFilter($submitted_by_id)
  				   ->addCityIdFilter($city_id)
                   ->addStateIdFilter($state_id)
  				   ->addDateFilter($start_date,$end_date)
  				   ->addMileRadiusFilter($redius, $latitude, $longitude)
  				   ->addAgeGroupIdFilter($age_group_id)
  				   ->addPositionFilter($position_id, $age_group_id)
  				   ->addSearch($search)
  				   ->addOrderBy($sortedBy, $sortedOrder)
				   ->addPaging($page,$per_page)
				   ->addgroupBy($this->table.'.tryout_id')
				   ->get();

		if(count($list)>0){
			$ageGroupForModuleWiseObj = new AgeGroup();
			foreach($list as $key => $row){
				if(!empty($row->age_group_id)){
					$age_group_id_array = $this->getCSVToArray($row->age_group_id);
				}
				$age_group_list = array();
				if(!empty($age_group_id_array)){
					foreach ($age_group_id_array as $row_age_group_id) {
						$age_group_result = $ageGroupForModuleWiseObj->getAllAgeGropuForCampOrClinic($row_age_group_id);		
						$age_group_list[] = $age_group_result->name;
					}
				}
				if($isFront == 0){
					$row['age_group'] = $age_group_list;	
				}else{
					$row['age_group'] = $this->getColumnList($age_group_list, ', ');
                    $dateList = $this->tryoutDateObj->getDateListByTryoutId($row['tryout_id']);
                    $list[$key]['dateList']= implode(', ',$dateList);
				}
			}

		}
		return $list;
   	}
	
	public function listTotalCount($search='', $team_id=0, $state_id=0, $city_id=0, $redius=0, $isFront=0, $latitude='', $longitude='', $age_group_id=array(), $position_id=array(),$start_date='',$end_date='', $tryout_name='', $sortedBy='', $sortedOrder='', $submitted_by_id=0){
		$this->reset();
		$count=$this->setSelect()
                    ->joinAgeGroupPosition($position_id, $age_group_id)
				    ->addSearch($search)
 				    ->addTeamIdFilter($team_id)
 				    ->addTryoutName($tryout_name)
                    ->addSubmittedByIdFilter($submitted_by_id)
                    ->addCityIdFilter($city_id)
                    ->addStateIdFilter($state_id)
				    ->addMileRadiusFilter($redius, $latitude, $longitude)
				    ->addDateFilter($start_date,$end_date)
   				    ->addAgeGroupIdFilter($age_group_id)
  				    ->addPositionFilter($position_id, $age_group_id)
  				    ->addSearch($search)
  				    ->addOrderBy($sortedBy, $sortedOrder)
				    ->addgroupBy($this->table.'.tryout_id')
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
	
	public function saveRecord($data, $team_id){

	    /* Check Duplicate or Form Submit Call */

        $is_form_submit=0;
        if(!empty($data['_token'])){
            $is_form_submit=1;
        }

		$rules=array();	
		$rules=[
				'submitted_by_id'   => 'required',
				'team_id'   => 'required',
				'tryout_name'       => 'required',
				'url_key'           => 'required|unique:'.$this->table,  
				'contact_name'      => 'required',
				'address_1'         => 'required',
				'city_id'           => 'required',
				'state_id'          => 'required',
				'zip'               => 'required',
				'phone_number'      => 'required',
				'email'             => 'required|email',
				'age_group_id'      => 'required',
				'longitude'         => 'required',
				'latitude'          => 'required',  
		];

        if($is_form_submit == 1){
            $rules['attachment_name_1'] = 'mimes:application/msword,pdf,application/pdf,text/plain';
            $rules['attachment_name_2'] = 'mimes:application/msword,pdf,application/pdf,text/plain';
        }

		if(!empty($data['age_group_id'])){
            $age_group_id_array = $data['age_group_id'];
			$data['age_group_id'] = $this->getArrayToCSV($data['age_group_id']);
		}else{
            $age_group_id_array = array();
			$data['age_group_id'] ='';
		}

		$data['url_key'] = str_slug($data['url_key']);
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',tryout_id';
		}

        $validationResult=$this->validateData($rules,$data);
        if($is_form_submit == 1) {
            $dateList = array();
            if (!empty($data['dates'])) {
                $dateArray = explode(',', $data['dates']);
                foreach ($dateArray as $date) {

                    /* Check date format Validate */
                    $validate = $this->validateDate($date);
                    if ($validate) {

                        $date = date_create($date);
                        $dateList[] = date_format($date, "Y-m-d");

                    } else {

                        $validationResult['success'] = false;
                        if (!isset($validationResult['message']) || !is_object($validationResult['message'])) {
                            $validationResult['message'] = new \stdClass();
                        }
                        $validationResult['message']->dates = array('Dates fields is invalid.');
                    }
                }

                /* Check date is duplicate */
                $isDuplicateDate = $this->tryoutDateObj->checkDateDuplicate($dateList);
                if ($isDuplicateDate) {

                    $validationResult['success'] = false;
                    if (!isset($validationResult['message']) || !is_object($validationResult['message'])) {
                        $validationResult['message'] = new \stdClass();
                    }
                    $validationResult['message']->dates = array('Dates fields is invalid.');
                }
            }
        }
		$result=array();
		$result['id']='';
		if($validationResult['success']==false){
			$result['success']=false;
			$result['message']=$validationResult['message'];
			$result['id']=$data['id'];

			return $result;
		}
        if($is_form_submit == 1) {
            $imageUploadPath = $this->_helper->getImageUploadPath();
            if (!empty($data['attachment_name_1'])) {
                $attachment_1 = $data['attachment_name_1'];
                $attachment_1_name = $attachment_1->getClientOriginalName();

                $destinationPath = public_path($imageUploadPath);
                $attachment_1->move($destinationPath, $attachment_1_name);

                $data['attachment_name_1'] = $attachment_1_name;
                $data['attachment_path_1'] = $imageUploadPath . '/' . $attachment_1_name;
            }

            if (!empty($data['attachment_name_2'])) {
                $attachment_2 = $data['attachment_name_2'];
                $attachment_2_name = $attachment_2->getClientOriginalName();

                $destinationPath = public_path($imageUploadPath);
                $attachment_2->move($destinationPath, $attachment_2_name);

                $data['attachment_name_2'] = $attachment_2_name;
                $data['attachment_path_2'] = $imageUploadPath . '/' . $attachment_2_name;
            }
        }

		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$tryout = self::findOrFail($data['id']);
		    $tryout ->update($data);
		    $this->afterSave($data,$tryout);
			$result['id']=$tryout->tryout_id;	
		}else{
		 	$tryout  = self::create($data);
		 	$result['id']=$tryout->tryout_id;
			$this->afterSave($data,$tryout);
		}

		if($is_form_submit==1 || $is_form_submit=='1'){
            $this->ageGroupPositionObj->ageGroupPositionCreateOrUpdate($data, $age_group_id_array, $result['id']);
            $this->tryoutDateObj->CreateOrUpdate($result['id'], $dateList);
        }

		$result['success']=true;
		$result['message']="Tryout Saved Successfully.";
		return $result;
	}

	
	public function display($tryout_id)
    {
	    $return =$this->load($tryout_id);

	   if(!empty($return->age_group_id)){		  
	   	
			$return->age_group_id = $this->getCSVToArray($return->age_group_id);
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
	public function getAgeGroupCsv($tryout_id =0){
		
		$ageGroupTable = $this->getTable();
		$selectedColumns = [$ageGroupTable.'.age_group_id'];
	    return  $this->setSelect()
		   			  ->addTryoutIdFilter($tryout_id)	
		   			  ->get($selectedColumns)
		   			  ->first();
  	}

    public function addIsActiveFilter($is_active=1)
	{	
		$this->queryBuilder->where($this->table.'.is_active',$is_active);
		return $this;
	}

	public function addTypeFilter($type=1)
	{
		$this->queryBuilder->where('type',$type);
		return $this;
	}

    
	public function addgroupBy($groupByName){
		
		$this->queryBuilder->groupBy($groupByName);	
		return $this;
	}

	public function addOrderBy($sortedBy, $sortedOrder){

		if(!empty($sortedBy) && !empty($sortedOrder)){
			$this->queryBuilder->orderBy($sortedBy, $sortedOrder);
		}
		return $this;
	}
	
	public function convertDataToHtml($tournamentOrganizations){
		
		$htmlContent="";
		if(count($tournamentOrganizations) > 0){
			foreach ($tournamentOrganizations as $key => $data) {

				$htmlContent .="<tr>
							        <td data-title='".trans('front.tryout_grid.fields.tryout')."'><a href='".$data->getUrl()."'>".$data->tryout_name."</a></td>
							        <td data-title='".trans('front.tryout_grid.fields.team')."'>".$data->team->name."</td>
							        <td data-title='".trans('front.tryout_grid.fields.dates')."'>".$data->dateList."</td>
							        <td data-title='".trans('front.tryout_grid.fields.age_groups')."'>".$data->age_group."</td>
							    </tr>";
			}
		}else{
			$htmlContent .="<tr>
								<td colspan='100'>".trans("quickadmin.qa_no_entries_in_table")."</td>
							</tr>";
		}
		return $htmlContent;
	}

	public function getTryoutNameById($tryout_id){

	    $return = $this->setSelect()
		   			  ->addTryoutIdFilter($tryout_id)
		   			  ->get(['tryout_name','team_id'])
		   			  ->first();	
		
		return !empty($return) ? $return : ''; 
  	}
  	
  	/* Page Builder */
	public function addStateIdFilter($state_id=0){	
		if($state_id > 0){
			$this->queryBuilder->where('state_id',$state_id);
		}
		return $this;
	}
	public function addCityIdFilter($city_id=0){	
		
		if($city_id >0){
			$this->queryBuilder->where('city_id',$city_id);
		}
		return $this;
	}

	public function addUrlKeyFilter($url_key){

        if(!empty(trim($url_key))) {
            $this->queryBuilder->where('url_key', $url_key);
        }
		return $this;
	}

	public function getDetailPageByUrlKey($url_key){

	    $tryout = $this->setSelect()
	   			     ->addUrlKeyFilter($url_key)	
				     ->get()
				     ->first();
		
		if(!empty($tryout)){
		
			if(!empty($tryout->age_group_id)){
				$age_group_id_array = $this->getCSVToArray($tryout->age_group_id);
				$age_group_position_list = array();
				$age_group_result = $this->ageGroupObj->getAgeGroupByArrayId($age_group_id_array);
				if(!empty($age_group_result)){
					foreach ($age_group_result as $age_group_id  => $data) {	
						$age_group_position_list[$age_group_id] = $this->ageGroupPositionObj->getAgeGroupPosition($tryout->tryout_id, $age_group_id);
					}

				}
			}
			$tryout['age_group_result'] = $age_group_result;
			$tryout['age_group_position_list'] = $age_group_position_list;		

			/* Age group */
			$age_group_list = array();
			$age_group_id_array = $this->getCSVToArray($tryout->age_group_id);
			if(!empty($age_group_id_array)){
				foreach ($age_group_id_array as $row_age_group_id) {
					$age_group_result = $this->ageGroupObj->getAllAgeGropuForCampOrClinic($row_age_group_id);
					$age_group_list[] = $age_group_result->name;
				}
			}

			$tryout['age_group'] = $this->getColumnList($age_group_list, ' - ');		
		}
		return $tryout;     
   	}
   	public function getUrl(){ 
   		return \URL::to('tryouts') .'/'.$this->url_key;
   	}

   	public function addTryoutName($tryout_name){

   		if(!empty($tryout_name)){
			$this->queryBuilder->where($this->table.'.tryout_name', 'like', '%'.$tryout_name.'%');
		}
		return $this;
	}

	public function HeaderSearch( $search, $city_id, $state_id, $latitude, $longitude, $redius ){
		$per_page = $this->_helper->getConfigRecordForTopsearch();
		$selectColoumn = ['tryout_id','tryout_name','url_key'];		
		$listArray = array();
		$list = $this->list($search, $page=0, $team_id=0, $state_id, $city_id, $redius, $isFront=1 , $latitude, $longitude, $age_group_id=array(), $position_id=array(), $start_date='',$end_date='', $tryout_name='', $sortedBy='tryout_name', $sortedOrder='ASC', $per_page, $selectColoumn);
			
	    if(!empty($list)) {
    		foreach ($list as $key => $value) {
    			$data['title'] = $value->tryout_name;
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

    public function getTryoutWidget($submitted_by_id =0){

        return $this->setSelect()
            ->addSubmittedByIdFilter($submitted_by_id)
            ->get()
            ->count();
    }

    public function loadByTryoutIdForEnquiry($tryout_id){

        $selectColoumn = [$this->table.'.contact_name',
                          $this->table.'.email'];

        $return = $this->setSelect()
            ->addTryoutIdFilter($tryout_id)
            ->get($selectColoumn)
            ->first();

        return $return;
    }

    public function exportCSV($entity, $search, $page){

        $selectedColumns = ['tryout_id',
                            'team_id',
                            'submitted_by_id',
                            'tryout_name',
                            'contact_name',
                            'address_1',
                            'address_2',
                            'location',
                            'state_id',
                            'city_id',
                            'zip',
                            'phone_number',
                            'email',
                            'longitude',
                            'latitude',
                            'attachment_name_1',
                            'attachment_path_1',
                            'attachment_name_2',
                            'attachment_path_2',
                            'age_group_id',
                            'information',
                            'dates'];

        $csvHeaderLable = [ 'tryout_id' => 'Tryout Id',
                            'team_id' => 'Team',
                            'submitted_by_id' => 'Member',
                            'tryout_name' => 'Name',
                            'contact_name' => 'Contact Name',
                            'address_1' => 'Address 1',
                            'address_2' => 'Address 2',
                            'location' => 'Location',
                            'state_id' => 'State',
                            'city_id' => 'City',
                            'zip' => 'Zip',
                            'phone_number' => 'Phone Number',
                            'email' => 'Email',
                            'longitude' => 'Longitude',
                            'latitude' => 'Latitude',
                            'attachment_name_1' => 'Attachment Name 1',
                            'attachment_path_1' => 'Attachment Path 1',
                            'attachment_name_2' => 'Attachment Name 2',
                            'attachment_path_2' => 'Attachment Path 2',
                            'age_group_id' => 'Age Groups',
                            'information' => 'Information',
                            'dates' => 'Dates'];

        $results = $this->list($search,$page, $team_id=0, $state_id=0, $city_id=0, $redius=0, $isFront=0 , $latitude='', $longitude='', $age_group_id=array(), $position_id=array(), $start_date='',$end_date='', $tryout_name='', $sortedBy='', $sortedOrder='', $per_page=0, $selectColoumn=array('*'), $submitted_by_id=0);
        $csvExportPath = $this->_helper->getCsvExportFolderPath();

        /* Data Format */
        if(!empty($results)){
            foreach ($results as $key => $value) {

                if($value->submitted_by_id > 0){
                    $value->submitted_by_id = $this->memberObj->getMemberEmailByMemberId($value->submitted_by_id);
                }else{
                    $value->submitted_by_id = "";
                }

                $value->team_id = $value->team->name;
                $value->state_id = $value->state->name;
                $value->city_id = $value->city->city;

                /* Age Group with Position */
                if(!empty($value->age_group_id)){
                    $ageGroupArray = array();
                    $age_group_array =  explode(',', $value->age_group_id);
                    foreach ($age_group_array  as $key=> $value_age_group_Id ) {
                        $ageGroupName = $this->ageGroupObj->getAgeGroupNameById($value_age_group_Id);
                        $positions = $this->ageGroupPositionObj->getAgeGroupPositionNameByTryoutIdAndAgeGroupId($value->tryout_id, $value_age_group_Id);

                        if(!empty($positions)) {
                            $positions = implode($positions, ':');
                            $ageGroupName = $ageGroupName.'-'.$positions;

                        }
                        $ageGroupArray[] = $ageGroupName;
                    }
                    $value->age_group_id = implode($ageGroupArray,'|');
                }
            }
            $results = $results->toArray();
            foreach ($results as $key => $value) {

                $results[$key]['dates']= $this->tryoutDateObj->getDateList($value['tryout_id']);
            }
            $results = json_decode(json_encode($results), false);
            $results = ( object ) $results;

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
                    'Tryout Id'=>array('db_column'=>'tryout_id'),
                    'Team'=>array('db_column'=>'team_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkTeamReference'),
                    'Member'=>array('db_column'=>'submitted_by_id','reference_key_with_zero_allow'=>true,'reference_function'=>'checkMemberReference'),
                    'Name'=>array('db_column'=>'tryout_name','required'=>true),
                    'Contact Name'=>array('db_column'=>'contact_name','required'=>true),
                    'Address 1'=>array('db_column'=>'address_1','required'=>true),
                    'Address 2'=>array('db_column'=>'address_2'),
                    'Location'=>array('db_column'=>'location'),
                    'State'=>array('db_column'=>'state_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkStateReference'),
                    'City'=>array('db_column'=>'city_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkCityReference'),
                    'Zip'=>array('db_column'=>'zip','required'=>true),
                    'Phone Number'=>array('db_column'=>'phone_number','required'=>true),
                    'Email'=>array('db_column'=>'email','required'=>true),
                    'Longitude'=>array('db_column'=>'longitude','required'=>true),
                    'Latitude'=>array('db_column'=>'latitude','required'=>true),
                    'Attachment Name 1'=>array('db_column'=>'attachment_name_1'),
                    'Attachment Path 1'=>array('db_column'=>'attachment_path_1'),
                    'Attachment Name 2'=>array('db_column'=>'attachment_name_2'),
                    'Attachment Path 2'=>array('db_column'=>'attachment_path_2'),
                    'Age Groups'=>array('db_column'=>'age_group_id','required'=>true,'custom_function'=>'checkAgeGroupReference'),
                    'Information'=>array('db_column'=>'information'),
                    'Dates'=>array('db_column'=>'dates','custom_function'=>'checkDateReference'),
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

                            /* For City */
                            if($reference_function == "checkCityReference"){
                                $isExistReferenceID = $this->{$reference_function}($csvValue, $tableEntryRow['state_id']);

                            }else{
                                $isExistReferenceID = $this->{$reference_function}($csvValue);
                            }

                            if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                $resultRow['result_message'] = 'Reference '.$headerKey.' is not exist';
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
                                    $resultRow['result_message'] = 'Reference '.$headerKey.' is not exist';
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

                            if($custom_function == "checkAgeGroupReference") {

                                if (!empty($csvValue)) {
                                    $ageGroups = explode('|', $csvValue);
                                    $ageGroupPositionArray = array();
                                    foreach ($ageGroups as $ageGroupCsv){
                                        $ageGroupArray = explode('-', $ageGroupCsv);
                                        $ageGroup[] = $ageGroupArray[0];
                                        $ageGroupPositionArray[] = !empty($ageGroupArray[1]) ? explode(':', $ageGroupArray[1]) : '';
                                    }
                                    $ageGroupId = array();
                                    foreach ($ageGroup as $key => $ageGroupValue) {
                                        $isExistReferenceID = $this->{$custom_function}($ageGroupValue);
                                        $ageGroupId[] = $isExistReferenceID;

                                        if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                            $resultRow['result_message'] = 'Reference '.$headerKey.' in '.$ageGroupValue.' is not exist';
                                            $validRow = false;
                                            continue;
                                        }
                                        
                                        if(!empty($ageGroupPositionArray[$key])){
                                            foreach ($ageGroupPositionArray[$key] as $ageGroupPositionValue){
                                                $isExistReferenceID = $this->checkPositionReference($ageGroupPositionValue);

                                                if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                                    $resultRow['result_message'] = 'Reference ' .$headerKey.' in '. $ageGroupValue .' => '.$ageGroupPositionValue. ' is not exist';
                                                    $validRow = false;
                                                    continue;
                                                }
                                            }
                                        }
                                    }
                                    $csvValue = implode(',', $ageGroupId);
                                }
                            }

                            if($custom_function == "checkDateReference") {

                                if(!empty($resultRow['Dates'])){
                                    $dateArray = explode(',', $csvValue);
                                    foreach ($dateArray as $date) {

                                        /* Check date format Validate */
                                        $validate = $this->validateDate($date);
                                        if ($validate) {
                                            $date = date_create($date);
                                            $dateList[] = date_format($date, "Y-m-d");
                                        } else {
                                            $resultRow['result_message'] = 'Invalid Dates format';
                                            $validRow = false;
                                            continue;
                                        }
                                    }

                                    /* Check date is duplicate */
                                    $isDuplicateDate = $this->tryoutDateObj->checkDateDuplicate($dateList);
                                    if ($isDuplicateDate) {

                                        $resultRow['result_message'] = 'Duplicate Dates';
                                        $validRow = false;
                                        continue;
                                    }

                                    $resultRow['DatesArray'] = $dateList;
                                }
                            }
                        }

                        $tableEntryRow[$headerValue['db_column']] = $csvValue;
                    }

                    if($validRow){

                        if(!empty($tableEntryRow[$this->primaryKey]) && $tableEntryRow[$this->primaryKey] > 0){
                            $id = $tableEntryRow[$this->primaryKey];
                            $results = self::findOrFail($id);
                            $results ->update($tableEntryRow);

                        }else{

                            $tableEntryRow['url_key'] = $this->generateUrlKey([$resultRow['Name'], $resultRow['City'], $resultRow['State']]);
                            $tableEntryRow['url_key'] = $this->checkUrlKeyDuplicate($tableEntryRow['url_key']);
                            $results = $this::create($tableEntryRow);
                        }
                        $tryout_id = $results->{$this->primaryKey};
                        
                        /* Age Group Position */
                        if(!empty($resultRow['Age Groups'])){
                            
                            $ageGroupWithPosition = explode('|', $resultRow['Age Groups']);
                            foreach ($ageGroupWithPosition as $key => $agePositionValue) {

                                $positionArray = explode('-', $agePositionValue);
                                $ageGroupName = $positionArray[0];
                                $ageGroupPosition = (!empty($positionArray[1])) ? explode(',', $positionArray[1]) : array();

                                $ageGroupId = $this->checkAgeGroupReference($ageGroupName);
                                $age_group_position_id_array = array();
                                if(!empty($ageGroupPosition)){
                                    foreach ($ageGroupPosition as $positionName){
                                        $positionId = $this->checkPositionReference($positionName);
                                        $age_group_position_id_array[] = $positionId;
                                        if(!empty($positionId) && $positionId > 0){
                                            AgeGroupPosition::updateOrCreate(array('tryout_id' => $tryout_id,
                                                                    'age_group_id' => $ageGroupId,
                                                                    'position_id' => $positionId));
                                        }
                                    }
                                }
                                /* Delete Record Position Wise */
                                AgeGroupPosition::where('tryout_id', $tryout_id)
                                                ->where('age_group_id', $ageGroupId)
                                                ->whereNotIn('position_id', $age_group_position_id_array)
                                                ->delete();
                            }
                        }

                        /* Dates */
                        if(isset($resultRow['Dates'])){
                            $dateList = isset($resultRow['DatesArray']) ? $resultRow['DatesArray'] : array();
                            $this->tryoutDateObj->CreateOrUpdate($tryout_id, $dateList);
                            if(isset($resultRow['DatesArray'])){
                                unset($resultRow['DatesArray']);
                            }
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
    /* check State model reference key */
    public function checkStateReference($stateName){
        return $this->stateObj->recordCount($stateName);
    }
    /* check Team model reference key */
    public function checkTeamReference($name){
        return $this->teamObj->recordCount($name);
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
    /* check PositionReference model reference key */
    public function checkPositionReference($name){
        $module_id = $this->_ageGroupPositionHelper->getModuleId();
        return $this->positionObj->getPositionIdByName($name, $module_id);
    }
}