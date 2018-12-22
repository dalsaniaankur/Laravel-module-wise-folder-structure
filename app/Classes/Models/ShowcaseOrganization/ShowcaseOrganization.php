<?php
namespace App\Classes\Models\ShowcaseOrganization;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

use App\Classes\Models\BaseModel;
use App\Classes\Models\Images\Images;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Helpers\ShowcaseOrganization\Helper;
use App\Classes\Models\Members\Members;

class ShowcaseOrganization extends BaseModel{
    
	protected $table = 'sbc_showcase_organization';
    protected $primaryKey = 'showcase_organization_id';
    
  	protected $entity='showcase_organization';
	protected $searchableColumns=['name'];

    protected $fillable = [ 'submitted_by_id',
							'name',
							'contact_name',
							'url_key',
							'address_1',
							'address_2',
							'location',
							'city_id',
							'state_id',
							'zip',
							'phone_number',
							'email',
							'description',
							'attachment_name_1',
							'attachment_path_1',
							'attachment_name_2',
							'attachment_path_2',
							'attachment_name_3',
							'attachment_path_3',
							'website_url',
							'facebook_url',
							'twitter_url',
							'instagram_url',
							'youtube_video_id_1',
							'youtube_video_id_2',
							'is_show_advertise',
							'longitude',
							'latitude',
							'image_id',
							'is_active',
							'is_send_email_to_user',
							];

    protected $memberObj;
    protected $stateObj;
    protected $cityObj;
    protected $ImagesObj;
    protected $_helper;

