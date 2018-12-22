<?php

namespace App\Classes\Models\Enquiry;

use App\Classes\Models\BaseModel;
use Mail;

class Enquiry extends BaseModel{
    
	protected $table = 'sbc_enquiry';
    protected $primaryKey = 'enquiry_id';
    
  	protected $entity='sbc_enquiry';
	protected $searchableColumns=['entity_name'];
    protected $fillable = ['entity_type',
                            'entity_id',
                            'entity_name',
                            'contact_email',
                            'sender_name',
                            'sender_email',
                            'sender_message',
                            'referal_url',
                            'ip_address'];

	public function __construct(array $attributes=[])
    {
        $this->bootIfNotBooted();
        $this->syncOriginal();
        $this->fill($attributes);
    }

	
	public function addEnquiryIdFilter($enquiry_id=0)
	{
		$this->queryBuilder->where('enquiry_id',$enquiry_id);
		return $this;
	}


    public function postSendEnquiryMail($data){
        $data['subject'] = 'Lead from Softball Connected';
        try {
            Mail::to($data['contact_email'])->send(new \App\Mail\SendEnquiryEmail((object)$data));
            return true;

        }catch(Exception $e){
            $this->setResponseMessageForError();
            return $this->response;
        }
        return false;
    }

	public function saveRecord($data)
	{
		$this->beforeSave($data);
        $enquiry = \App\Classes\Models\Enquiry\Enquiry::create($data);
        $this->afterSave($data,$enquiry);
	}
}