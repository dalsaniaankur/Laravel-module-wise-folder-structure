<?php

namespace App\Http\Controllers\Enquiry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Models\Enquiry\Enquiry;
use App\Classes\Models\Tournament\Tournament;
use App\Classes\Models\Team\Team;
use App\Classes\Models\Tryout\Tryout;
use App\Classes\Models\Academies\Academies;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrProspect;


class IndexController extends Controller{

    protected $enquiryObj;
    protected $tournamentObj;
    protected $teamObj;
    protected $tryoutObj;
    protected $academiesObj;
    protected $showcaseOrProspectObj;

    public function __construct(Enquiry $enquiry){

        $this->enquiryObj = $enquiry;
        $this->tournamentObj = new Tournament();
        $this->teamObj = new Team();
        $this->tryoutObj = new Tryout();
        $this->academiesObj = new Academies();
        $this->showcaseOrProspectObj = new ShowcaseOrProspect();

    }

    public function postSendEnquiryMail(Request $request){
        $data = $request->all();
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
        switch ($data['entity_type']) {
            case "tournament":
                $entity = $this->tournamentObj->loadByTournamentIdForEnquiry($data['entity_id']);
                $data['entity_name'] = $entity->contact_name;
                $data['contact_email'] = $entity->email;
                break;
            case "team":
                $entity = $this->teamObj->loadByTeamIdForEnquiry($data['entity_id']);
                $data['entity_name'] = $entity->contact_name;
                $data['contact_email'] = $entity->email;
                break;
            case "tryout":
                $entity = $this->tryoutObj->loadByTryoutIdForEnquiry($data['entity_id']);
                $data['entity_name'] = $entity->contact_name;
                $data['contact_email'] = $entity->email;
                break;
            case "showcase":
                $entity = $this->showcaseOrProspectObj->loadByShowcaseOrProspectIdForEnquiry($data['entity_id']);
                $data['entity_name'] = $entity->name;
                $data['contact_email'] = $entity->email;
                break;
            case "academy":
                $entity = $this->academiesObj->loadByAcademyIdForEnquiry($data['entity_id']);
                $data['entity_name'] = $entity->academy_name;
                $data['contact_email'] = $entity->email;
                break;
        }
        $results = $this->enquiryObj->postSendEnquiryMail($data);
        if($results == true) {
            $this->enquiryObj->saveRecord($data);
        }
        return response()->json($results);
    }





}
