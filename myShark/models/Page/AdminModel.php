<?php

class AdminModel extends \Kate\Main\Model {
 
	const ADMIN_LINK = 'admin';
	
	private $loadAdmin = false;
	
	public function setLoadAdmin() {
		$this->loadAdmin = true;
	}
	
	public function isLoadAdmin() {
		return $this->loadAdmin;
	}
	
}
?>
