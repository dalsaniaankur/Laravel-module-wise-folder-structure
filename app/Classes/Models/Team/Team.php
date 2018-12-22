<?php
namespace App\Classes\Models\Team;

use Auth;
use DB;
use App\Classes\Models\BaseModel;
use App\Classes\Models\Images\Images;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Helpers\Team\Helper;
use App\Classes\Models\AgeGroup\AgeGroup;
use App\Classes\Models\Members\Members;
use Illuminate\Database\Eloquent\Model;
use Image;


class Team extends BaseModel
{
	protected $table = 'sbc_teams';
    protected $primaryKey = 'team_id';
  	protected $entity='team';
	protected $searchableColumns=['name','address_1','address_2'];
	protected $fillable = ['submitted_by_id',
							'name',
							'url_key',
							'contact_name',
							'address_1',
							'address_2',
							'city_id',
							'state_id',
							'zip',
							'phone_number',
							'email',
							'agree_to_recevie_email_updates',
							'website_url',
							'blog_url',
							'age_group_id',
							'about',
							'achievements',
							'general_information',
							'notable_alumni',
							'facebook_url',
							'twitter_url',
							'youtube_video_id_1',
							'youtube_video_id_2',
							'is_show_advertise',
							'longitude',
							'latitude',
							'image_id',
							'is_active',
							'is_send_email_to_user',
                            'approval_status'];

    protected $memberObj;
    protected $stateObj;
    protected $cityObj;
    protected $ImagesObj;
    protected $ageGroupObj;
    protected $_helper;


