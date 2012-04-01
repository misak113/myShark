<?php

/**
 * Kate Presenter
 *
 * @copyright  Copyright (c) 2011 Michael Žabka
 * @package    Kate
 */

namespace Kate\Main;

use Kate;

/**
 * Base class for all application presenters.
 *
 * @author     Michael Žabka
 * @package    Kate
 */
abstract class Presenter extends \Nette\Application\UI\Presenter {
    
    const FLASH_ERROR = 'error';
    const FLASH_INFO = 'info';

    // Atributy
    // Vždy načítané styly
    protected $styles = array(
	array('/css/screen.css', 'screen,projection,tv', 'text/css'),
	array('/css/print.css', 'print', 'text/css'),
	array('/css/libs/jquery-ui/dark-hive/jquery-ui-1.8.16.custom.css', 'screen,projection,tv', 'text/css'),
    );
    // Vždy načítané javascripty
    protected $scripts = array(
	//'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js',
	//'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js',
	//'http://documentcloud.github.com/underscore/underscore-min.js',
	'/js/libs/underscore.min.js',
	'/js/libs/underscore.string.min.js',
	'/js/libs/jquery-1.7.1.min.js',
	'/js/libs/jquery-ui-1.8.16.custom.min.js',
	'/js/libs/netteForms.js',
	'/js/libs/jquery.json-2.3.min.js',
    );
    protected $jsVariables = array();
    protected $baseUrl;
    protected $appName;

    public function __construct() {
	$context = \Nette\Environment::getContext();
	parent::__construct($context);
	new \Kate\External\__; // Načtení underscore knihovny
	
	$this->appName = 'kate';
    }

    protected function setAppName($appName) {
	$this->appName = $appName;
    }

    /**
     * přepisuje kuli inicializaci presenteru
     * @param string $method metoda
     * @param array $params parametry 
     */
    protected function tryCall($method, array $params) {
	$this->initPresenter();
	parent::tryCall($method, $params);
    }

    private function initPresenter() {
	$this->baseUrl = Loader::getBaseUrl();
	$this->initTitle();
	$this->template->setTranslator(\Kate\Helper\Translator::get());
    }

    /**
     * @todo spatne funguje http include
     */
    protected function initStyles() {
	$styles = $this->styles;
	foreach ($styles as &$style) {
	    if (substr($style[0], 0, 7) !== 'http://') {
		$style[0] = $this->baseUrl . $style[0];
	    }
	}
	$this->template->styles = $styles;
    }

    protected function initScripts() {
	$scripts = array();
	foreach ($this->scripts as $script) {
	    if (substr($script, 0, 7) !== 'http://') {
		$scripts[] = $this->baseUrl . $script;
	    } else {
		$scripts[] = $script;
	    }
	}
	$this->template->jsVariables = $this->jsVariables;
	$this->template->scripts = $scripts;
    }

    private function initTitle() {
	$this->template->title = Loader::getPageModel()->getTitle();
    }

    /**
     * Přidá JS script do zobrazování
     * @param string $path cesta ke scriptu
     */
    public function addScript($path) {
	if (!preg_match('~^.+\.js$~', $path)) {
	    $path = '/js/'.$this->appName.'/' . $path . '.js';
	}
	if (!in_array($path, $this->scripts)) {
	    $this->scripts[] = $path;
	}
    }

    public function addJsVariable($var, $value) {
	if (!preg_match('~\.~', $var)) {
	    $var = 'var ' . $var;
	}
	$this->jsVariables[$var] = str_replace("'", "\\'", $value);
    }

    /**
     * Přidá styl do zobrazování
     * @param string $path cesta ke stylu
     * @param string $media media atribut
     * $param string $type typ stylu
     */
    public function addStyle($path, $media = 'screen,projection,tv', $type = 'text/css') {
	if (!preg_match('~^.+\.css$~', $path)) {
	    $path = '/css/'.$this->appName.'/' . $path . '.css';
	}
	$style = array($path, $media, $type);
	if (!in_array($style, $this->styles)) {
	    $this->styles[] = $style;
	}
    }

}
