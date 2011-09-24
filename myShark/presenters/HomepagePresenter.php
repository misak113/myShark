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
            $pageModel = PageModel::get();
            
            $parameters = $pageModel->getPageParameters();
            $layout = $pageModel->cache()->loadPageLayout($parameters[PageModel::ID]);
            
	}

}
