<?php

namespace App\Classes\Models\Event;

use App\Classes\Models\BaseModel;
use App\Classes\Models\Images\Images;
Use DB;
use App\Classes\Helpers\Event\Helper;

class Event extends BaseModel{

    protected $table = 'sbc_events';
    protected $primaryKey = 'event_id';

    protected $entity='event';
    protected $searchableColumns=['title'];
    protected $fillable = ['event_id', 'title', 'url_key', 'event_date', 'content', 'image_id'];
    protected $_helper;
    protected $ImagesObj;


    public function __construct(array $attributes=[])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
        $this->_helper = new Helper();
        $this->ImagesObj = new Images();
    }

    public function Images()
    {
        return $this->belongsTo(Images::class,'image_id','image_id');
    }

    public function addEventIdFilter($event_id=0)
    {
        $this->queryBuilder->where('event_id',$event_id);
        return $this;
    }

    public function setEventDateAttribute($value){
        $this->attributes['event_date'] = date("Y-m-d", strtotime($value));
    }

    public function getEventDateAttribute($value) {
        if(!empty($value)) {
            return \Carbon\Carbon::parse($value)->format('m/d/Y');
        }
        return "";
    }

    public function list($search='',$page=0, $selectedColumns = array('*'))
    {
        $per_page=$this->_helper->getConfigPerPageRecord();
        $list=$this->setSelect()
            ->addSearch($search)
            ->addPaging($page,$per_page)
            ->get($selectedColumns);

        return $list;
    }

    public function listTotalCount($search='')
    {
        $this->reset();
        $count=$this->setSelect()
            ->addSearch($search)
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
            'title'       => 'required',
            'url_key'     => 'required|unique:'.$this->table,
            'event_date'  => 'required',
            'content'     => 'required',
            'event_image' => 'mimes:jpeg,jpg,png,gif',
        ];

        $data['url_key'] = str_slug($data['url_key']);
        if(isset($data['id']) && $data['id'] !=''){
            $id=$data['id'];
            $rules['url_key']='required|unique:'.$this->table.',url_key,'.$id.',event_id';
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

        if(!empty($data['event_image'])){
            $image = $data['event_image'];
            $event_image_name = $data['event_image']->getClientOriginalName();
            $event_image_name = str_replace('.'.$data['event_image']->getClientOriginalExtension().'','_'.time().'.'.$data['event_image']->getClientOriginalExtension(),$event_image_name);

            $destinationPath = public_path('/images/module_images');
            $image->move($destinationPath, $event_image_name);

            $image_data['image_name'] = $event_image_name;
            $image_data['image_path'] = 'images/module_images/'.$event_image_name;
            $image_data['module_id'] = $this->_helper->getModuleId();

            $images = \App\Classes\Models\Images\Images::insert($image_data);
            $inserted_image_id = DB::getPdo()->lastInsertId();
        }

        if(!empty($inserted_image_id)){
            $data['image_id'] = $inserted_image_id;
        }
        $this->beforeSave($data);
        if(isset($data['id']) && $data['id'] !=''){
            $event = \App\Classes\Models\Event\Event::findOrFail($data['id']);
            $event->update($data);
            $this->afterSave($data,$event);
            $result['id']=$event->event_id;
        }else{
            $event = \App\Classes\Models\Event\Event::create($data);
            $this->afterSave($data,$event);
            $result['id']=$event->event_id;
        }
        $result['success']=true;
        $result['message']="Event Saved Successfully.";
        return $result;
    }

    public function load($event_id){

        $this->beforeLoad($event_id);

        $return =$this->setSelect()
            ->addEventIdFilter($event_id)
            ->get()
            ->first();

        $this->afterLoad($event_id, $return);

        return $return;
    }

    public function display($event_id)
    {
        return $this->load($event_id);

    }

    public function removed($event_id)
    {
        $this->beforeRemoved($event_id);
        $deleteImageObj=$this->display($event_id);
        if(!empty($deleteImageObj)){
            $deleted=$deleteImageObj->delete();
            $this->afterRemoved($event_id);
            return $deleted;
        }
        return false;
    }

    public function getEventWidget(){

        return $this->setSelect()
            ->get()
            ->count();
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
