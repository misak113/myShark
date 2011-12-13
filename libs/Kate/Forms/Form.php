<?php

namespace Kate\Forms;



/**
 * Formulář
 */
class Form extends \Nette\Forms\Form {
	
	
	public function __construct() {
		parent::__construct();
		$this->onSuccess[] = callback(\Kate\Main\Loader::get()->getPresenter(), 'formSuccess');
		
	}
}
?>
