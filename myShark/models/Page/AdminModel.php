<?php

class AdminModel extends \Kate\Main\Model {
 
	const ADMIN_LINK = 'admin';
	
	private $loadAdminLogin = false;
	
	public function setLoadAdminLogin() {
		$this->loadAdminLogin = true;
	}
	
	public function createLoginForm() {
		if (!$this->loadAdminLogin) {
			return false;
		}
		$form = new \Kate\Forms\LoginForm();
		return $form;
	}

	public function loginSuccess($var) {
		\Nette\Diagnostics\Debugger::dump($var);
	}
	
	
}
?>
