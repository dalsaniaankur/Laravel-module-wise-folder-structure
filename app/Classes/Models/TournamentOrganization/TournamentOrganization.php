<?php
namespace App\Classes\Models\TournamentOrganization;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Models\BaseModel;
use App\Classes\Models\Images\Images;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Helpers\TournamentOrganization\Helper;
use App\Classes\Models\Members\Members;

class TournamentOrganization extends BaseModel{
    
	protected $table = 'sbc_tournament_organization';
    protected $primaryKey = 'tournament_organization_id';
    
  	protected $entity='tournament_organization';
	protected $searchableColumns=['name'];

	protected $_helper;
	protected $stateObj;
    protected $cityObj;
    protected $ImagesObj;
    protected $memberObj;

    protected $fillable = [ 'submitted_by_id',
							'name',
							'contact_name',
							'url_key',
							'address_1',
							'address_2',
							'city_id',
							'state_id',
							'zip',
							'phone_number',
							'agree_to_recevie_email_updates',
							'is_subscribe_newsletter',
							'website_url',
							'description',
							'facebook_url',
							'twitter_url',
							'instagram_url',
							'youtube_video_id_1',
							'youtube_video_id_2',
							'longitude',
							'latitude',
							'image_id',
							'is_active',
							'is_send_email_to_user',
                            'approval_status'
							];


	/*Copy from parent*/
	public function __construct(array $attributes = [])
    {	
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
        $this->stateObj = new \App\Classes\Models\State\State();
        $this->memberObj = new Members();
        $this->stateObj = new State();
        $this->cityObj = new City();
        $this->ImagesObj = new Images();
    }

	/**
	**	Model Relation Methods 
	*/
	
	public function state(){

        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }
	
	public function Images()
    {
        return $this->belongsTo(Images::class, 'image_id', 'image_id');
    }

    public function city(){

        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }
    public function addApprovalStatusFilter($approvalStatus = ''){

        if(!empty(trim($approvalStatus))){
            $this->queryBuilder->where($this->table.'.approval_status',$approvalStatus);
        }
        return $this;
    }
	
	/**
	**	Model Filter Methods 
	*/	
	public function addTournamentOrganizationIdFilter($tournament_organization_id=0)
	{
		$this->queryBuilder->where('tournament_organization_id',$tournament_organization_id);
		return $this;
	}

    public function addNameFilter($name){

        $this->queryBuilder->where('name',$name);
        return $this;
    }
    public function addIsActiveFilter($is_active = 2){
        if($is_active != 2) {
            $this->queryBuilder->where('is_active', $is_active);
        }
        return $this;
    }
	
	/*
	**	Logic Methods
	*/
	public function load($tournament_organization_id)
    {
    	$this->beforeLoad($tournament_organization_id);
	    
	    $return = $this->setSelect()
	   			  ->addTournamentOrganizationIdFilter($tournament_organization_id)	
				  ->get()
				  ->first();

		$this->afterLoad($tournament_organization_id, $return);		  
		
		return $return;
   	}
    public function addSubmittedByIdFilter($submitted_by_id = 0){

	    if($submitted_by_id > 0){
            $this->queryBuilder->where('submitted_by_id',$submitted_by_id);
        }
        return $this;
    }

    public function list($search='',$page=0, $submitted_by_id=0, $selectedColumns = array('*')){

		$per_page=$this->_helper->getConfigPerPageRecord();
  		$list=$this->setSelect()
  				   ->addSearch($search)
                    ->addSubmittedByIdFilter($submitted_by_id)
				   ->addPaging($page,$per_page)
				   ->get($selectedColumns);
		
		return $list;
   	}
	
	public function listTotalCount($search='',$submitted_by_id=0){
		$this->reset();
		$count=$this->setSelect()
				  ->addSearch($search)
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

		$rules=array();	
		$rules=[
				'submitted_by_id'              => 'required',
				'name'                         => 'required',
				'contact_name'                 => 'required',
				'url_key'                      => 'required|unique:'.$this->table,  
				'address_1'                    => 'required',
				'city_id'                      => 'required',
				'state_id'                     => 'required',
				'zip'                          => 'required',
				'phone_number'                 => 'required',
				'longitude'                    => 'required',
				'latitude'                     => 'required',  
				'tournamentorganization_image' => 'mimes:jpeg,jpg,png,gif',
		];

		$data['url_key'] = str_slug($data['url_key']);
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',tournament_organization_id';
		}
		
