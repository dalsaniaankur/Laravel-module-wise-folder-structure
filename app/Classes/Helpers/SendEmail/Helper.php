<?php
namespace App\Classes\Helpers\SendEmail;

class Helper
{
	protected $entityPlayerType = 1;
	protected $entityParentType = 2;
	protected $entityCoachType = 3;
	protected $entityOtherType = 4;

	protected $PlayerType = "player";
	protected $ParentType = "parent";
	protected $CoachType = "coach";
	protected $OtherType = "other";
	protected $InstructorType = "instructor";
	protected $AcademyType = "academy";
	protected $TeamType = "team";
	protected $OrganizationsType = "organizations";
	protected $CoachesNeededType = "coaches_needed";
	protected $PlayersLookingForTeamType = "players_looking_for_team";
	
	
	public function getEntityPlayerType(){

		return $this->entityPlayerType;
	}

	public function getEntityParentType(){

		return $this->entityParentType;
	}

	public function getEntityCoachType(){

		return $this->entityCoachType;
	}

	public function getEntityOtherType(){

		return $this->entityOtherType;
	}

	public function getPlayerType(){

		return $this->PlayerType;
	}
	public function getParentType(){

		return $this->ParentType;
	}
	public function getCoachType(){

		return $this->CoachType;
	}
	public function getOtherType(){

		return $this->OtherType;
	}
	public function getInstructorType(){

		return $this->InstructorType;
	}
	public function getAcademyType(){

		return $this->AcademyType;
	}
	public function getTeamType(){

		return $this->TeamType;
	}
	public function getOrganizationsType(){

		return $this->OrganizationsType;
	}
	public function getCoachesNeededType(){

		return $this->CoachesNeededType;
	}

	public function getPlayersLookingForTeamType(){

		return $this->PlayersLookingForTeamType;
	}

}