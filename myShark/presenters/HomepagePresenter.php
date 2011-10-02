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
class HomepagePresenter extends Kate\Main\Presenter {

    public function renderDefault() {
        $pageModel = PageModel::get();

        $parameters = $pageModel->getPageParameters();

        // Naloaduje stránku
        $idPage = $pageModel->cache()->loadPageId($parameters[PageModel::ID]);
        $page = $pageModel->cache()->loadPageLayout($idPage);

        // Pokud stránka neexistuje, naloaduje Error 404
        if ($page === false) {
            $this->error404();
            return;
        }

        $page = $this->loadPageCells($page);

        
        // Nastavý proměnné pro tamplate
        $this->initDefault($page);
    }
    
    private function loadPageCells($page) {
        if (!isset($page['cells']) || !isset($page['page']) || !isset($page['layout'])) {
            return false;
        }
        $pageModel = PageModel::get();

        $parameters = $pageModel->getPageParameters();
        $idPage = $page['page']['id_page'];
        
        // Načte správné obsahy do jednotlivých buněk
        foreach ($page['cells'] as &$row) {
            foreach ($row as &$cell) {
                $idCell = $cell['id_cell'];
                // Načte sloty
                $slot = $pageModel->cache()->loadSlot($idPage, $idCell, $parameters);
                if ($slot['invalidate'] === true) {
                    $this->invalidateControl('cell-' . $idCell);
                }
                // Načte obsahy modulů
                foreach ($slot['contents'] as &$content) {
                    $content['moduleContent'] = $pageModel->cache()->loadContent($content, $parameters);
                }
                $cell['slot'] = $slot;
            }
        }
        return $page;
    }
    
    private function initDefault($page) {
        if (!isset($this->template->page)) {
            $this->template->page = $page;
        }
    }

    public function error404() {
        $pageModel = PageModel::get();
        $idPage = $pageModel->cache()->loadPageId(PageModel::LINK_ERROR_404);
        $page = $pageModel->cache()->loadPageLayout($idPage);
        $this->getHttpResponse()->setCode(Nette\Http\Response::S404_NOT_FOUND);
        
        $this->setView('default');
        $page = $this->loadPageCells($page);
        
        // Pokud ani error stránka neexistuje resp. buňky skončí činnost
        if (!$page) {
            $this->error500();
            return;
        }
        
        $this->initDefault($page);
    }
    
    public function error500() {
        $this->getHttpResponse()->setCode(Nette\Http\Response::S500_INTERNAL_SERVER_ERROR);
        
        $this->setView('error');
        $this->template->errorNumber = 500;
        $this->template->errorMessage = 'Na serveru nastala chyba. Omlouváme se, zkuste znovu načíst později nebo přejít na jinou stránku.';
    }

}
