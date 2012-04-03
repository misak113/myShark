<?php

namespace Kate\Forms;



/**
 * Formulář pro přihlášení
 */
class LoginForm extends \Kate\Forms\Form {
	
	const PASS_MIN_LENGTH = 6;
	
	
	/**
	 * @todo preklad
	 */
	public function __construct() {
		parent::__construct('loginForm');
		$this->addText('username', 'Uživatelské jméno');
		$this->addPassword('password', 'Heslo')
				->setRequired('Zadejte prosím heslo')
				->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znamků', self::PASS_MIN_LENGTH);
		$this->addSubmit('login', 'Přihlásit');
		$this->setAction(\Kate\Main\Loader::getBaseUrl().'/'. \Kate\Main\Loader::get()->getPageModel()->getActualPath());
		
		
	}
}

?>
