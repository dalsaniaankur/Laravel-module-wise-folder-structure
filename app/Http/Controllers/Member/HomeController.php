<?php
namespace App\Http\Controllers\Member;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Classes\Models\TournamentOrganization\TournamentOrganization;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrProspect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $TournamentOrganizationObj;
    protected $InstructorsObj;
    protected $AcademiesObj;
    protected $TeamObj;
    protected $CoachesNeededObj;
    protected $LookupForPlayerExperienceObj;
    protected $showcaseOrganizationObj;
    protected $ShowcaseOrProspect;

    public function __construct()
    {
        $this->middleware('member');
        $this->TournamentOrganizationObj = new \App\Classes\Models\TournamentOrganization\TournamentOrganization();
        $this->AcademiesObj = new \App\Classes\Models\Academies\Academies();
        $this->TeamObj = new \App\Classes\Models\Team\Team();
        $this->CoachesNeededObj = new \App\Classes\Models\CoachesNeeded\CoachesNeeded();
        $this->LookupForPlayerExperienceObj = new \App\Classes\Models\LookupForPlayerExperience\LookupForPlayerExperience();
        $this->showcaseOrganizationObj = new \App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization();
        $this->ShowcaseOrProspect = new ShowcaseOrProspect();
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $totalAcademies=0;
        $totalTeam=0;
        $totalTournamentOrganization=0;
        $totalShowcaseOrganization=0;
        $totalShowCases=0;

        $submitted_by_id = $this->TournamentOrganizationObj->isLoginMember();
        $totalAcademies = $this->AcademiesObj->getAcademyWidget($submitted_by_id);
        $totalTeam = $this->TeamObj->getTeamWidget($submitted_by_id);
        $totalTournamentOrganization = $this->TournamentOrganizationObj->getTournamentOrganizationWidget($submitted_by_id);
        $totalShowcaseOrganization = $this->showcaseOrganizationObj->getShowcaseOrganizationWidget($submitted_by_id);
        $totalShowCases = $this->ShowcaseOrProspect->getShowcaseOrProspectWidget($submitted_by_id);

        return view('member.home',compact('totalAcademies','totalTeam','totalTournamentOrganization','totalShowcaseOrganization','totalShowCases'));
    }
}
