<?php

namespace App\Classes\Models\Instructors;

use App\Classes\Models\BaseModel;
Use DB;
use App\Classes\Models\Images\Images;
use App\Classes\Models\State\State;
use App\Classes\Models\City\City;
use App\Classes\Helpers\Instructor\Helper;
use App\Classes\Models\Members\Members;
use App\Helpers\DateHelper;

class Instructors extends BaseModel{

    protected $table = 'sbc_instructors';
    protected $primaryKey = 'instructor_id';

    protected $entity='instructor';
    protected $searchableColumns=['first_name','last_name','title'];
    protected $memberObj;
    protected $stateObj;
    protected $cityObj;
    protected $ImagesObj;
    protected $_helper;

    protected $fillable = ['instructor_id',
        'member_id',
        'title',
        'url_key',
        'first_name',
        'last_name',
        'address_1',
        'address_2',
        'city_id',
        'state_id',
        'zip',
        'phone_number',
        'email',
        'is_subscribe_newsletter',
        'website_url',
        'blog_url',
        'affilated_academy',
        'age_group_id',
        'focus_id',
        'is_team_coach',
        'profile_description',
        'notable_coaching_achievements',
        'facebook_url',
        'twitter_url',
        'youtube_video_id_1',
        'youtube_video_id_2',
        'article_url_1',
        'article_url_2',
        'is_show_advertise',
        'longitude',
        'latitude',
        'image_id',
        'is_active',
        'is_send_email_to_user'];

    public function __construct(array $attributes = []){

        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
        $this->memberObj = new Members();
        $this->stateObj = new State();
        $this->cityObj = new City();
        $this->ImagesObj = new Images();
    }

    public function state(){

        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function city(){

        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    public function addInstructorIdFilter($instructor_id=0)
    {
        $this->queryBuilder->where('instructor_id',$instructor_id);
        return $this;
    }

    public function Images()
    {
        return $this->belongsTo(Images::class, 'image_id', 'image_id');
    }

    public function addMemberIdFilter($member_id=0){
        if($member_id > 0) {
            $this->queryBuilder->where('member_id', $member_id);
        }
        return $this;
    }

    public function list($search='',$page=0, $member_id=0, $selectedColumns = array('*'))
    {
        $per_page=$this->_helper->getConfigPerPageRecord();
        $list=$this->setSelect()
            ->addSearch($search)
            ->addMemberIdFilter($member_id)
            ->addPaging($page,$per_page)
            ->get($selectedColumns);

        return $list;
    }

    public function listTotalCount($search='', $member_id=0)
    {
        $this->reset();
        $count=$this->setSelect()
            ->addSearch($search)
            ->addMemberIdFilter($member_id)
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
        $rules=array();
        $rules=[
            'first_name'   => 'required|max:100',
            'last_name'    => 'required|max:100',
            'url_key'      => 'required|unique:'.$this->table,
            'address_1'    => 'required',
            'city_id'      => 'required',
            'state_id'     => 'required',
            'zip'          => 'required',
            'phone_number' => 'required',
            'email'        => 'required|max:100|email',
            'age_group_id' => 'required',
            'focus_id'     => 'required',
            'longitude'    => 'required',
            'latitude'     => 'required',
            'instructor_image'  => 'mimes:jpeg,jpg,png,gif',
        ];

        $data['url_key'] = str_slug($data['url_key']);
        if(isset($data['id']) && $data['id'] !=''){
            $id=$data['id'];
            $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',instructor_id';
        }

        $validationResult=$this->validateData($rules,$data);
        $result=array();
        $result['id']='';

        if($validationResult['success']==false)
        {
            $result['success']=false;
            $result['message']=$validationResult['message'];
            $result['id']=$data['id'];
            return $result;
        }

        if(!empty($data['instructor_image']))
        {
            $image = $data['instructor_image'];
            $instructor_image_name = $data['instructor_image']->getClientOriginalName();
            $instructor_image_name = str_replace('.'.$data['instructor_image']->getClientOriginalExtension().'','_'.time().'.'.$data['instructor_image']->getClientOriginalExtension(),$instructor_image_name);

            $destinationPath = public_path('/images/module_images');
            $image->move($destinationPath, $instructor_image_name);

            $image_data['image_name'] = $instructor_image_name;
            $image_data['image_path'] = 'images/module_images/'.$instructor_image_name;
            $image_data['module_id'] = $this->_helper->getModuleId();

            $image_for_module_wise = \App\Classes\Models\Images\Images::insert($image_data);
            $inserted_image_id = DB::getPdo()->lastInsertId();
        }

        if(!empty($data['age_group_id'])){
            $data['age_group_id'] = $this->getArrayToCSV($data['age_group_id']);
        }else{
            $data['age_group_id'] ='';
        }

        if(!empty($data['focus_id'])){
            $data['focus_id'] = $this->getArrayToCSV($data['focus_id']);
        }else{
            $data['focus_id'] ='';
        }

        if(!empty($inserted_image_id)){
            $data['image_id'] = $inserted_image_id;
        }

        if(!empty($data['is_active']) && $data['is_active'] ='on'){
            $data['is_active'] = 1;
        }else{
            $data['is_active'] = 0;
        }

        if(!empty($data['is_show_advertise']) && $data['is_show_advertise'] ='on'){
            $data['is_show_advertise'] = 1;
        }else{
            $data['is_show_advertise'] = 0;
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

        $this->beforeSave($data);
        if(isset($data['id']) && $data['id'] !=''){
            $instructors = \App\Classes\Models\Instructors\Instructors::findOrFail($data['id']);
            $instructors->update($data);
            $this->afterSave($data,$instructors);
            $result['id']=$instructors->instructor_id;
        }else{
            $instructors = \App\Classes\Models\Instructors\Instructors::create($data);
            $result['id']=$instructors->instructor_id;
            $this->afterSave($data,$instructors);
        }
        $result['success']=true;
        $result['message']="Instructor Saved Successfully.";
        return $result;
    }

    public function load($instructor_id){

        $this->beforeLoad($instructor_id);

        $return = $this->setSelect()
            ->addInstructorIdFilter($instructor_id)
            ->get()
            ->first();

        $this->afterLoad($instructor_id, $return);

        return $return;
    }

    public function display($instructor_id)
    {
        $return = $this->load($instructor_id);

        if(!empty($return->focus_id)){
            $return->focus_id = $this->getCSVToArray($return->focus_id);
        }
        if(!empty($return->age_group_id)){
            $return->age_group_id = $this->getCSVToArray($return->age_group_id);
        }

        return $return;
    }

    public function removed($instructor_id)
    {
        $this->beforeRemoved($instructor_id);
        $deleteMemberObj=$this->display($instructor_id);

        if(!empty($deleteMemberObj)){
            $delete=$deleteMemberObj->delete();
            $this->afterRemoved($instructor_id);
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

    public function getInstructorWidget($member_id = 0)
    {
        return $this->setSelect()
            ->addMemberIdFilter($member_id)
            ->get()
            ->count();
    }
    public function addIsSendEmailToUserFilter($is_send_email_to_user=1)
    {
        $this->queryBuilder->where('is_send_email_to_user',$is_send_email_to_user);
        return $this;
    }

    public function addIsActiveFilter($is_active=1)
    {
        $this->queryBuilder->where('is_active',$is_active);
        return $this;
    }

    public function getInstructorsListForSendMail()
    {
        return $this->setSelect()
            ->addIsActiveFilter()
            ->addIsSendEmailToUserFilter()
            ->get();
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
}