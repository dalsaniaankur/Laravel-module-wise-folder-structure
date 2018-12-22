<?php
namespace App\Http\Controllers\Administrator\SendEmail;

use App\Classes\Models\EmailTemplate\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use App\Mail\SendEmail;
use App\Classes\Models\Members\Members;
use App\Classes\Models\Instructors\Instructors;
use App\Classes\Models\Academies\Academies;
use App\Classes\Models\Team\Team;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization;
use App\Classes\Models\CoachesNeeded\CoachesNeeded;
use App\Classes\Models\LookupForPlayerExperience\LookupForPlayerExperience;
use App\Classes\Helpers\SendEmail\Helper;

class IndexController extends Controller{

    protected $emailTemplateObj;
     
    public function __construct(EmailTemplate $emailTemplate){  
        
        $this->emailTemplateObj = $emailTemplate;
        $this->response=array();
        $this->response['success'] = true;
        $this->response['message'] ='Mail send successfully.';
        $this->_helper = new Helper();
    }

    public function setResponseMessageForError(){ 

        $this->response['message'] = 'Oops! Something went wrong.';
        $this->response['success'] = false;
    }
    public function setResponseMessageForNoRecordFound(){ 

        $this->response['message'] = 'No record Found.';
        $this->response['success'] = false;
    }

    public function updateLastEmailSentDateByEmailTemplateId($email_template_id){ 

        $emailTemplateObj = \App\Classes\Models\EmailTemplate\EmailTemplate::findOrFail($email_template_id);
        $emailTemplateObj->update(['last_email_sent_date' => date("Y-m-d H:i:s")]); 
    }

    public function SendMail($email, $emailTemplate){ 
        try {

            Mail::to($email)->send(new \App\Mail\SendEmail($emailTemplate));
                
        }catch(Exception $e){

            $this->setResponseMessageForError();
            return $this->response;
        }
    }

    public function PlayerSendMail(){ 
                
        $emailTemplate = $this->emailTemplateObj->getEmailTemplateForEntityType($this->_helper->getPlayerType());
        
        if(!empty($emailTemplate->template_content)){

            $memberObj = new \App\Classes\Models\Members\Members();
            $members = $memberObj->getMemberListForSendMail($this->_helper->getEntityPlayerType());
            
            if(count($members)>0){
                
                foreach ($members as $member) {

                    $this->SendMail($member->email, $emailTemplate);
                }

                $this->updateLastEmailSentDateByEmailTemplateId($emailTemplate->email_template_id);
                return $this->response;

            }else{

                $this->setResponseMessageForNoRecordFound();
                return $this->response;
            }

        }else{
            
            $this->setResponseMessageForNoRecordFound();
            return $this->response;
        }
    }

    public function ParentSendMail(){ 
                
        $emailTemplate = $this->emailTemplateObj->getEmailTemplateForEntityType($this->_helper->getParentType());
        
        if(!empty($emailTemplate->template_content)){

            $memberObj = new \App\Classes\Models\Members\Members();
            $members = $memberObj->getMemberListForSendMail($this->_helper->getEntityParentType());
            if(count($members)>0){
                
                foreach ($members as $member) {

                    $this->SendMail($member->email, $emailTemplate);
                }

                $this->updateLastEmailSentDateByEmailTemplateId($emailTemplate->email_template_id);
                return $this->response;

            }else{

                $this->setResponseMessageForNoRecordFound();
                return $this->response;
            }

        }else{
            
            $this->setResponseMessageForNoRecordFound();
            return $this->response;
        }
    }

    public function CoachSendMail(){ 
                
        $emailTemplate = $this->emailTemplateObj->getEmailTemplateForEntityType($this->_helper->getCoachType());
        
        if(!empty($emailTemplate->template_content)){

            $memberObj = new \App\Classes\Models\Members\Members();
            $members = $memberObj->getMemberListForSendMail($this->_helper->getEntityCoachType());
            if(count($members)>0){
                
                foreach ($members as $member) {

                    $this->SendMail($member->email, $emailTemplate);
                }

                $this->updateLastEmailSentDateByEmailTemplateId($emailTemplate->email_template_id);
                return $this->response;

            }else{

                $this->setResponseMessageForNoRecordFound();
                return $this->response;
            }

        }else{
            
            $this->setResponseMessageForNoRecordFound();
            return $this->response;
        }
    }

    public function OtherSendMail(){ 
                
        $emailTemplate = $this->emailTemplateObj->getEmailTemplateForEntityType($this->_helper->getOtherType());
        
        if(!empty($emailTemplate->template_content)){

            $memberObj = new \App\Classes\Models\Members\Members();
            $members = $memberObj->getMemberListForSendMail($this->_helper->getEntityOtherType());
            if(count($members)>0){
                
                foreach ($members as $member) {

                    $this->SendMail($member->email, $emailTemplate);
                }

                $this->updateLastEmailSentDateByEmailTemplateId($emailTemplate->email_template_id);
                return $this->response;

            }else{

                $this->setResponseMessageForNoRecordFound();
                return $this->response;
            }

        }else{
            
            $this->setResponseMessageForNoRecordFound();
            return $this->response;
        }
    }

