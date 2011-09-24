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
            $this->invalidateControl('good');
            $this->invalidateControl('bad');
            //$this->validateControl();
            //var_dump($this->isControlInvalid('good'));
            //var_dump($this->isControlInvalid('bad'));
            //var_dump($this->isControlInvalid());
            $this->template->anyVariable = var_export(PageModel::get()->getPageParameters(), true);
            $x = $this->payload;
            $x = 1;
            if ($this->isAjax()) {
                //$this->terminate();
            }
	}

}
