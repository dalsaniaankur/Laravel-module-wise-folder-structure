<?php
namespace App\Classes\Helpers;

class Helper
{
	public function getConfigPerPageRecord()
	{
		$per_page=\Config::get('user-configuration.default_per_page_record.value');
		return $per_page;
	}
}


