<?php

namespace Kate\Forms;



/**
 * Formulář
 */
class Form extends \Nette\Forms\Form {
	
	
	public function __construct($name) {
		parent::__construct($name);
		$presenter = \Kate\Main\Loader::get()->getPresenter();
		$this->setTranslator(\Kate\Helper\Translator::get());
	}
}
?>