    public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
		$this->_helper =new Helper();
        $this->memberObj = new Members();
        $this->stateObj = new State();
        $this->cityObj = new City();
        $this->ImagesObj = new Images();
    }

	/**
	**	Model Relation Methods 
	*/
	
    
	public function state()
	{
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }
	
	public function Images()
    {
        return $this->belongsTo(Images::class, 'image_id', 'image_id');
    }
	
	/**
	**	Model Filter Methods 
	*/	
	public function addShowcaseOrganizationIdFilter($showcase_organization_id=0)
	{
		$this->queryBuilder->where('showcase_organization_id',$showcase_organization_id);
		return $this;
	}

    public function addNameFilter($name){

        $this->queryBuilder->where('name','=',$name);
        return $this;
    }
	/*
	**	Logic Methods
	*/

    public function addOrderBy($columeName, $orderBy)
    {
        $this->queryBuilder->orderBy($columeName, $orderBy);
        return $this;
    }

    public function addSubmittedByIdFilter($submitted_by_id=0)
    {
        if($submitted_by_id > 0) {
            $this->queryBuilder->where('submitted_by_id', $submitted_by_id);
        }
        return $this;
    }
	public function load($showcase_organization_id)
    {
        $return = $this->beforeLoad($showcase_organization_id);

	    $return = $this->setSelect()
		   			  ->addShowcaseOrganizationIdFilter($showcase_organization_id)
					  ->get()
					  ->first();
		
		$this->afterLoad($showcase_organization_id, $return);

		return $return;
   	}
   	
	public function list($search='',$page=0, $submitted_by_id=0)
	{
		$perpage=$this->_helper->getConfigPerPageRecord();
  		$list=$this->setSelect()
				   ->addSearch($search)
                   ->addSubmittedByIdFilter($submitted_by_id)
				   ->addPaging($page,$perpage)
				   ->get();
		
		return $list;
   	}
	
	public function listTotalCount($search='', $submitted_by_id=0){
		$this->reset();
		$count=$this->setSelect()
				  ->addSearch($search)
                  ->addSubmittedByIdFilter($submitted_by_id)
				  ->get()
				  ->count();
		
		return $count;
	}
	
	public function getAllShowcaseOrganizationDropDown()
	{
		$list=$this->setSelect()
                    ->addOrderBy('name', 'asc')
                    ->get()
                    ->pluck('name','showcase_organization_id');
		
		return $list;
   	}
	
	public function preparePagination($total,$basePath){

		$perpage=$this->_helper->getConfigPerPageRecord();
		$pageHelper=new \App\Classes\PageHelper($perpage,'page');
		$pageHelper->set_total($total); 
		$pageHelper->page_links($basePath);
		return $pageHelper->page_links($basePath);
	}
	
	public function saveRecord($data){

        /* Check Duplicate or Form Submit Call */
        $is_form_submit=0;
        if(!empty($data['_token'])){
            $is_form_submit=1;
        }

		$rules=array();	
		$rules=[
				'submitted_by_id'   => 'required',
				'name'              => 'required',
				'url_key'           => 'required|unique:'.$this->table,  
				'contact_name'      => 'required',
				'address_1'         => 'required',
				'city_id'           => 'required',
				'state_id'          => 'required',
				'zip'               => 'required',
				'phone_number'      => 'required',
				'email'             => 'required',
				'longitude'         => 'required',
				'latitude'          => 'required',  
				'showcaseorganization_image'          => 'mimes:jpeg,jpg,png,gif',
		];

        if($is_form_submit == 1){
            $rules['attachment_name_1'] = 'mimes:application/msword,pdf,application/pdf,text/plain';
            $rules['attachment_name_2'] = 'mimes:application/msword,pdf,application/pdf,text/plain';
            $rules['attachment_name_3'] = 'mimes:application/msword,pdf,application/pdf,text/plain';
        }

		$data['url_key'] = str_slug($data['url_key']);
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',showcase_organization_id';
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

        if($is_form_submit == 1) {
            if (!empty($data['showcaseorganization_image'])) {

                $image = $data['showcaseorganization_image'];
                $tournament_organization_image_name = $data['showcaseorganization_image']->getClientOriginalName();
                $tournament_organization_image_name = str_replace('.' . $data['showcaseorganization_image']->getClientOriginalExtension() . '', '_' . time() . '.' . $data['showcaseorganization_image']->getClientOriginalExtension(), $tournament_organization_image_name);

                $destinationPath = public_path('/images/module_images');
                $image->move($destinationPath, $tournament_organization_image_name);

                $image_data['image_name'] = $tournament_organization_image_name;
                $image_data['image_path'] = 'images/module_images/' . $tournament_organization_image_name;
                $image_data['module_id'] = $this->_helper->getModuleId();;

                $image_for_module_wise = \App\Classes\Models\Images\Images::insert($image_data);
                $inserted_image_id = DB::getPdo()->lastInsertId();
            }

            //attachment file
            if (!empty($data['attachment_name_1'])) {
                $attachment_name_1 = $data['attachment_name_1'];
                $attachment_name_1_name = $data['attachment_name_1']->getClientOriginalName();
                $attachment_name_1_name = str_replace('.' . $data['attachment_name_1']->getClientOriginalExtension() . '', '_' . time() . '.' . $data['attachment_name_1']->getClientOriginalExtension(), $attachment_name_1_name);

                $destinationPath = public_path('/images/showcase_organization');
                $attachment_name_1->move($destinationPath, $attachment_name_1_name);

                $data['attachment_name_1'] = $attachment_name_1_name;
                $data['attachment_path_1'] = 'images/showcase_organization/' . $attachment_name_1_name;
            } else {
                unset($data['attachment_name_1']);
                unset($data['attachment_path_1']);
            }

            if (!empty($data['attachment_name_2'])) {
                $attachment_name_2 = $data['attachment_name_2'];
                $attachment_name_2_name = $data['attachment_name_2']->getClientOriginalName();
                $attachment_name_2_name = str_replace('.' . $data['attachment_name_2']->getClientOriginalExtension() . '', '_' . time() . '.' . $data['attachment_name_2']->getClientOriginalExtension(), $attachment_name_1_name);

                $destinationPath = public_path('/images/showcase_organization');
                $attachment_name_2->move($destinationPath, $attachment_name_2_name);

                $data['attachment_name_2'] = $attachment_name_2_name;
                $data['attachment_path_2'] = 'images/showcase_organization/' . $attachment_name_2_name;
            } else {
                unset($data['attachment_name_2']);
                unset($data['attachment_path_2']);
            }

            if (!empty($data['attachment_name_3'])) {
                $attachment_name_3 = $data['attachment_name_3'];
                $attachment_name_3_name = $data['attachment_name_3']->getClientOriginalName();
                $attachment_name_3_name = str_replace('.' . $data['attachment_name_3']->getClientOriginalExtension() . '', '_' . time() . '.' . $data['attachment_name_3']->getClientOriginalExtension(), $attachment_name_1_name);

                $destinationPath = public_path('/images/showcase_organization');
                $attachment_name_3->move($destinationPath, $attachment_name_3_name);

                $data['attachment_name_3'] = $attachment_name_3_name;
                $data['attachment_path_3'] = 'images/showcase_organization/' . $attachment_name_3_name;
            } else {
                unset($data['attachment_name_3']);
                unset($data['attachment_path_3']);
            }

            if (!empty($inserted_image_id)) {
                $data['image_id'] = $inserted_image_id;
            }
        }
		if(!empty($data['is_active']) && $data['is_active'] ='on'){
			$data['is_active'] = 1;
		}else{
			$data['is_active'] = 0;
		}

		if(!empty($data['is_send_email_to_user']) && $data['is_send_email_to_user'] ='on'){
			$data['is_send_email_to_user'] = 1;
		}else{
			$data['is_send_email_to_user'] = 0;
		}
		
		$this->beforeSave($data);
		if(isset($data['id']) && $data['id'] !=''){   
		  	$showcase_organization = self::findOrFail($data['id']);
		    $showcase_organization ->update($data);
		    $this->afterSave($data,$showcase_organization);
			$result['id']=$showcase_organization ->showcase_organization_id;
	
		}else{
		 	$showcase_organization  = self::create($data);
			$result['id'] = $showcase_organization->showcase_organization_id;
			$this->afterSave($data,$showcase_organization);
		}
		$result['success']=true;
		$result['message']="Showcase Organization Saved Successfully.";
	
		return $result;
	}
	
	public function display($id)
    {
	    $return =$this->load($id);
		return $return;
   	}
	
	public function removed($id)
	{
		$this->beforeRemoved($id);
		$organisation=$this->load($id);
		if(!empty($organisation)){
			 $delete = $organisation->delete();
			 $this->afterRemoved($id);
			 return $delete;
		}
		return false;
	}
	public function getShowcaseOrganizationWidget($submitted_by_id=0){

    	return $this->setSelect()
                     ->addSubmittedByIdFilter($submitted_by_id)
    			     ->get()
    			     ->count();
    }
    public function addIsSendEmailToUserFilter($is_send_email_to_user=1){
    	
		$this->queryBuilder->where('is_send_email_to_user',$is_send_email_to_user);
		return $this;
	}
	
	public function addIsActiveFilter($is_active=1)
	{
		$this->queryBuilder->where('is_active',$is_active);
		return $this;
	}
	
    public function getShowcaseOrganizationListForSendMail(){
	    
	    return $this->setSelect()
	    			->addIsActiveFilter()
	    			->addIsSendEmailToUserFilter()
	   			    ->get();
		
	}
	public function getAllShowcaseOrganizationDropDownWithAllOption()
	{
		$list=$this->setSelect()
                    ->addOrderBy('name', 'asc')
                    ->get()
                    ->pluck('name','showcase_organization_id')
				    ->prepend(trans('quickadmin.qa_all'), 0);
		
		return $list;
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

        $selectedColumns = ['showcase_organization_id',
                            'submitted_by_id',
                            'name',
                            'contact_name',
                            'address_1',
                            'address_2',
                            'location',
                            'state_id',
                            'city_id',
                            'zip',
                            'phone_number',
                            'email',
                            'description',
                            'attachment_name_1',
                            'attachment_path_1',
                            'attachment_name_2',
                            'attachment_path_2',
                            'attachment_name_3',
                            'attachment_path_3',
                            'is_show_advertise',
                            'website_url',
                            'facebook_url',
                            'twitter_url',
                            'youtube_video_id_1',
                            'youtube_video_id_2',
                            'longitude',
                            'latitude',
                            'image_id',
                            'is_active',
                            'is_send_email_to_user',];

        $csvHeaderLable = ['showcase_organization_id' => 'Showcase Organization Id',
                            'submitted_by_id' => 'Member',
                            'name' => 'Name',
                            'contact_name' => 'Contact Name',
                            'address_1' => 'Address 1',
                            'address_2' => 'Address 2',
                            'location' => 'Location',
                            'state_id' => 'State',
                            'city_id' => 'City',
                            'zip' => 'Zip',
                            'phone_number' => 'Phone Number',
                            'email' => 'Email',
                            'description' => 'Description',
                            'attachment_name_1' => 'Attachment Name 1',
                            'attachment_path_1' => 'Attachment Path 1',
                            'attachment_name_2' => 'Attachment Name 2',
                            'attachment_path_2' => 'Attachment Path 2',
                            'attachment_name_3' => 'Attachment Name 3',
                            'attachment_path_3' => 'Attachment Path 3',
                            'is_show_advertise' => 'Is Show Advertise',
                            'website_url' => 'Website Url',
                            'facebook_url' => 'Facebook Url',
                            'twitter_url' => 'Twitter Url',
                            'instagram_url' => 'Instagram Url',
                            'youtube_video_id_1' => 'Youtube Video Id 1',
                            'youtube_video_id_2' => 'Youtube Video Id 2',
                            'longitude' => 'Longitude',
                            'latitude' => 'Latitude',
                            'image_id' => 'Image Path',
                            'is_active' => 'Is Active',
                            'is_send_email_to_user' => 'Is Send Email To User'];

        $results = $this->list($search, $page, $submitted_by_id=0);
        $csvExportPath = $this->_helper->getCsvExportFolderPath();

        /* Data Format */
        if(!empty($results)){
            foreach ($results as $value) {

                if($value->submitted_by_id > 0){
                    $value->submitted_by_id = $this->memberObj->getMemberEmailByMemberId($value->submitted_by_id);
                }else{
                    $value->submitted_by_id = "";
                }

                /* Set Image Path */
                if($value->image_id > 0){
                    $value->image_id = $this->ImagesObj->getImagePathByImageId($value->image_id);
                }else{
                    $value->image_id = "";
                }

                $value->state_id = $value->state->name;
                $value->city_id = $value->city->city;
                $value->is_active = ($value->is_active == 1) ? 'Active' : 'Deactive';
                $value->is_send_email_to_user = ($value->is_send_email_to_user == 1) ? 'Yes' : 'No';
                $value->is_show_advertise = ($value->is_show_advertise == 1) ? 'Yes' : 'No';

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
                    'Showcase Organization Id'=>array('db_column'=>'showcase_organization_id'),
                    'Member'=>array('db_column'=>'submitted_by_id','reference_key_with_zero_allow'=>true,'reference_function'=>'checkMemberReference'),
                    'Name'=>array('db_column'=>'name','required'=>true),
                    'Contact Name'=>array('db_column'=>'contact_name','required'=>true),
                    'Address 1'=>array('db_column'=>'address_1','required'=>true),
                    'Address 2'=>array('db_column'=>'address_2'),
                    'Location'=>array('db_column'=>'location'),
                    'State'=>array('db_column'=>'state_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkStateReference'),
                    'City'=>array('db_column'=>'city_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkCityReference'),
                    'Zip'=>array('db_column'=>'zip','required'=>true),
                    'Phone Number'=>array('db_column'=>'phone_number','required'=>true),
                    'Email'=>array('db_column'=>'email','required'=>true),
                    'Description'=>array('db_column'=>'description'),
                    'Attachment Name 1'=>array('db_column'=>'attachment_name_1'),
                    'Attachment Path 1'=>array('db_column'=>'attachment_path_1'),
                    'Attachment Name 2'=>array('db_column'=>'attachment_name_2'),
                    'Attachment Path 2'=>array('db_column'=>'attachment_path_2'),
                    'Attachment Name 3'=>array('db_column'=>'attachment_name_3'),
                    'Attachment Path 3'=>array('db_column'=>'attachment_path_3'),
                    'Is Show Advertise'=>array('db_column'=>'is_show_advertise'),
                    'Website Url'=>array('db_column'=>'website_url'),
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

                        $tableEntryRow[$headerValue['db_column']] = $csvValue;
                    }

                    if($validRow){

                        $tableEntryRow['is_show_advertise'] = ( ($tableEntryRow['is_show_advertise'] == 'Yes') ? 1 : 0 );
                        $tableEntryRow['is_active'] = ( ($tableEntryRow['is_active'] == 'Active') ? 1 : 0 );
                        $tableEntryRow['is_send_email_to_user'] = ( ($tableEntryRow['is_send_email_to_user'] == 'Yes') ? 1 : 0 );

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
    /* check Images model reference key */
    public function checkImageReference($imagePath){
        $module_id = $this->_helper->getModuleId();
        return $this->ImagesObj->recordCount($imagePath, $module_id);
    }

    public function recordCount($name){
        return $this->setSelect()
            ->addNameFilter($name)
            ->get(['showcase_organization_id'])
            ->pluck('showcase_organization_id')
            ->first();
    }

}