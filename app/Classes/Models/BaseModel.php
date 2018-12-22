<?php
namespace App\Classes\Models;

use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BaseModel extends Model
{
	protected $queryBuilder;
	protected $modelObj;
	protected $joinTables=array();
	
	protected $entity='task';
	protected $searchableColumns=['title'];
	
	/**
	**	Model befor after Methods 
	*/
	protected function beforeSave($data=array())
	{
	
	}
	
	protected function afterSave($data=array(),$object='')
	{
	}
	
	protected function beforeRemoved($id=0)
	{
	}
	
	protected function afterRemoved($id=0)
	{
	}

	protected function beforeLoad($id=0)
	{
	}
	
	protected function afterLoad($id=0, $data)
	{
	}
		
	/**
	**	Model Query builder Methods 
	*/
	public function getEntity()
	{
	 	return $this->entity;
	}
	
	public function getSearchableColumns()
	{
		return $this->searchableColumns;
	}
	
	public function reset()
	{
		$this->queryBuilder='';
		return $this;
	}
	public function setSelect()
	{  
		$this->queryBuilder=$this->query();
		return $this;
	}
	
	public function addSearch($search='')
	{
		$search=trim($search);
		$searchKeyword=explode(" ",$search);
		$searchKeywordArray=array();
		if(count($searchKeyword)>0){
			foreach($searchKeyword as $keyword){
				$searchKeywordArray[]=trim($keyword);
			}
			array_unique($searchKeywordArray);
		}
		if(count($searchKeywordArray)>0){
			$this->queryBuilder->where(function($query) use ($searchKeywordArray){
				  $i=0;
				  foreach($searchKeywordArray as $keyword){ //first table
					 if($i==0){
						 $query->where(function($query) use ($keyword){
							 $j=0;
							 foreach($this->searchableColumns as $column){
								 if($j==0)
									$query->where($this->table.'.'.$column, 'like', '%' . $keyword . '%');
								  else 
									 $query->orWhere($this->table.'.'.$column, 'like', '%' . $keyword . '%');
								  $j++;
							 }
						 });
					 }else{
						$query->orWhere(function($query) use ($keyword){
							 $j=0;
							 foreach($this->searchableColumns as $column){
								 if($j==0)
									$query->where($this->table.'.'.$column, 'like', '%' . $keyword . '%');
								  else 
									 $query->orWhere($this->table.'.'.$column, 'like', '%' . $keyword . '%');
								  $j++;
							 }
						 });	 
					 }
					 $i++;
				  }
				  if(count($this->joinTables)>0){
					  foreach($this->joinTables as $tableRow){
						if($tableRow['searchable']){  
							foreach($searchKeywordArray as $keyword){ 
							 if($i==0){
								 $query->where(function($query) use ($keyword,$tableRow){
									 $j=0;
									 foreach($tableRow['searchableColumns'] as $column){
										 if($j==0)
											$query->where($tableRow['table'].'.'.$column, 'like', '%' . $keyword . '%');
										  else 
											 $query->orWhere($tableRow['table'].'.'.$column, 'like', '%' . $keyword . '%');
										  $j++;
									 }
								 });
							 }else{
								$query->orWhere(function($query) use ($keyword,$tableRow){
									 $j=0;
									 foreach($tableRow['searchableColumns'] as $column){
										 if($j==0)
											$query->where($tableRow['table'].'.'.$column, 'like', '%' . $keyword . '%');
										  else 
											 $query->orWhere($tableRow['table'].'.'.$column, 'like', '%' . $keyword . '%');
										  $j++;
									 }
								 });	 
							 }
							 $i++;
						  }
						}
					 }
				  }
			});
		}
		return $this;
	}
	
	public function addPaging($page=0,$per_page){	

	      if($page != -1 || $page != '-1') {
              $limit = (($page > 0) ? ($page - 1) : $page) * $per_page;
              $this->queryBuilder->skip($limit)->take($per_page);
          }

          return $this;
	}
	
	public function get($selectColoumn=array('*'))
    {
	 	return $this->queryBuilder->get($selectColoumn);
	}

	public function validateData($rules,$data)
	{
		$validator = '';
		$validationResult=array();
		$validationResult['success']=false;
		$validationResult['message']=array();
		
		$validator = \Validator::make($data, $rules);
        if($validator->passes()){
			$validationResult['success']=true;
			return $validationResult;
		}
	    $errors=json_decode($validator->errors());
     	$validationResult['success']=false;
		$validationResult['message']=$errors;
		return $validationResult;
	}

	public function getArrayToCSV($array)
	{

		return implode(",",$array);
    }

    public function getCSVToArray($csv){
    	
    	return explode(",",$csv);
    }

    public function getCuttentData(){
    	
        return \Carbon\Carbon::now()->toDateTimeString();
    }

    public function getCleanSearchData($searchData = null){

    	if(is_array($searchData) && isset($searchData['_token'])){
	    	unset($searchData['_token']);
	    }

	    return ((!empty($searchData)) ? $searchData : new \stdClass()); 
	}

	public function getsearchDataTojsonEncode($searchData = null){

    	if(is_array($searchData) && isset($searchData['_token'])){
	    	unset($searchData['_token']);
	    }

	    return ((!empty($searchData)) ? json_encode($searchData) : new \stdClass()); 
	}

	public function getsearchDataTojsonDecode($searchData){

	    return ((!empty($searchData)) ? json_decode($searchData) : new \stdClass()); 
	}

	public function getLatitudeAndLongitudeData($searchQuery){
				
		$apiUrl = "https://maps.google.com/maps/api/geocode/json?$searchQuery&sensor=false";
		$apiUrl .= "&key=AIzaSyCU20ZTHLSRSWjLMQRox31mvc5DfJXYzSo";
		$jsonResponse = file_get_contents($apiUrl);
		$response = json_decode($jsonResponse);
		$return = array();
		if(!empty($response->results[0]->geometry->location->lat)){
			$return['latitude']	= $response->results[0]->geometry->location->lat;
			$return['longitude']	= $response->results[0]->geometry->location->lng;
		}
		return $return;
	}
	
    public function getColumnList($array, $sign){

		$string ="";
		foreach ($array as $key => $value) {
			if ($key != key($array)){ $string .= $sign; }
			$string .= $value;
		}
		return $string;
	}	

	public function getQueryString($requestData){
		
		$queryString ='';

		if(isset($requestData['page'])){ unset($requestData['page']); }
			
		if(!empty($requestData)){ $queryString .='?'; }
		
		foreach ($requestData as $key => $value) {

			if($key != 'page'){ 

				if(is_array($value)){ 

					$queryString .= http_build_query(array($key => $value)).'&'; 
				}else{

					$queryString .= $key.'='.$value.'&'; 
				}
			}
		}
		if(empty($queryString)){ $queryString .='?'; }
		return $queryString;
	}

	public function getSearchDataToFormat($data, $moduleUrl='', $search, $moduleTitle, $searchFieldName){
		
		$moduleUrl = \URL::to($moduleUrl);
		$response = array();

		if(!empty($data) && count($data)>0){
            $dataTitle['html'] = "<a href='".$moduleUrl."'><div class='list-item-title'><div class='search-inner'><span>".$moduleTitle."</span></div></div></a>";
            $dataTitle['url'] = $moduleUrl;
            $response[] = $dataTitle;

            foreach ($data as $key => $value) {
               $dataContent['html'] =  "<a href='".$value['url']."'>
                                                <div class='list-item-container'>
                                                    <div class='search-label'>                                                    
                                                        <span>".$value['title']."</span>
                                                    </div>
                                                </div></a>";
                $dataContent['url'] = $value['url'];                                               
                $response[] = $dataContent;
            }
            $dataSeeMoreResults['html'] = "<a href='".$moduleUrl."?".$searchFieldName."=".$search."'><div class='list-item-search-all'> <span> VIEW ALL <strong>".$search."</strong></span></div></a>";
            $dataSeeMoreResults['url'] = $moduleUrl;                        
            $response[] = $dataSeeMoreResults;
        }

        return $response;
	}
	public function isLoginMember(){
        $id = 0;
	    if(Auth::guard('member')->check()){
            $member = Auth::guard('member')->user();
            return $member->member_id;
        }
        return $id;
    }
    public function generateDuplidateUrlKey($url_key){
        $newUrlKey = $url_key;
        for($i=1;$i<=100;$i++){
            $newUrlKey = $url_key.'-'.$i;
            $result = $this->checkDuplicateUrlKey($newUrlKey);
            if($result == 1 || $result == '1'){
                continue;
            }
            return $newUrlKey;
            
        }
        return '';
    }

    public function frontGoogleCaptchaValidation($data){

        $result = array( 'success' => true);

        if(!isset($data['g-recaptcha-response']) || empty(trim($data['g-recaptcha-response']))){
            $result['success'] = false;
            return $result;
        }

        $rules = [ 'g-recaptcha-response' => 'required|captcha'];

        $validationResult = $this->validateData($rules, $data);

        if ($validationResult['success'] == false) {
            $result['success'] = false;
            //$result['message'] = $validationResult['message'];
            return $result;
        }
        return $result;
    }
    public function convertDatesToStartDate($dates){
        $date = explode('-', $dates);
        return trim($date[0]);
    }
    public function convertDatesToEndDate($dates){
        $date = explode('-', $dates);
        return trim($date[1]);
    }


    public function generateUrlKey($array){
	    $urlKey = implode(" ",$array);
        $urlKey = str_slug($urlKey);
        return $urlKey;
    }

    public function generateCSV( $results, $entity, $csvHeaderLable, $selectedColumns, $csvExportPath ){
        
        $fileName = $entity."-".time().".csv";
        $filePath = $csvExportPath.$fileName;
        $handle = fopen(public_path($filePath), 'w+');
        $csvHeaderLable = array_values($csvHeaderLable);
        fputcsv($handle, $csvHeaderLable);

        if(!empty($results)){
            foreach ($results as $key => $value) {

                $csvRow = array();
                foreach ($selectedColumns as $columnName){
                    $csvRow[] = $value->{$columnName};
                }

                fputcsv($handle,$csvRow );
            }
        }
        fclose($handle);
        $fileUrl = \URL::to('administrator/download_csv?&filepath='.$filePath);

        return response()->json([ 'success' => true, 'file_url' => $fileUrl, ]);
    }

    public function uploadCSV($file, $csvImportFolderPath, $csvImportResultsFolderPath ){

        $response = array();

        if(!empty($file)){

            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$fileExtension.'','_'.time().'.'.$fileExtension,$fileName);

            $destinationPath = public_path($csvImportFolderPath);
            $file->move($destinationPath, $fileName);

            $response['FilePath'] = $csvImportFolderPath.$fileName;
            $response['ResultsFilePath'] = $csvImportResultsFolderPath.$fileName;
        }

        return $response;
    }
    public function checkCsvColumnValidation($csvHeader, $header){

	    $rowErrors = array();
        
	    foreach($csvHeader as $key => $value){
            if (!array_key_exists( $value , $header) ){
                $rowErrors[] = '<b>"'.$value.'"</b> column is unspecified or invalid or extra column.';
                break;
            }
        }
                
        foreach($header as $key=>$value){
            
            if (!in_array($key,$csvHeader)){
                $rowErrors[]= '<b>"'.$key.'"</b> column does not exist in imported csv file.';
                break;
            }
        }

        return $rowErrors;
    }
    public function unsetArrayByValue($array, $value){

	    if(!empty($array)){
           if (($key = array_search($value, $array)) !== false) {
               unset($array[$key]);
           }
        }

        return $array;
    }

    function validateDate($date, $format = 'm/d/Y'){
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}