		$validationResult=$this->validateData($rules,$data);
		$result=array();
		$result['id']='';
		if($validationResult['success']==false){
			$result['success']=false;
			$result['message']=$validationResult['message'];
			if(!empty($data['id'])) {
                $result['id'] = $data['id'];
            }
            return $result;
		}
		if(!empty($data['tournamentorganization_image']))
		{
			$image = $data['tournamentorganization_image'];
			$tournament_organization_image_name = $data['tournamentorganization_image']->getClientOriginalName();
			$tournament_organization_image_name = str_replace('.'.$data['tournamentorganization_image']->getClientOriginalExtension().'','_'.time().'.'.$data['tournamentorganization_image']->getClientOriginalExtension(),$tournament_organization_image_name);
			
			$destinationPath = public_path('/images/module_images');
			$image->move($destinationPath, $tournament_organization_image_name);

			$image_data['image_name'] = $tournament_organization_image_name;
			$image_data['image_path'] = 'images/module_images/'.$tournament_organization_image_name;
			$image_data['module_id'] =$this->_helper->getModuleId();
		
			$image_for_module_wise = \App\Classes\Models\Images\Images::insert($image_data);
			$inserted_image_id = DB::getPdo()->lastInsertId();
		}
		if(!empty($inserted_image_id)){
			$data['image_id'] = $inserted_image_id;
		}

		if(!empty($data['agree_to_recevie_email_updates']) && $data['agree_to_recevie_email_updates'] ='on'){
			$data['agree_to_recevie_email_updates'] = 1;
		}else{
			$data['agree_to_recevie_email_updates'] = 0;
		}

        if(!empty($data['is_subscribe_newsletter']) && $data['is_subscribe_newsletter'] ='on'){
            $data['is_subscribe_newsletter'] = 1;
        }else{
            $data['is_subscribe_newsletter'] = 0;
        }

		if(!empty($data['is_send_email_to_user']) && $data['is_send_email_to_user'] ='on'){
			$data['is_send_email_to_user'] = 1;
		}else{
			$data['is_send_email_to_user'] = 0;
		}

