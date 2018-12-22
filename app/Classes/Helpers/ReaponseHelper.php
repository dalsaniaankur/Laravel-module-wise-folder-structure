<?php
namespace App\Classes\Helpers;

class ReaponseHelper{

	public $_data;
	public $_pagination;
	public $_success = true;
	public $_status = 200;

	public function __construct($_data =null, $_pagination =null, $_success= true, $_status=200){
		$this->_data = $_data;
		$this->_pagination = $_pagination;
		$this->_success = $_success;
		$this->_status = $_status;
	}
}