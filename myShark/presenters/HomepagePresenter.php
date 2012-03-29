<?php

/**
 * myShark RS
 *
 * @copyright  Copyright (c) 2011 Michael Žabka
 * @package    myShark
 */
use Nette\Diagnostics\Debugger,
    Kate\Main\Loader,
    Kate\Helper\Translator,
    Kate\Helper as T;

/**
 * Homepage presenter.
 *
 * @author     Michael Žabka
 * @package    myShark
 */
class HomepagePresenter extends Kate\Main\Presenter {

    private $setting = array();

    public function __construct() {
	parent::__construct();
	$this->setAppName(PageModel::MYSHARK_DIR);
    }
    /**
     * Hlavní render pro defaultní stránku
     */
    public function renderDefault() {
	
	$request = \Nette\Environment::getHttpRequest();
	if ($request->isPost()) {
	    $moduleModelName = $request->getPost('module') . 'ModuleModel';
	    if ($request->getPost('module') && $moduleModelName && class_exists($moduleModelName)) {
		$moduleModelName::get()->postMethod($request->getPost('method'), $request->getPost());
	    }
	}
	
	$this->addJsVariable('jQuery.myshark.baseUrl', Loader::getBaseUrl());
	$this->addScript('default/myshark');
	$this->addScript('shorthands');
	$this->addStyle('default/myshark');

	$pageModel = PageModel::get();
	$userModel = UserModel::get();

	// má právo web zobrazit
	$userModel->logUser();
	if (!$userModel->getUser()->isAllowed('web', 'display')) {
	    $this->error403();
	    return;
	}

	// Animovaný web
	if ($userModel->getUser()->isAllowed('web', 'animate') && !$this->isAjax()) {
	    $path = $pageModel->getActualRealPath();
	    if ($path != '') {
		$this->redirectUrl(Loader::getBaseUrl() . '#' . $path, 301);
	    }
	    $this->addScript('default/animate');
	    $this->addStyle('default/animate');
	    $this->setting['loadingBox'] = true;
	}
	if (!$userModel->getUser()->isAllowed('web', 'animate') && $this->isAjax()) {
	    // Pokud je v animovaném webu a odhlasi se tak se ma presmerovat
	    $this->error401(_t('Po delší neaktivitě jste byl odhlášen'));
	    return;
	}

	// parametry
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

	_d($this->getUser()->getIdentity()->getData());
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
		    // Načte obsahy modulů
		    foreach ($slot['contents'] as &$content) {
			$content['moduleContent'] = $pageModel->cache()->loadContent($content, $parameters);
			// Načte styly pro moduly
			$this->addStyle(Loader::DEFAULT_DIR . '/' . Loader::MODULES_DIR . '/' . $content['moduleLabel']);
			$this->addScript(Loader::DEFAULT_DIR . '/' . Loader::MODULES_DIR . '/' . $content['moduleLabel']);
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
	$this->template->setting = $this->setting;
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
	$this->template->errorMessage = _t('Na serveru nastala chyba. Omlouváme se, zkuste znovu načíst později nebo přejít na jinou stránku.'); // @todo prelozit ze statických překladačů
    }

    /**
     * Nastaví vykreslování na error 403 stránku... Nedostatečná práva
     */
    public function error403() {
	$this->getHttpResponse()->setCode(Nette\Http\Response::S403_FORBIDDEN);

	$this->setView('error');
	$this->template->errorNumber = 403;
	$this->template->errorMessage = _t('Pro zobrazení této stránky nemáte dostatečná práva'); // @todo prelozit ze statických překladačů
    }
    
    /**
     * Nastaví vykreslování na error 401 stránku... Neautorizovaný přístup
     */
    public function error401($message = false) {
	$this->getHttpResponse()->setCode(Nette\Http\Response::S401_UNAUTHORIZED);

	if ($message === false) {
	    $message = _t('Pro zobrazení této stránky musíte být autorizován');
	}
	
	$this->setView('error');
	$this->template->errorNumber = 403;
	$this->template->errorMessage = $message;
    }

}
