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
            $pageLayout = $pageModel->cache()->loadPageLayout($idPage);
            
            foreach ($pageLayout['cells'] as &$cell) {
                $idCell = $cell['id_cell'];
                $slot = $pageModel->cache()->loadSlot($idPage, $idCell, $parameters);
                if ($slot['invalidate'] === true) {
                    $this->invalidateControl('page_cell_'.$idPage.'_'.$idCell);
                }
                foreach ($slot['contents'] as &$content) {
                    $content['module'] = $pageModel->cache()->loadContent($content);
                }
                $cell['slot'] = $slot;
            }
            
            $this->template->page = $pageLayout;
            var_export($pageLayout);
            //\Nette\Diagnostics\Debugger::dump($pageLayout);
	}

}