    public function InstructorSendMail(){ 
                
        $emailTemplate = $this->emailTemplateObj->getEmailTemplateForEntityType($this->_helper->getInstructorType());
        
        if(!empty($emailTemplate->template_content)){

            $instructorObj = new \App\Classes\Models\Instructors\Instructors();
            $instructors = $instructorObj->getInstructorsListForSendMail();

            if(count($instructors)>0){
                
                foreach ($instructors as $instructor) {

                    $this->SendMail($instructor->email, $emailTemplate);
                }

                $this->updateLastEmailSentDateByEmailTemplateId($emailTemplate->email_template_id);
                return $this->response;

            }else{

                $this->setResponseMessageForNoRecordFound();
                return $this->response;
            }

        }else{
            
            $this->setResponseMessageForNoRecordFound();
            return $this->response;
        }
    }
    public function AcademySendMail(){ 
                
        $emailTemplate = $this->emailTemplateObj->getEmailTemplateForEntityType($this->_helper->getAcademyType());
        
        if(!empty($emailTemplate->template_content)){

            $AcademiesObj = new \App\Classes\Models\Academies\Academies();
            $Academies = $AcademiesObj->getAcademiesListForSendMail();
            
            if(count($Academies)>0){
                
                foreach ($Academies as $Academy) {

                    $this->SendMail($Academy->email, $emailTemplate);
                }

                $this->updateLastEmailSentDateByEmailTemplateId($emailTemplate->email_template_id);
                return $this->response;

            }else{

                $this->setResponseMessageForNoRecordFound();
                return $this->response;
            }

        }else{
            
            $this->setResponseMessageForNoRecordFound();
            return $this->response;
        }
    }

    public function TeamSendMail(){
                
        $emailTemplate = $this->emailTemplateObj->getEmailTemplateForEntityType($this->_helper->getTeamType());
        
        if(!empty($emailTemplate->template_content)){

            $teamObj = new \App\Classes\Models\Team\Team();
            $teams = $teamObj->getTeamsListForSendMail();
            
            if(count($teams)>0){
                
                foreach ($teams as $team) {

                    $this->SendMail($team->email, $emailTemplate);
                }

                $this->updateLastEmailSentDateByEmailTemplateId($emailTemplate->email_template_id);
                return $this->response;

            }else{

                $this->setResponseMessageForNoRecordFound();
                return $this->response;
            }

        }else{
            
            $this->setResponseMessageForNoRecordFound();
            return $this->response;
        }
    }

    public function OrganizationSendMail(){ 
                
        $emailTemplate = $this->emailTemplateObj->getEmailTemplateForEntityType($this->_helper->getOrganizationsType());
        
        if(!empty($emailTemplate->template_content)){

            $showcaseOrganizationObj = new \App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization();
            $showcaseOrganizations = $showcaseOrganizationObj->getShowcaseOrganizationListForSendMail();
            
            if(count($showcaseOrganizations)>0){
                
                foreach ($showcaseOrganizations as $showcaseOrganization) {

                    $this->SendMail($showcaseOrganization->email, $emailTemplate);
                }

                $this->updateLastEmailSentDateByEmailTemplateId($emailTemplate->email_template_id);
                return $this->response;

            }else{

                $this->setResponseMessageForNoRecordFound();
                return $this->response;
            }

        }else{
            
            $this->setResponseMessageForNoRecordFound();
            return $this->response;
        }
    }

    public function CoachesNeededSendMail(){ 
                
        $emailTemplate = $this->emailTemplateObj->getEmailTemplateForEntityType($this->_helper->getCoachesNeededType());
        
        if(!empty($emailTemplate->template_content)){

            $coachesNeededObj = new \App\Classes\Models\CoachesNeeded\CoachesNeeded();
            $coachesNeededs = $coachesNeededObj->getCoachesNeededListForSendMail();
            if(count($coachesNeededs)>0){
                
                foreach ($coachesNeededs as $coachesNeeded) {

                    $this->SendMail($coachesNeeded->email, $emailTemplate);
                }

                $this->updateLastEmailSentDateByEmailTemplateId($emailTemplate->email_template_id);
                return $this->response;

            }else{

                $this->setResponseMessageForNoRecordFound();
                return $this->response;
            }

        }else{
            
            $this->setResponseMessageForNoRecordFound();
            return $this->response;
        }
    }

    public function PlayersLookingForTeamSendMail(){ 
                
        $emailTemplate = $this->emailTemplateObj->getEmailTemplateForEntityType($this->_helper->getPlayersLookingForTeamType());
        
        if(!empty($emailTemplate->template_content)){

            $lookupForPlayerExperienceObj = new \App\Classes\Models\LookupForPlayerExperience\LookupForPlayerExperience();
            $lookupForPlayerExperiences = $lookupForPlayerExperienceObj->getLookupForPlayerExperienceListForSendMail();

            if(count($lookupForPlayerExperiences)>0){
                
                foreach ($lookupForPlayerExperiences as $lookupForPlayerExperience) {

                    $this->SendMail($lookupForPlayerExperience->player_email, $emailTemplate);
                }

                $this->updateLastEmailSentDateByEmailTemplateId($emailTemplate->email_template_id);
                return $this->response;

            }else{

                $this->setResponseMessageForNoRecordFound();
                return $this->response;
            }

        }else{
            
            $this->setResponseMessageForNoRecordFound();
            return $this->response;
        }
    }

    public function FileuploadForTinymce(Request $request){ 
        
        $data = $request->all();
        if(!empty($data['file'])){
            $image = $data['file'];
            $image_name = $data['file']->getClientOriginalName();
            $image_name = str_replace('.'.$image->getClientOriginalExtension().'','_'.time().'.'.$image->getClientOriginalExtension(), $image_name);
            $destinationPath = public_path('/images/tinymce_images');
            $image->move($destinationPath, $image_name);
            $url = \URL::to('').'/images/tinymce_images/'.$image_name;
            echo json_encode(array('location' => $url));
        }
    }
}