		if(!empty($data['is_active']) && $data['is_active'] ='on'){
			$data['is_active'] = 1;
		}else{
			$data['is_active'] = 0;
		}
		
		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$tournament_organization = self::findOrFail($data['id']);
		    $tournament_organization ->update($data);	
		    $this->afterSave($data,$tournament_organization);
			$result['id']=$tournament_organization ->tournament_organization_id;	
		}else{
		 	$tournament_organization  = self::create($data);
			$result['id']=$tournament_organization->tournament_organization_id;
			$this->afterSave($data,$tournament_organization);
		}
		$result['success']=true;
		$result['message']="Tournament Organization Saved Successfully.";
		return $result;
	}
	
	public function display($team_id)
    {
	    $return =$this->load($team_id);
		return $return;
   	}
	
	public function removed($id)
	{
		$this->beforeRemoved($id);
		$team=$this->load($id);
		if(!empty($team)){
			 $delete = $team->delete();
			 $this->afterRemoved($id);
			 return $delete;
		}
		return false;
	}
	public function getTournamentOrganizationWidget($submitted_by_id = 0){
    	return $this->setSelect()
                      ->addSubmittedByIdFilter($submitted_by_id)
                      ->get()
    			      ->count();
    }

    public function addOrderBy($columeName, $orderBy){

		$this->queryBuilder->orderBy($columeName, $orderBy);
		return $this;
	}

   	public function getTournamentOrganizationDropdownWithAllOption(){

	    return  $this->setSelect()
		   			  ->addOrderBy('name', 'asc')
                      ->get()
                      ->pluck('name', 'tournament_organization_id')
	                  ->prepend(trans('front.qa_all'), 0);
  	}	

	public function getTournamentOrganizationDropdown(){

	    return  $this->setSelect()
		   			  ->addOrderBy('name', 'asc')
                      ->get()
	                  ->pluck('name', 'tournament_organization_id');
	                  
  	}

    public function addUrlKeyFilter($url_key){

	    if(!empty(trim($url_key))) {
            $this->queryBuilder->where('url_key', $url_key);
        }
        return $this;
    }
    public function checkDuplicateUrlKey($url_key){
        return $this->setSelect()
                    ->addUrlKeyFilter($url_key)
                    ->get()
                    ->count();
    }

    public function exportCSV($entity, $search, $page){

        $selectedColumns = ['tournament_organization_id', 'submitted_by_id', 'name', 'contact_name', 'address_1', 'address_2', 'state_id', 'city_id', 'zip', 'phone_number', 'agree_to_recevie_email_updates', 'is_subscribe_newsletter', 'website_url', 'description', 'facebook_url', 'twitter_url', 'instagram_url', 'youtube_video_id_1', 'youtube_video_id_2', 'longitude', 'latitude', 'image_id', 'is_active', 'is_send_email_to_user', 'approval_status'];
        $csvHeaderLable = ['tournament_organization_id' => 'Tournament Organization Id',
                            'submitted_by_id' => 'Member',
                            'name' => 'Name',
                            'contact_name' => 'Contact Name',
                            'address_1' => 'Address 1',
                            'address_2' => 'Address 2',
                            'state_id' => 'State',
                            'city_id' => 'City',
                            'zip' => 'Zip',
                            'phone_number' => 'Phone Number',
                            'agree_to_recevie_email_updates' => 'Agree To Recevie Email Updates',
                            'is_subscribe_newsletter' => 'Is Subscribe Newsletter',
                            'website_url' => 'Website Url',
                            'description' => 'Description',
                            'facebook_url' => 'Facebook Url',
                            'twitter_url' => 'Twitter Url',
                            'instagram_url' => 'Instagram Url',
                            'youtube_video_id_1' => 'Youtube Video Id 1',
                            'youtube_video_id_2' => 'Youtube Video Id 2',
                            'longitude' => 'Longitude',
                            'latitude' => 'Latitude',
                            'image_id' => 'Image Path',
                            'is_active' => 'Is Active',
                            'is_send_email_to_user' => 'Is Send Email To User',
                            'approval_status' => 'Approval Status'];

        $results = $this->list($search, $page, $submitted_by_id=0, $selectedColumns);
        $csvExportPath = $this->_helper->getCsvExportFolderPath();

        /* Data Format */
        if(!empty($results)){
            foreach ($results as $value) {

                if($value->submitted_by_id > 0){
                    $value->submitted_by_id = $this->memberObj->getMemberEmailByMemberId($value->submitted_by_id);
                }else{
                    $value->submitted_by_id = "";
                }

                if($value->image_id > 0){
                    $value->image_id = $this->ImagesObj->getImagePathByImageId($value->image_id);
                }else{
                    $value->image_id = "";
                }

                $value->state_id = $value->state->name;
                $value->city_id = $value->city->city;

                $value->agree_to_recevie_email_updates = ($value->agree_to_recevie_email_updates == 1) ? 'Yes' : 'No';
                $value->is_subscribe_newsletter = ($value->is_subscribe_newsletter == 1) ? 'Yes' : 'No';
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
                    'Tournament Organization Id'=>array('db_column'=>'tournament_organization_id'),
                    'Member'=>array('db_column'=>'submitted_by_id','reference_key_with_zero_allow'=>true,'reference_function'=>'checkMemberReference'),
                    'Name'=>array('db_column'=>'name','required'=>true),
                    'Contact Name'=>array('db_column'=>'contact_name','required'=>true),
                    'Address 1'=>array('db_column'=>'address_1','required'=>true),
                    'Address 2'=>array('db_column'=>'address_2'),
                    'State'=>array('db_column'=>'state_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkStateReference'),
                    'City'=>array('db_column'=>'city_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkCityReference'),
                    'Zip'=>array('db_column'=>'zip','required'=>true),
                    'Phone Number'=>array('db_column'=>'phone_number','required'=>true),
                    'Agree To Recevie Email Updates'=>array('db_column'=>'agree_to_recevie_email_updates','required_specific_value'=>true, 'required_value'=>'Yes'),
                    'Is Subscribe Newsletter'=>array('db_column'=>'is_subscribe_newsletter','required_specific_value'=>true, 'required_value'=>'Yes'),
                    'Website Url'=>array('db_column'=>'website_url'),
                    'Description'=>array('db_column'=>'description'),
                    'Facebook Url'=>array('db_column'=>'facebook_url'),
                    'Twitter Url'=>array('db_column'=>'twitter_url'),
                    'Instagram Url'=>array('db_column'=>'instagram_url'),
                    'Youtube Video Id 1'=>array('db_column'=>'youtube_video_id_1'),
                    'Youtube Video Id 2'=>array('db_column'=>'youtube_video_id_2'),
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

                        if (!empty($headerValue['required_specific_value']) && $headerValue['required_specific_value']) {
                            if ($csvValue != $headerValue['required_value']) {
                                $resultRow['result_message'] = $headerKey.' is mandatory. so please enter => '.$headerValue['required_value'];
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
                        $tableEntryRow['is_subscribe_newsletter'] = ( ($tableEntryRow['is_subscribe_newsletter'] == 'Yes') ? 1 : 0 );
                        $tableEntryRow['is_send_email_to_user'] = ( ($tableEntryRow['is_send_email_to_user'] == 'Yes') ? 1 : 0 );
                        $tableEntryRow['is_active'] = ( ($tableEntryRow['is_active'] == 'Active') ? 1 : 0 );
                        $tableEntryRow['approval_status'] = strtolower($tableEntryRow['approval_status']);

                        if(!empty($tableEntryRow[$this->primaryKey]) && $tableEntryRow[$this->primaryKey] > 0){
                            $id = $tableEntryRow[$this->primaryKey];
                            $results = self::findOrFail($id);
                            $results ->update($tableEntryRow);

                        }else{

                            $tableEntryRow['url_key'] = $this->generateUrlKey([$resultRow['Name'], $resultRow['City'], $resultRow['State']]);
                            $tableEntryRow['url_key'] = $this->checkUrlKeyDuplicate($tableEntryRow['url_key']);
                            $results = self::create($tableEntryRow);
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

    public function getNameByTournamentOrganizationId($tournament_organization_id){

        return $this->setSelect()
            ->addTournamentOrganizationIdFilter($tournament_organization_id)
            ->get(['name'])
            ->pluck('name')
            ->first();
    }

    public function recordCount($name){
        return $this->setSelect()
            ->addNameFilter($name)
            ->get(['tournament_organization_id'])
            ->pluck('tournament_organization_id')
            ->first();
    }

    /* check Status reference key */
    public function checkStatusReference($statusId){
        return $this->_helper->checkStatusReference($statusId);
    }
}
