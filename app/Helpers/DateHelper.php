<?php
namespace App\Helpers;
use Carbon\Carbon;

class DateHelper 
{
  public function dateFormat($date,$date_formate)
  {
	  if($date_formate=='formate-1'){
		 
		 return \Carbon\Carbon::parse($date)->format('d, F Y'); //20,December 2017
	  }

	  if($date_formate=='formate-2'){
		 
		 return \Carbon\Carbon::parse($date)->format('d/m/Y h:i:s A'); //  19/05/2018 12:44:11 PM
	  }

	  if($date_formate=='formate-3'){
		 
		 return \Carbon\Carbon::parse($date)->format('m/d/Y'); //  9/30/2014
	  }

	  if($date_formate=='formate-4'){
		 
		 return \Carbon\Carbon::parse($date)->format('m/d/Y h:i:s A'); //  9/28/2017 7:18:39 AM
	  }


	  return $date;
  }


}


