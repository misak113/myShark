<?php

/**
 * My Application
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */


use Nette\Diagnostics\Debugger;
/**
 * Homepage presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class HomepagePresenter extends Kate\Main\Presenter
{

	public function renderDefault()
	{
            //$this->initPresenter();
            //$page = new PageModel();
            //$page->getPages();
            //Debugger::fireLog("test");
            //$this->validateControl('good');
            echo $this->isControlInvalid();
            $this->template->anyVariable = mt_rand(1, 2);
	}

}
