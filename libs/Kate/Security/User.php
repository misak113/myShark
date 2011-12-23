<?php

namespace Kate\Security;

class User {
	
	private $user, $userData;
	
	public function __construct(\Nette\Http\User $user, $userData) {
		$this->user = $user;
		$this->userData = $userData;
	}
	
	public function getPermissions() {
		return $this->userData['userGroup']['permissions'];
	}
	
	public function setAuthenticator(\Nette\Security\IAuthenticator $handler) {
		return $this->user->setAuthenticator($handler);		
	}
	
	public function getUser() {
		return $this->user;
	}
}

?>