    /*Copy from parent*/
	public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
		$this->_helper = new Helper();
		$this->ageGroupObj = new AgeGroup();
        $this->memberObj = new Members();
        $this->stateObj = new State();
        $this->cityObj = new City();
        $this->ImagesObj = new Images();

    }

    public function state()
	{
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function city()
	{
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }
	
	public function Images()
    {
        return $this->belongsTo(Images::class, 'image_id', 'image_id');
    }
	
	public function addTeamIdFilter($team_id=0)
	{
		$this->queryBuilder->where('team_id',$team_id);
		return $this;
	}
    public function addNameFilter($name){

	    $this->queryBuilder->where('name','=',$name);
        return $this;
    }
	/*
	**	Logic Methods
	*/
	public function load($team_id)
    {
    	$this->beforeLoad($team_id);

	    $return =$this->setSelect()
	   			  ->addTeamIdFilter($team_id)
				  ->get()
				  ->first();
			
		$this->afterLoad($team_id, $return);
		
		return $return;
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

   	public function addgroupBy($groupByName){
		
		$this->queryBuilder->groupBy($groupByName);	
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
    public function addApprovalStatusFilter($approvalStatus = ''){

        if(!empty(trim($approvalStatus))){
            $this->queryBuilder->where($this->table.'.approval_status',$approvalStatus);
        }
        return $this;
    }
	public function list($search='',$page=0, $state_id=0, $is_active = 2, $city_id=0, $redius=0, $isFront=0, $latitude='', $longitude='', $name='', $age_group_id=array(), $sortedBy='', $sortedOrder='' , $per_page=0, $selectColoumn=array('*'), $submitted_by_id = 0, $approvalStatus='' ){

  		$per_page = $per_page == 0 ? $this->_helper->getConfigPerPageRecord() : $per_page;
  		$list=$this->setSelect()
  				   ->addSearch($search)
  				   ->addStateIdFilter($state_id)
                   ->addSubmittedByIdFilter($submitted_by_id)
                   ->addApprovalStatusFilter($approvalStatus)
  				   ->addIsActiveFilter($is_active)
  				   ->addCityIdFilter($city_id)
				   ->addMileRadiusFilter($redius, $latitude, $longitude)  				     				   
  				   ->addNameLikeFilter($name)
  				   ->addAgeGroupIdFilter($age_group_id)
				   ->addOrderBy($sortedBy, $sortedOrder)
				   ->addPaging($page,$per_page)
				   ->addgroupBy($this->table.'.team_id')
				   ->get();

		if($isFront == 1){   
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
					//$row['age_group'] = $this->getColumnList($age_group_list, ', ');
					$row['age_group'] = $age_group_list;
				}
			}		 		   
		}	
		return $list;
   	}
	
	public function listTotalCount($search='',$state_id=0, $is_active = 2, $city_id=0, $redius=0, $isFront=0,  $latitude='', $longitude='', $name='', $age_group_id=array(), $sortedBy='', $sortedOrder='', $submitted_by_id = 0, $approvalStatus='' )
	{
		$this->reset();
		$count=$this->setSelect()
				  ->addSearch($search)
				  ->addStateIdFilter($state_id)
				  ->addIsActiveFilter($is_active)
                  ->addApprovalStatusFilter($approvalStatus)
                  ->addSubmittedByIdFilter($submitted_by_id)
				  ->addCityIdFilter($city_id)
				  ->addMileRadiusFilter($redius, $latitude, $longitude)  				     				   
				  ->addNameLikeFilter($name)
  				  ->addAgeGroupIdFilter($age_group_id)
				  ->addOrderBy($sortedBy, $sortedOrder)
				  ->addgroupBy($this->table.'.team_id')
				  ->get()
				  ->count();
		
		return $count;
	}
	
	public function preparePagination($total,$basePath)
	{
		$perpage=$this->_helper->getConfigPerPageRecord();
		$pageHelper=new \App\Classes\PageHelper($perpage,'page');
		$pageHelper->set_total($total); 
		$pageHelper->page_links($basePath);
		return $pageHelper->page_links($basePath);
	}
	
	public function saveRecord($data)
	{
        /* Check Duplicate or Form Submit Call */
        $is_form_submit=0;
        if(!empty($data['_token'])){
            $is_form_submit=1;
        }

		$rules=array();	
		$rules=[
				'submitted_by_id' => 'required',
				'name'            => 'required',
				'url_key'         => 'required|unique:'.$this->table,  
				'contact_name'    => 'required',
				'address_1'       => 'required',
				'city_id'         => 'required',
				'state_id'        => 'required',
				'zip'             => 'required',
				'phone_number'    => 'required',
				'email'           => 'required',
				'age_group_id'    => 'required',
				'longitude'       => 'required',  
				'latitude'        => 'required',  
		];

		$data['url_key'] = str_slug($data['url_key']);
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',team_id';
		}

        if($is_form_submit == 1){
            $rules['teams_image'] = 'mimes:jpeg,jpg,png,gif';
            $rules['teams_image'] = 'mimes:jpeg,jpg,png,gif|dimensions:min_width=250';
        }

		$validationResult=$this->validateData($rules,$data);
		$result=array();
		$result['id']='';

		if($validationResult['success']==false)
		{
			$result['success']=false;
			$result['message']=$validationResult['message'];
			if(!empty($data['id'])) {
                $result['id'] = $data['id'];
            }
			return $result;
		}
        if($is_form_submit == 1) {
            if (!empty($data['teams_image'])) {
                $image = $data['teams_image'];
                $teams_image_name = $data['teams_image']->getClientOriginalName();
                $teams_image_name = str_replace('.' . $data['teams_image']->getClientOriginalExtension() . '', '_' . time() . '.' . $data['teams_image']->getClientOriginalExtension(), $teams_image_name);
                $destinationPath = public_path('/images/module_images');

                $aspectImage = Image::make($image)->resize(250, null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->save($destinationPath.'/'.$teams_image_name);

                $image_data['image_name'] = $teams_image_name;
                $image_data['image_path'] = 'images/module_images/' . $teams_image_name;
                $image_data['module_id'] = $this->_helper->getModuleId();

                $image_for_module_wise = \App\Classes\Models\Images\Images::insert($image_data);
                $inserted_image_id = DB::getPdo()->lastInsertId();

                if(!empty($inserted_image_id)){
                    $data['image_id'] = $inserted_image_id;
                }
            }
        }
		if(!empty($data['age_group_id'])){
			$data['age_group_id'] = $this->getArrayToCSV($data['age_group_id']);
		}else{
			$data['age_group_id'] ='';
		}

		if(!empty($data['is_active']) && $data['is_active'] ='on'){
			$data['is_active'] = 1;
		}else{
			$data['is_active'] = 0;
		}

		if(!empty($data['agree_to_recevie_email_updates']) && $data['agree_to_recevie_email_updates'] ='on'){
			$data['agree_to_recevie_email_updates'] = 1;
		}else{
			$data['agree_to_recevie_email_updates'] = 0;
		}

		if(!empty($data['is_send_email_to_user']) && $data['is_send_email_to_user'] ='on'){
			$data['is_send_email_to_user'] = 1;
		}else{
			$data['is_send_email_to_user'] = 0;
		}

		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$team = self::findOrFail($data['id']);
		    $team->update($data);
			$this->afterSave($data,$team);
			$result['id']=$team->team_id;
		}else{
		 	 $team = self::create($data);
			 $result['id']=$team->team_id;
			 $this->afterSave($data,$team);
		}
		$result['success']=true;
		$result['message']="Team Saved Successfully.";
		return $result;
	}
	
	public function display($team_id)
    {
	    $return = $this->load($team_id);
	    if(!empty($return->age_group_id)){
		    $return->age_group_id = $this->getCSVToArray($return->age_group_id);
	    }
		return $return;
   	}
	
	public function removed($id)
	{
		$this->beforeRemoved($id);
		$team=$this->load($id);
		if(!empty($team))
		{
			 $delete = $team->delete();
			 $this->afterRemoved($id);
			 return $delete;
		}
		return false;
	}
	
	public function getAllTeamDropdown()
    {
	   $return =$this->setSelect()
	  		 	  ->orderBy('name', 'asc')
                   ->get()
				  ->pluck('name', 'team_id')
				  ->prepend(trans('front.qa_all'), '');
		return $return;
   	}
   	public function getTeamWidget($submitted_by_id =0){

    	return $this->setSelect()
                      ->addSubmittedByIdFilter($submitted_by_id)
    			      ->get()
    			      ->count();
    }
    
    public function addIsActiveFilter($is_active = 2){
        
		if($is_active != 2){
			$this->queryBuilder->where('is_active',$is_active);
		}
		return $this;
	}
	
    public function addIsSendEmailToUserFilter($is_send_email_to_user=1){
    	
		$this->queryBuilder->where('is_send_email_to_user',$is_send_email_to_user);
		return $this;
	}

    public function getTeamsListForSendMail()
	{
	    return $this->setSelect()
	    			->addIsActiveFilter(1)
	    			->addIsSendEmailToUserFilter()
	   			    ->get();
		
	}

	public function addOrderBy($sortedBy='', $sortedOrder=''){
		
		if(!empty($sortedBy) && !empty($sortedOrder)){
			$this->queryBuilder->orderBy($sortedBy, $sortedOrder);
		}
		return $this;
	}
	
	public function convertDataToHtml($team){

		$htmlContent="";
		if(count($team) > 0){
			foreach ($team as $key => $data) {

                $htmlContent .="<div class='col-xs-6 col-sm-6 col-md-4'>
                                    <div class='card'>
                                        <div class='card-img-top'>";

                if(!empty($data->Images->image_path)) {

                    $htmlContent .="<img src='".\URL::to('/').'/'.$data->Images->image_path."' alt = '".$data->Images->image_name."'>";
                }
                $htmlContent .="</div>
                                    <div class='card-body'>
                                        <div class='card-text'>
                                            <b class='card-title'>".$data->name."</b>
                                            <p>".$data->address_1.', </br>'.$data->city->city.', '.$data->state->name.', '.$data->zip."</p>";


                if(!empty($data->age_group)) {
                    $htmlContent .="<div class='group-content'><b>Age Groups:</b>";
                        foreach ($data->age_group as $name){
                            $htmlContent.="\n <span class='badge badge-info'>".$name."</span>";
                        }
                    $htmlContent .="</div>";
                }

                $htmlContent .="</div>
                            </div>
                            <a class='team-listing-a-tag' href='".$data->getUrl()."' title='".$data->name."'></a>
                        </div>
                    </div>";
			}

		}else{
            $htmlContent ="<div class='col-xs-12 col-sm-12 col-md-12 text-center'>
                         <p>".trans("quickadmin.qa_no_entries_in_table")."</p>
                       </div>";
		}

		return $htmlContent;
	}

	public function getTeamNameById($team_id){
	    $return =  $this->setSelect()
	    			->addTeamIdFilter($team_id)
	   				->get()
	  		 	  	->pluck('name');

	  	return (!empty($return[0])) ? $return[0] : '';	 	  	
   	}

   	/* Page Builder */
	public function addStateIdFilter($state_id=0)
	{	
		if($state_id > 0){
			$this->queryBuilder->where('state_id',$state_id);
		}
		return $this;
	}  	
	public function addCityIdFilter($city_id=0){	

		if($city_id > 0){
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

	    $team = $this->setSelect()
	   			     ->addUrlKeyFilter($url_key)	
				     ->get()
				     ->first();
		
		if(!empty($team)){
			/* age group */
			$age_group_list = array();
			$age_group_id_array = $this->getCSVToArray($team->age_group_id);
			if(!empty($age_group_id_array)){
				foreach ($age_group_id_array as $row_age_group_id) {
					$age_group_result = $this->ageGroupObj->getAllAgeGropuForCampOrClinic($row_age_group_id);
					$age_group_list[] = $age_group_result->name;
				}
			}
			$team['age_group'] = $this->getColumnList($age_group_list, ' - ');
		}	
		return $team;
   	}

	public function getUrl(){ 
		return \URL::to('teams') .'/'.$this->url_key;
	}

	public function getTeamDropdown()
    {
	   $return =$this->setSelect()->where('is_active',1)
	  		 	  ->orderBy('name', 'asc')
                  ->get()
				  ->pluck('name', 'team_id');
				  
		return $return;
   	}

   	public function addNameLikeFilter($name =''){
   		
   		if(!empty($name)){
			$this->queryBuilder->where($this->table.'.name', 'like', '%'.$name.'%');
		}
		return $this;
	}

	public function HeaderSearch( $search, $city_id, $state_id, $latitude, $longitude, $redius ){
		$per_page = $this->_helper->getConfigRecordForTopsearch();
		$selectColoumn = ['team_id','name','url_key'];

		$listArray = array();
		$list = $this->list($search,$page=0, $state_id=0, $is_active = 1, $city_id=0, $redius, $isFront=1, $latitude, $longitude, $name='', $age_group_id=array(), $sortedBy='name', $sortedOrder='ASC' , $per_page, $selectColoumn);
	    	
	    if(!empty($list)) {
    		foreach ($list as $key => $value) {
    			$data['title'] = $value->name;
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

    public function loadByTeamIdForEnquiry($team_id){

	    $selectColoumn = [$this->table.'.contact_name',
                          $this->table.'.email'];

        $return = $this->setSelect()
            ->addTeamIdFilter($team_id)
            ->get($selectColoumn)
            ->first();

        return $return;
    }

    public function exportCSV($entity, $search, $page){

        $selectedColumns = ['team_id',
                            'submitted_by_id',
                            'name',
                            'contact_name',
                            'address_1',
                            'address_2',
                            'state_id',
                            'city_id',
                            'zip',
                            'phone_number',
                            'email',
                            'agree_to_recevie_email_updates',
                            'website_url',
                            'blog_url',
                            'age_group_id',
                            'about',
                            'achievements',
                            'general_information',
                            'notable_alumni',
                            'facebook_url',
                            'twitter_url',
                            'youtube_video_id_1',
                            'youtube_video_id_2',
                            'is_show_advertise',
                            'longitude',
                            'latitude',
                            'image_id',
                            'is_active',
                            'is_send_email_to_user',
                            'approval_status'];

        $csvHeaderLable = ['team_id' => 'Team Id',
            'submitted_by_id' => 'Member',
            'name' => 'Name',
            'contact_name' => 'Contact Name',
            'address_1' => 'Address 1',
            'address_2' => 'Address 2',
            'state_id' => 'State',
            'city_id' => 'City',
            'zip' => 'Zip',
            'phone_number' => 'Phone Number',
            'email' => 'Email',
            'agree_to_recevie_email_updates' => 'Agree To Recevie Email Updates',
            'website_url' => 'Website Url',
            'blog_url' => 'Blog Url',
            'age_group_id' => 'Age Groups',
            'about' => 'About',
            'achievements' => 'Achievements',
            'general_information' => 'General Information',
            'notable_alumni' => 'Notable Alumni',
            'facebook_url' => 'Facebook Url',
            'twitter_url' => 'Twitter Url',
            'youtube_video_id_1' => 'Youtube Video Id 1',
            'youtube_video_id_2' => 'Youtube Video Id 2',
            'is_show_advertise' => 'Is Show Advertise',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'image_id' => 'Image Path',
            'is_active' => 'Is Active',
            'is_send_email_to_user' => 'Is Send Email To User',
            'approval_status' => 'Approval Status'];

        $results = $this->list($search,$page, $state_id=0, $is_active = 2, $city_id=0, $redius=0, $isFront=0, $latitude='', $longitude='', $name='', $age_group_id=array(), $sortedBy='', $sortedOrder='' , $per_page=0, $selectColoumn=array('*'), $member_id=0, $approvalStatus='' );
        $csvExportPath = $this->_helper->getCsvExportFolderPath();

        /* Data Format */
        if(!empty($results)){
            foreach ($results as $value) {

                if($value->submitted_by_id > 0){
                    $value->submitted_by_id = $this->memberObj->getMemberEmailByMemberId($value->submitted_by_id);
                }else{
                    $value->submitted_by_id = "";
                }

                $value->state_id = $value->state->name;
                $value->city_id = $value->city->city;

                /* Set Image Path */
                if($value->image_id > 0){
                    $value->image_id = $this->ImagesObj->getImagePathByImageId($value->image_id);
                }else{
                    $value->image_id = "";
                }

                /*Age Group */
                if(!empty($value->age_group_id)){
                    $ageGroupArray = array();
                    $age_group_array =  explode(',', $value->age_group_id);

                    foreach ($age_group_array  as $key=> $value_age_group_Id ) {
                        $ageGroupName = $this->ageGroupObj->getAgeGroupNameById($value_age_group_Id);
                        $ageGroupArray[] = $ageGroupName;
                    }
                    $value->age_group_id = implode($ageGroupArray,':');
                }

                $value->agree_to_recevie_email_updates = ($value->agree_to_recevie_email_updates == 1) ? 'Yes' : 'No';
                $value->is_show_advertise = ($value->is_show_advertise == 1) ? 'Yes' : 'No';
                $value->is_active = ($value->is_active == 1) ? 'Active' : 'Deactive';
                $value->is_send_email_to_user = ($value->is_send_email_to_user == 1) ? 'Yes' : 'No';
                $value->approval_status = ucfirst($value->approval_status);
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
                    'Team Id'=>array('db_column'=>'team_id'),
                    'Member'=>array('db_column'=>'submitted_by_id','reference_key_with_zero_allow'=>true,'reference_function'=>'checkMemberReference'),
                    'Name'=>array('db_column'=>'name','required'=>true),
                    'Contact Name'=>array('db_column'=>'contact_name','required'=>true),
                    'Address 1'=>array('db_column'=>'address_1','required'=>true),
                    'Address 2'=>array('db_column'=>'address_2'),
                    'State'=>array('db_column'=>'state_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkStateReference'),
                    'City'=>array('db_column'=>'city_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkCityReference'),
                    'Zip'=>array('db_column'=>'zip','required'=>true),
                    'Phone Number'=>array('db_column'=>'phone_number','required'=>true),
                    'Agree To Recevie Email Updates'=>array('db_column'=>'agree_to_recevie_email_updates'),
                    'Email'=>array('db_column'=>'email','required'=>true),
                    'Website Url'=>array('db_column'=>'website_url'),
                    'Blog Url'=>array('db_column'=>'blog_url'),
                    'Age Groups'=>array('db_column'=>'age_group_id','required'=>true,'custom_function'=>'checkAgeGroupReference'),
                    'About'=>array('db_column'=>'about'),
                    'Achievements'=>array('db_column'=>'achievements'),
                    'General Information'=>array('db_column'=>'general_information'),
                    'Notable Alumni'=>array('db_column'=>'notable_alumni'),
                    'Facebook Url'=>array('db_column'=>'facebook_url'),
                    'Twitter Url'=>array('db_column'=>'twitter_url'),
                    'Youtube Video Id 1'=>array('db_column'=>'youtube_video_id_1'),
                    'Youtube Video Id 2'=>array('db_column'=>'youtube_video_id_2'),
                    'Is Show Advertise'=>array('db_column'=>'is_show_advertise'),
                    'Longitude'=>array('db_column'=>'longitude','required'=>true),
                    'Latitude'=>array('db_column'=>'latitude','required'=>true),
                    'Image Path'=>array('db_column'=>'image_id','reference_key_with_zero_allow'=>true,'reference_function'=>'checkImageReference'),
                    'Is Active'=>array('db_column'=>'is_active'),
                    'Is Send Email To User'=>array('db_column'=>'is_send_email_to_user'),
                    'Approval Status'=>array('db_column'=>'approval_status','required'=>true,'custom_function'=>'checkStatusReference'),
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
                                    $ageGroups = explode(':', $csvValue);
                                    $ageGroupId = array();
                                    foreach ($ageGroups as $key => $ageGroupName) {
                                        $isExistReferenceID = $this->{$custom_function}($ageGroupName);
                                        $ageGroupId[] = $isExistReferenceID;

                                        if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                            $resultRow['result_message'] = 'Reference '.$headerKey.'in '.$ageGroupName.' is not exist';
                                            $validRow = false;
                                            continue;
                                        }
                                    }
                                    $csvValue = implode(',', $ageGroupId);
                                }
                            }

                            if($custom_function == "checkStatusReference") {

                                $isExistReferenceID = $this->{$custom_function}($csvValue);

                                if (!isset($isExistReferenceID) || empty($isExistReferenceID)) {
                                    $resultRow['result_message'] = 'Reference ' . $headerKey . ' is not exist';
                                    $validRow = false;
                                    continue;
                                }
                            }
                        }
                        $tableEntryRow[$headerValue['db_column']] = $csvValue;
                    }

                    if($validRow){

                        $tableEntryRow['agree_to_recevie_email_updates'] = ( ($tableEntryRow['agree_to_recevie_email_updates'] == 'Yes') ? 1 : 0 );
                        $tableEntryRow['is_show_advertise'] = ( ($tableEntryRow['is_show_advertise'] == 'Yes') ? 1 : 0 );
                        $tableEntryRow['is_send_email_to_user'] = ( ($tableEntryRow['is_send_email_to_user'] == 'Yes') ? 1 : 0 );
                        $tableEntryRow['is_active'] = ( ($tableEntryRow['is_active'] == 'Active') ? 1 : 0 );
                        $tableEntryRow['approval_status'] = strtolower($tableEntryRow['approval_status']);

                        if(!empty($tableEntryRow[$this->primaryKey]) && $tableEntryRow[$this->primaryKey] > 0){
                            $id = $tableEntryRow[$this->primaryKey];
                            $results = self::findOrFail($id);
                            $results ->update($tableEntryRow);

                        }else {

                            $tableEntryRow['url_key'] = $this->generateUrlKey([$resultRow['Name'], $resultRow['City'], $resultRow['State']]);
                            $tableEntryRow['url_key'] = $this->checkUrlKeyDuplicate($tableEntryRow['url_key']);
                            $results = $this::create($tableEntryRow);
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

    public function recordCount($name){
        return $this->setSelect()
            ->addNameFilter($name)
            ->get(['team_id'])
            ->pluck('team_id')
            ->first();
    }

    /* check Status reference key */
    public function checkStatusReference($statusId){
        return $this->_helper->checkStatusReference($statusId);
    }
}