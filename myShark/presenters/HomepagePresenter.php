<?php

/**
 * myShark RS
 *
 * @copyright  Copyright (c) 2011 Michael Žabka
 * @package    myShark
 */
use Nette\Diagnostics\Debugger,
        Kate\Main\Loader;

/**
 * Homepage presenter.
 *
 * @author     Michael Žabka
 * @package    myShark
 */
class HomepagePresenter extends Kate\Main\Presenter {

    /**
     * Hlavní render pro defaultní stránku
     */
    public function renderDefault() {
        $pageModel = PageModel::get();
        $userModel = UserModel::get();
        
        $userModel->logUser();
        if (!$userModel->getUser()->isAllowed('web', 'display')) {
            $this->error403();
            return;
        }
        
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
		
		\Kate\Main\Hook::get()->process();
		
		Kate\Helper\LogService::realtimeDebug($this->getUser()->getIdentity()->getData());
    }
	
	
	
    
    /**
     * Vrací stránku s načtenými buňky do page
     * @param array $page vstupní stránka
     * @return array stránka se sloty
     */
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
                if ($slot) {
                    if ($slot['invalidate'] === true) {
                        $this->invalidateControl('cell-' . $idCell);
                    }
                    // Načte obsahy modulů
                    foreach ($slot['contents'] as &$content) {
                        $content['moduleContent'] = $pageModel->cache()->loadContent($content, $parameters);
                        // Načte styly pro moduly
                        $this->styles[$content['moduleLabel']] = array(
                            '/' . Loader::CSS_DIR . '/' . Loader::MODULES_DIR . '/' . $content['moduleLabel'] . '.css', 
                            'screen,projection,tv', 
                            'text/css'
                        );
                    }
                }
                $cell['slot'] = $slot;
            }
        }
        return $page;
    }
    
    /**
     * Načte základní náležitosti pro tamplate
     * @param array $page stránka
     */
    private function initDefault($page) {
        if (!isset($this->template->page)) {
            $this->template->page = $page;
        }
        $this->initScripts();
        $this->initStyles();
    }
	
	
	
	
	

    /**
     * Při zavolání nastaví na vykreslení error 404 či 500 pokud nenajde 404
	 * @internal !Je třeba psát return s touto funkcí, aby se nepřerenderoval 404
     */
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
    
    /**
     * Nastaví vykreslování na error 500 stránku... nefunkční server
     */
    public function error500() {
        $this->getHttpResponse()->setCode(Nette\Http\Response::S500_INTERNAL_SERVER_ERROR);
        
        $this->setView('error');
        $this->template->errorNumber = 500;
        $this->template->errorMessage = 'Na serveru nastala chyba. Omlouváme se, zkuste znovu načíst později nebo přejít na jinou stránku.'; // @todo prelozit ze statických překladačů
    }
    
    /**
     * Nastaví vykreslování na error 403 stránku... Nedostatečná práva
     */
    public function error403() {
        $this->getHttpResponse()->setCode(Nette\Http\Response::S403_FORBIDDEN);
        
        $this->setView('error');
        $this->template->errorNumber = 403;
        $this->template->errorMessage = 'Pro zobrazení této stránky nemáte dostatečná práva'; // @todo prelozit ze statických překladačů
    }

}
