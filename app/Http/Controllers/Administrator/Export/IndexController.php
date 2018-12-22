<?php
namespace App\Http\Controllers\Administrator\Export;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Models\Academies\Academies;
use App\Classes\Models\Team\Team;
use App\Classes\Models\Tryout\Tryout;
use App\Classes\Models\Tournament\Tournament;
use App\Classes\Models\TournamentOrganization\TournamentOrganization;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrProspect;

class IndexController extends Controller{
  
    protected $academyObj;
    protected $teamObj;
    protected $tryoutObj;
    protected $tournamentObj;
    protected $tournamentOrganizationObj;
    protected $showcaseOrganizationObj;
    protected $showcaseOrProspectObj;

    public function __construct()
	{	
        $this->academyObj = new Academies();
        $this->teamObj = new Team();
        $this->tryoutObj = new Tryout();
        $this->tournamentObj = new Tournament();
        $this->tournamentOrganizationObj = new TournamentOrganization();
        $this->showcaseOrganizationObj = new ShowcaseOrganization();
        $this->showcaseOrProspectObj = new ShowcaseOrProspect();
    }

    public function postExportCsv(Request $request){
            
        $data = $request->all();
        $search = !empty(trim($data['search'])) ? $data['search'] : '';
        $page = -1;
        $entity = $data['entity'];
        $response = array('success' => false);

        switch ($entity) {

            case $this->academyObj->getEntity():
                $response = $this->academyObj->exportCSV($entity, $search, $page);
                break;

            case $this->teamObj->getEntity():
                $response = $this->teamObj->exportCSV($entity, $search, $page);
                break;

            case $this->tryoutObj->getEntity():
                $response = $this->tryoutObj->exportCSV($entity, $search, $page);
                break;

            case $this->tournamentObj->getEntity():
                $response = $this->tournamentObj->exportCSV($entity, $search, $page);
                break;

            case $this->tournamentOrganizationObj->getEntity():
                $response = $this->tournamentOrganizationObj->exportCSV($entity, $search, $page);
                break;

            case $this->showcaseOrganizationObj->getEntity():
                $response = $this->showcaseOrganizationObj->exportCSV($entity, $search, $page);
                break;

            case $this->showcaseOrProspectObj->getEntity():
                $response = $this->showcaseOrProspectObj->exportCSV($entity, $search, $page);
                break;

        }
        return $response;
    }

    public function getDownloadCsv(Request $request){

        $data = $request->all();

        $resultFilePath = !empty($data['filepath']) ? trim($data['filepath']) : '';

        if(empty($resultFilePath) || empty($resultFilePath)) { return abort(404); }

        $resultFilePath = public_path($resultFilePath);

        if(!file_exists($resultFilePath)) {
            return abort(404);
        }

        return response()->download("{$resultFilePath}");
    }
}