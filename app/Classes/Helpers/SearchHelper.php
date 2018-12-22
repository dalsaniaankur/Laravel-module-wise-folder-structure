<?php
namespace App\Classes\Helpers;

class SearchHelper{
    
	public $_page = 1;
	public $_sortedOrder = 'ASC';
	public $_sortedBy = 'name';
	public $_searchData = [];

	public function __construct($_sortedBy = 'name', $_sortedOrder = 'ASC', $_page =1, $_searchData = [] ){
		$this->_sortedBy = $_sortedBy;
		$this->_sortedOrder = $_sortedOrder;
		$this->_page = $_page;
		$this->_searchData = $_searchData;
	}
}