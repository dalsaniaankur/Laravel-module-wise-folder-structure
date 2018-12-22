<?php
namespace App\Http\Controllers\Administrator;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Classes\Models\Members\Members;
use App\Classes\Models\User\User;
use App\Classes\Models\Event\Event;
use App\Classes\Models\Instructors\Instructors;
use App\Classes\Models\Academies\Academies;
use App\Classes\Models\Team\Team;
use App\Classes\Models\TournamentOrganization\TournamentOrganization;
use App\Classes\Models\CoachesNeeded\CoachesNeeded;
use App\Classes\Models\LookupForPlayerExperience\LookupForPlayerExperience;
use App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('administrator');
        $this->UserObj = new \App\Classes\Models\User\User();
        $this->MemberObj = new \App\Classes\Models\Members\Members();
        $this->EventObj = new \App\Classes\Models\Event\Event();
        $this->InstructorsObj = new \App\Classes\Models\Instructors\Instructors();
        $this->AcademiesObj = new \App\Classes\Models\Academies\Academies();
        $this->TeamObj = new \App\Classes\Models\Team\Team();
        $this->TournamentOrganizationObj = new \App\Classes\Models\TournamentOrganization\TournamentOrganization();
        $this->CoachesNeededObj = new \App\Classes\Models\CoachesNeeded\CoachesNeeded();
        $this->LookupForPlayerExperienceObj = new \App\Classes\Models\LookupForPlayerExperience\LookupForPlayerExperience();
        $this->showcaseOrganizationObj = new \App\Classes\Models\ShowcaseOrganization\ShowcaseOrganization();
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		
		$totalUsers=0;
        $totalMembers=0;
        $totalEvents=0;
        $totalInstructors=0;
        $totalAcademies=0;
        $totalTeam=0;
        $totalTournamentOrganization=0;
        $totalCoachesNeeded=0;
        $totalLookupForPlayerExperience=0;
        $totalShowcaseOrganization=0;
        
        $totalUsers = $this->UserObj->getUserWidget();
        $totalMembers = $this->MemberObj->getMemberWidget();
        $totalEvents = $this->EventObj->getEventWidget();
        $totalInstructors = $this->InstructorsObj->getInstructorWidget();
        $totalAcademies = $this->AcademiesObj->getAcademyWidget();
        $totalTeam = $this->TeamObj->getTeamWidget();
        $totalTournamentOrganization = $this->TournamentOrganizationObj->getTournamentOrganizationWidget();
        $totalCoachesNeeded = $this->CoachesNeededObj->getCoachesNeededWidget();
        $totalLookupForPlayerExperience = $this->LookupForPlayerExperienceObj->getLookupForPlayerExperienceWidget();
        $totalShowcaseOrganization = $this->showcaseOrganizationObj->getShowcaseOrganizationWidget();

       
		return view('administrator.home',compact('totalUsers','totalMembers','totalEvents','totalInstructors','totalAcademies','totalTTeam','totalTournamentOrganization','totalCoachesNeeded','totalLookupForPlayerExperience','totalShowcaseOrganization'));
    }
}
