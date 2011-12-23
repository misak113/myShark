<?php

class AdminModel extends \Kate\Main\Model implements \Nette\Security\IAuthenticator {
 
	const ADMIN_LINK = 'admin';
	
	private $loadAdminLogin = false;
	
	public function setLoadAdminLogin() {
		$this->loadAdminLogin = true;
	}
	public function getLoadAdminLogin() {
		return $this->loadAdminLogin;
	}
	
	public function createLoginForm() {
		if (!$this->loadAdminLogin) {
			return false;
		}
		$form = new \Kate\Forms\LoginForm();
		$this->controlLoginForm($form);
		return $form;
	}
	
	public function controlLoginForm(\Kate\Forms\LoginForm $form) {
		if ($form->isValid()) {
			if (!$this->loginByForm($form)) {
				$form->addError('Zadáno špatné přihlašovací jméno nebo heslo');
			}
		}
	}

	/**
	 * Pokusí se přihlásit z vyplněného formuláře
	 * @param \Kate\Forms\LoginForm $form 
	 */
	private function loginByForm(\Kate\Forms\LoginForm $form) {
		try {
			$values = $form->getValues();
			$username = $values['username'];
			$password = $values['password'];
			$this->loginUser($username, $password);
			// @todo přidat hook na přesměrování na aktualní adresu bez ../admin
			\Kate\Main\Hook::get()->redirect(\Kate\Main\Hook::UP);
			return true;
		} catch (\Nette\Security\AuthenticationException $e) {
			return false;
		}
	}
	
	public function loginUser($username, $password) {
		$user = UserModel::get()->getUser();
		$user->setAuthenticator($this);
		$user->login($username, $password);
	}

	public function authenticate(array $credentials) {
		list($username, $password) = $credentials;
		$userFetch = $this->db->table('user')
				->where('username', $username)
				->where('password', sha1($password))
				->limit(1)->fetch();
		if (!$userFetch) {
			throw new \Nette\Security\AuthenticationException();
		}
		UserModel::get()->adminUserLogged($userFetch['id_user']);
		$userData = UserModel::get()->loadUserData();
		$perms = $userData['userGroup']['permissions'];
		$identity = new Nette\Security\Identity($userData['id_user'], $userData['id_userGroup'], $userData);
		return $identity;
	}
	
	
}
?>
