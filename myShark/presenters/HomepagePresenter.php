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
            $idPage = $pageModel->cache()->loadPageId($parameters[PageModel::ID]);
            $layout = $pageModel->cache()->loadPageLayout($idPage);
            
            foreach ($layout as &$cell) {
                $idCell = $cell['id_cell'];
                if ($pageModel->cache()->loadCellChanged($idPage, $idCell, $parameters)) {
                    $this->invalidateControl('page_cell_'.$idPage.'_'.$idCell);
                }
                $cell['slot'] = $pageModel->cache()->loadSlot($idPage, $idCell, $parameters);
            }
            
            $this->template->page = $layout;
            \Nette\Diagnostics\Debugger::dump($layout[2]);
	}

}
