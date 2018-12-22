<?php

namespace App\Classes\Models\Academies;

use App\Classes\Models\BaseModel;
Use DB;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Models\Images\Images;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Models\Members\Members;
use App\Classes\Helpers\Academies\Helper;
use App\Classes\Models\Services\Services;
use Image;

class Academies extends BaseModel{
    
	protected $table = 'sbc_academies';
    protected $primaryKey = 'academy_id';
  	protected $entity='academy';
	protected $searchableColumns=['academy_name'];
    protected $fillable = ['academy_id',
							'member_id',
							'academy_name',
							'url_key',
							'address_1',
							'address_2',
							'city_id',
							'state_id',
							'zip',
							'phone_number',
							'email',
							'website_url',
                            'agree_to_recevie_email_updates',
							'is_subscribe_newsletter',
							'service_id',
							'about',
							'objectives',
							'programs',
							'alumni',
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
    protected $memberObj;
    protected $stateObj;
    protected $cityObj;
    protected $ImagesObj;
    protected $servicesObj;
    protected $_helper;

	public function __construct(array $attributes = []){

        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
        $this->memberObj = new Members();
        $this->stateObj = new State();
        $this->cityObj = new City();
        $this->ImagesObj = new Images();
        $this->servicesObj = new Services();
    }

    public function state(){

        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function city(){

        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }
	
	public function addAcademyIdFilter($academy_id=0)
	{
		$this->queryBuilder->where('academy_id',$academy_id);
		return $this;
	}

	public function Images()
    {
        return $this->belongsTo(Images::class, 'image_id', 'image_id');
    }
	
	public function addServiceFilter($service_id_array=array())
	{
		if(!empty($service_id_array)){
    		$fieldName = $this->table.'.service_id';

    		$this->queryBuilder->where(function($q) use ($service_id_array, $fieldName) {
    			foreach ($service_id_array as $key => $value) {
    				$q->orWhereRaw("find_in_set('".$value."',".$fieldName.")");
	    		}
			});
    	}
		return $this;
	}

    public function addMemberIdFilter($member_id=0)
    {
        if($member_id > 0) {
            $this->queryBuilder->where('member_id', $member_id);
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
    public function addApprovalStatusFilter($approvalStatus = ''){

        if(!empty(trim($approvalStatus))){
            $this->queryBuilder->where($this->table.'.approval_status',$approvalStatus);
        }
        return $this;
    }

	public function list($search='',$page=0, $state_id=0, $city_id=0, $redius=0, $isFront=0, $is_active=2, $latitude='', $longitude='', $academy_name='', $service_id=array(), $sortedBy='', $sortedOrder='', $per_page=0, $selectColoumn=array('*'), $member_id=0, $approvalStatus=''){

  		$per_page = $per_page == 0 ? $this->_helper->getConfigPerPageRecord() : $per_page;
  		$list=$this->setSelect()
  				   ->addSearch($search)
  				   ->addStateIdFilter($state_id)
                   ->addMemberIdFilter($member_id)
                   ->addApprovalStatusFilter($approvalStatus)
  				   ->addCityIdFilter($city_id)
  				   ->addAcademyNameFilter($academy_name)
  				   ->addMileRadiusFilter($redius, $latitude, $longitude)
  				   ->addServiceFilter($service_id)
  				   ->addIsActiveFilter($is_active)
				   ->addOrderBy($sortedBy, $sortedOrder)
				   ->addPaging($page,$per_page)
				   ->addgroupBy($this->table.'.academy_id')
				   ->get();

			if($isFront == 1){
				
				if(count($list)>0){
		
				$servicesObj = new \App\Classes\Models\Services\Services();
				foreach($list as $row){

					if(!empty($row->service_id)){
						$services_id_array = $this->getCSVToArray($row->service_id);
					}
					$services_list = array();
					if(!empty($services_id_array)){
						foreach ($services_id_array as $service_id) {
							$service_result = $servicesObj->getServicesByServiceId($service_id);		
							$services_list[] = $service_result->name;
						}
					}
					$row['services_list'] = $this->getColumnList($services_list, ', ');	
				}
			}	
		}				   

		return $list;
   	}
	
	public function listTotalCount($search='',$page=0, $state_id=0, $city_id=0, $redius=0, $isFront=0, $is_active=2, $latitude='', $longitude='', $academy_name='', $service_id=array(), $sortedBy='', $sortedOrder='', $member_id=0, $approvalStatus=''){
	    $this->reset();
		$count=$this->setSelect()
				  	->addSearch($search)
				  	->addCityIdFilter($city_id)
				  	->addStateIdFilter($state_id)
                    ->addMemberIdFilter($member_id)
                    ->addApprovalStatusFilter($approvalStatus)
				  	->addAcademyNameFilter($academy_name)
				  	->addMileRadiusFilter($redius, $latitude, $longitude)
  				    ->addServiceFilter($service_id)
  				    ->addIsActiveFilter($is_active)
				    ->addOrderBy($sortedBy, $sortedOrder)
				    ->addgroupBy($this->table.'.academy_id')
				  	->get()
				  	->count();
		
		return $count;
	}
	public function addgroupBy($groupByName){
		
		$this->queryBuilder->groupBy($groupByName);	
		return $this;
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
			'academy_name' => 'required',  
			'url_key'      => 'required|unique:'.$this->table,  
			'address_1'    => 'required',  
			'city_id'      => 'required',
			'state_id'     => 'required',  
			'zip'          => 'required',  
			'longitude'    => 'required',  
			'latitude'     => 'required',  
		];

        if($is_form_submit == 1){
            $rules['academy_image'] = 'mimes:jpeg,jpg,png,gif|dimensions:min_width=250';
        }
		
		$data['url_key'] = str_slug($data['url_key']);
		if(isset($data['id']) && $data['id'] !=''){ 
		   $id=$data['id'];
		   $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',academy_id';
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

        if($is_form_submit == 1) {
            if (!empty($data['academy_image'])) {

                $image = $data['academy_image'];
                $academy_image_name = $data['academy_image']->getClientOriginalName();
                $academy_image_name = str_replace('.' . $data['academy_image']->getClientOriginalExtension() . '', '_' . time() . '.' . $data['academy_image']->getClientOriginalExtension(), $academy_image_name);
                $destinationPath = public_path('/images/module_images');

                $aspectImage = Image::make($image)->resize(250, null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->save($destinationPath.'/'.$academy_image_name);

                $image_data['image_name'] = $academy_image_name;
                $image_data['image_path'] = 'images/module_images/' . $academy_image_name;
                $image_data['module_id'] = $this->_helper->getModuleId();

                \App\Classes\Models\Images\Images::insert($image_data);
                $inserted_image_id = DB::getPdo()->lastInsertId();
            }
        }

		if(!empty($data['service_id'])){
			$data['service_id'] = $this->getArrayToCSV($data['service_id']);
		}else{
			$data['service_id'] ='';
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
		  	$academies = \App\Classes\Models\Academies\Academies::findOrFail($data['id']);
            $academies->update($data);	
			$this->afterSave($data,$academies);
			$result['id']=$academies->academy_id;	
		}else{
		 	 $academies = \App\Classes\Models\Academies\Academies::create($data);
			 $result['id']=$academies->academy_id;
			 $this->afterSave($data,$academies);
		}
		$result['success']=true;
		$result['message']="Academy Saved Successfully.";
		return $result;
	}

	public function load($academy_id){

		$this->beforeLoad($academy_id);
	   	
	    $return =$this->setSelect()
	   			  ->addAcademyIdFilter($academy_id)	
				  ->get()
				  ->first();

		$this->afterLoad($academy_id, $return);
				  
		return $return;
   	}
	
	public function display($academy_id)
    {
		$return = $this->load($academy_id); 

		if(!empty($return->service_id)){		  
			
			$return->service_id = $this->getCSVToArray($return->service_id);
		}   		

		return $return;
   	}
	
	public function removed($academy_id)
	{
		$this->beforeRemoved($academy_id);
		$deleteMemberObj=$this->display($academy_id);
		if(!empty($deleteMemberObj)){
			 $delete = $deleteMemberObj->delete();
			 $this->afterRemoved($academy_id);
			 return $delete;
		}
		return false;
	}
    public function addSubmittedByIdFilter($submitted_by_id = 0){

        if($submitted_by_id > 0){
            $this->queryBuilder->where('submitted_by_id',$submitted_by_id);
        }
        return $this;
    }
	public function getAcademyWidget($member_id = 0){
    	return $this->setSelect()
                    ->addMemberIdFilter($member_id)
    			      ->get()
    			      ->count();
    }
    
    public function addIsSendEmailToUserFilter($is_send_email_to_user=1){
    	
		$this->queryBuilder->where('is_send_email_to_user',$is_send_email_to_user);
		return $this;
	}
    public function addIsActiveFilter($is_active=2){
    	
    	if($is_active != 2){
			$this->queryBuilder->where('is_active',$is_active);
		}
		return $this;
	}
    public function getAcademiesListForSendMail()
	{
	    return $this->setSelect()
	    			 ->addIsActiveFilter(1)
	    			 ->addIsSendEmailToUserFilter()
	   			     ->get();
		
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
							        <td data-title='".trans('front.academies_grid.fields.academy')."'><a href='".$data->getUrl()."'>".$data->academy_name."</a></td>
							        <td data-title='".trans('front.academies_grid.fields.location')."'>".$data->city->city.', '.$data->state->name."</td>
							        <td data-title='".trans('front.academies_grid.fields.service_id')."'>".$data->services_list."</td>
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

	    $academy = $this->setSelect()
	   			     ->addUrlKeyFilter($url_key)	
	   			     ->addIsActiveFilter(1)
				     ->get()
				     ->first();
		
		if(!empty($academy)){
			/* Services */
			$servicesObj = new \App\Classes\Models\Services\Services();
			if(!empty($academy->service_id)){
				$services_id_array = $this->getCSVToArray($academy->service_id);
			}
			$services_list = array();
			if(!empty($services_id_array)){
				foreach ($services_id_array as $service_id) {
					$service_result = $servicesObj->getServicesByServiceId($service_id);		
					$services_list[] = $service_result->name;
				}
			}
			$academy['services_list'] = $this->getColumnList($services_list, ' - ');	
		}		
		return $academy;     
   	}

   	public function getUrl(){ 
   		return \URL::to('academies') .'/'.$this->url_key;
   	}

   	public function addAcademyNameFilter($academy_name){
   		
   		if(!empty($academy_name)){
			$this->queryBuilder->where($this->table.'.academy_name', 'like', '%'.$academy_name.'%');
		}
		return $this;
	}

	public function HeaderSearch( $search, $city_id, $state_id, $latitude, $longitude, $redius ){
		$per_page = $this->_helper->getConfigRecordForTopsearch();
		$selectColoumn = ['academy_id','academy_name','url_key'];		
		$listArray = array();
		$list = $this->list( $search, $page=0, $state_id, $city_id, $redius, $isFront=1, $is_active=1, $latitude, $longitude, $academy_name='', $service_id=array(), $sortedBy='academy_name', $sortedOrder='ASC', $per_page, $selectColoumn);
		    
	    if(!empty($list)) {
    		foreach ($list as $key => $value) {
    			$data['title'] = $value->academy_name;
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
    public function loadByAcademyIdForEnquiry($academy_id){

        $selectColoumn = [$this->table.'.academy_name',
            $this->table.'.email'];

        $return = $this->setSelect()
            ->addAcademyIdFilter($academy_id)
            ->get($selectColoumn)
            ->first();

        return $return;
    }

    public function exportCSV($entity, $search, $page){

        $selectedColumns = ['academy_id',
                            'member_id',
                            'academy_name',
                            'address_1',
                            'address_2',
                            'state_id',
                            'city_id',
                            'zip',
                            'phone_number',
                            'email',
                            'website_url',
                            'agree_to_recevie_email_updates',
                            'is_subscribe_newsletter',
                            'facebook_url',
                            'twitter_url',
                            'instagram_url',
                            'youtube_video_id_1',
                            'youtube_video_id_2',
                            'service_id',
                            'about',
                            'objectives',
                            'programs',
                            'alumni',
                            'longitude',
                            'latitude',
                            'image_id',
                            'is_active',
                            'is_send_email_to_user',
                            'approval_status'];

        $csvHeaderLable = [ 'academy_id' => 'Academy Id',
                            'member_id' => 'Member',
                            'academy_name' => 'Academy Name',
                            'address_1' => 'Address 1',
                            'address_2' => 'Address 2',
                            'state_id' => 'State',
                            'city_id' => 'City',
                            'zip' => 'Zip',
                            'phone_number' => 'Phone Number',
                            'email' => 'Email',
                            'website_url' => 'Website Url',
                            'agree_to_recevie_email_updates' => 'Agree To Recevie Email Updates',
                            'is_subscribe_newsletter' => 'Is Subscribe Newsletter',
                            'facebook_url' => 'Facebook Url',
                            'twitter_url' => 'Twitter Url',
                            'instagram_url' => 'Instagram Url',
                            'youtube_video_id_1' => 'Youtube Video Id 1',
                            'youtube_video_id_2' => 'Youtube Video Id 2',
                            'service_id' => 'Services',
                            'about' => 'About',
                            'objectives' => 'Objectives',
                            'programs' => 'Programs',
                            'alumni' => 'Alumni',
                            'longitude' => 'Longitude',
                            'latitude' => 'Latitude',
                            'image_id' => 'Image Path',
                            'is_active' => 'Is Active',
                            'is_send_email_to_user' => 'Is Send Email To User',
                            'approval_status' => 'Approval Status'];

        $results = $this->list($search,$page, $state_id=0, $city_id=0, $redius=0, $isFront=0, $is_active=2, $latitude='', $longitude='', $academy_name='', $service_id=array(), $sortedBy='', $sortedOrder='', $per_page=0, $selectColoumn=array('*'), $member_id=0, $approvalStatus='');
        $csvExportPath = $this->_helper->getCsvExportFolderPath();

        /* Data Format */
        $module_id = $this->_helper->getModuleId();
        if(!empty($results)){
            foreach ($results as $value) {

                if($value->member_id > 0){
                    $value->member_id = $this->memberObj->getMemberEmailByMemberId($value->member_id);
                }else{
                    $value->member_id = "";
                }

                $value->state_id = $value->state->name;
                $value->city_id = $value->city->city;

                /* Set Image Path */
                if($value->image_id > 0){
                    $value->image_id = $this->ImagesObj->getImagePathByImageId($value->image_id);
                }else{
                    $value->image_id = "";
                }

                /* service_id */
                if(!empty($value->service_id)){
                    $service_id_array =  explode(',', $value->service_id);
                    $servicesArray = array();
                    foreach ($service_id_array  as $key=> $value_service_id ) {
                        $servicesArray[] = $this->servicesObj->getServiceNameById($value_service_id, $module_id);
                    }
                    $value->service_id = implode($servicesArray,':');
                }

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
    public function importCSV($data, $member_id=0){

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
                    'Academy Id'=>array('db_column'=>'academy_id'),
                    'Member'=>array('db_column'=>'member_id','reference_key_with_zero_allow'=>true,'reference_function'=>'checkMemberReference'),
                    'Academy Name'=>array('db_column'=>'academy_name','required'=>true),
                    'Address 1'=>array('db_column'=>'address_1','required'=>true),
                    'Address 2'=>array('db_column'=>'address_2'),
                    'State'=>array('db_column'=>'state_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkStateReference'),
                    'City'=>array('db_column'=>'city_id','required'=>true,'reference_key'=>true,'reference_function'=>'checkCityReference'),
                    'Zip'=>array('db_column'=>'zip','required'=>true),
                    'Phone Number'=>array('db_column'=>'phone_number'),
                    'Email'=>array('db_column'=>'email'),
                    'Website Url'=>array('db_column'=>'website_url'),
                    'Is Subscribe Newsletter'=>array('db_column'=>'is_subscribe_newsletter'),
                    'Agree To Recevie Email Updates'=>array('db_column'=>'agree_to_recevie_email_updates','required_specific_value'=>true, 'required_value'=>'Yes'),
                    'Services'=>array('db_column'=>'service_id','custom_function'=>'checkServiceReference'),
                    'About'=>array('db_column'=>'about'),
                    'Objectives'=>array('db_column'=>'objectives'),
                    'Programs'=>array('db_column'=>'programs'),
                    'Alumni'=>array('db_column'=>'alumni'),
                    'Facebook Url'=>array('db_column'=>'facebook_url'),
                    'Twitter Url'=>array('db_column'=>'twitter_url'),
                    'Instagram Url'=>array('db_column'=>'instagram_url'),
                    'Youtube Video Id 1'=>array('db_column'=>'youtube_video_id_1'),
                    'Youtube Video Id 2'=>array('db_column'=>'youtube_video_id_2'),
                    'Longitude'=>array('db_column'=>'longitude','required'=>true),
                    'Latitude'=>array('db_column'=>'latitude','required'=>true),
                    'Image Path'=>array('db_column'=>'image_id','reference_key_with_zero_allow'=>true,'reference_function'=>'checkImageReference'),
                    'Is Active'=>array('db_column'=>'is_active'),
                    'Approval Status'=>array('db_column'=>'approval_status','required'=>true,'custom_function'=>'checkStatusReference'),
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

                        if (!empty($headerValue['required_specific_value']) && $headerValue['required_specific_value']) {
                            if ($csvValue != $headerValue['required_value']) {
                                $resultRow['result_message'] = $headerKey.' is mandatory. so please enter => '.$headerValue['required_value'];
                                $validRow = false;
                                continue;
                            }
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

                        if (!empty($headerValue['custom_function']) && $headerValue['custom_function']) {

                            $custom_function = $headerValue['custom_function'];

                            if($custom_function == "checkServiceReference") {

                                if (!empty($csvValue)) {
                                    $servicesList = explode(':', $csvValue);
                                    $serviceId = array();
                                    foreach ($servicesList as $key => $serviceValue) {
                                        $isExistReferenceID = $this->{$custom_function}($serviceValue);

                                        if (!isset($isExistReferenceID) || empty($isExistReferenceID) || $isExistReferenceID == 0) {
                                            $resultRow['result_message'] = 'Reference '.$headerKey.' in '.$serviceValue.' is not exist';
                                            $validRow = false;
                                            continue;
                                        }

                                        $serviceId[] = $isExistReferenceID;
                                    }

                                    $csvValue = implode(',', $serviceId);
                                }
                            }

                            if($custom_function == "checkStatusReference") {

                                $isExistReferenceID = $this->{$custom_function}($csvValue);

                                if (!isset($isExistReferenceID) || empty($isExistReferenceID)) {
                                    $resultRow['result_message'] = 'Reference '.$headerKey.' is not exist';
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

                        }else {

                            $tableEntryRow['url_key'] = $this->generateUrlKey([$resultRow['Academy Name'], $resultRow['City'], $resultRow['State']]);
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

    /* check Service model reference key */
    public function checkServiceReference($name){
        $module_id = $this->_helper->getModuleId();
        return $this->servicesObj->getServiceIdByName($name, $module_id);
    }

    /* check Status reference key */
    public function checkStatusReference($statusId){
        return $this->_helper->checkStatusReference($statusId);
    }

}