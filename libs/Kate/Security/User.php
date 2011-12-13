<?php

namespace Kate\Security;

class User extends \Nette\Http\User {
	
	private $user, $userData;
	
	public function __construct(\Nette\Http\User $user, $userData) {
		$this->user = $user;
		$this->userData = $userData;
	}
	
	public function getPermissions() {
		return $this->userData['userGroup']['permissions'];
	}
}

?>
