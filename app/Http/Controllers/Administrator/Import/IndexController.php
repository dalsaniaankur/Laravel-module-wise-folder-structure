<?php
namespace App\Http\Controllers\Administrator\Import;

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

	public function __construct(){

        $this->academyObj = new Academies();
        $this->teamObj = new Team();
        $this->tryoutObj = new Tryout();
        $this->tournamentObj = new Tournament();
        $this->tournamentOrganizationObj = new TournamentOrganization();
        $this->showcaseOrganizationObj = new ShowcaseOrganization();
        $this->showcaseOrProspectObj = new ShowcaseOrProspect();
    }

    public function postImportCsv(Request $request){

        $data = $request->all();
        $entity = $data['entity'];
        $response = array('success' => false);

        switch ($entity) {

            case $this->academyObj->getEntity():
                $response = $this->academyObj->importCSV($data);
                break;

            case $this->teamObj->getEntity():
                $response = $this->teamObj->importCSV($data);
                break;

            case $this->tryoutObj->getEntity():
                $response = $this->tryoutObj->importCSV($data);
                break;

            case $this->tournamentObj->getEntity():
                $response = $this->tournamentObj->importCSV($data);
                break;

            case $this->tournamentOrganizationObj->getEntity():
                $response = $this->tournamentOrganizationObj->importCSV($data);
                break;

            case $this->showcaseOrganizationObj->getEntity():
                $response = $this->showcaseOrganizationObj->importCSV($data);
                break;

            case $this->showcaseOrProspectObj->getEntity():
                $response = $this->showcaseOrProspectObj->importCSV($data);
                break;

        }
        return $response;
    }
}
