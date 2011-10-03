<?php

/**
 * myShark
 *
 * @copyright  Copyright (c) 2011 Michael Žabka
 * @package    myShark
 */

namespace Kate\Main;
use Kate;

/**
 * Base class for all application presenters.
 *
 * @author     Michael Žabka
 * @package    myShark
 */
abstract class Presenter extends \Nette\Application\UI\Presenter
{
    // Atributy
    // Vždy načítané styly
    protected $styles = array(
        array('/css/screen.css', 'screen,projection,tv', 'text/css'),
        array('/css/print.css', 'print', 'text/css'),
    );
    // Vždy načítané javascripty
    protected $scripts = array(
        'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js',
        '/js/libs/netteForms.js',
    );
    
    protected $baseUrl;
    

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * přepisuje kuli inicializaci presenteru
     * @param string $method metoda
     * @param array $params parametry 
     */
    protected function tryCall($method, array $params) {
        $this->initPresenter();
        $func = __FUNCTION__;
        parent::$func($method, $params);
    }
    
    private function initPresenter() {
        $this->baseUrl = Loader::getBaseUrl();
        $this->initTitle();
    }
    
    /**
     * @todo spatne funguje http include
     */
    protected function initStyles() {
        $styles = $this->styles;
        foreach ($styles as &$style) {
            if (substr($style[0], 0, 7) !== 'http://') {
                $style[0] = $this->baseUrl.$style[0];
            }
        }
        $this->template->styles = $styles;
    }
    
    protected function initScripts() {
        $scripts = array();
        foreach ($this->scripts as $script) {
            if (substr($script, 0, 7) !== 'http://') {
                $scripts[] = $this->baseUrl.$script;
            } else {
                $scripts[] = $script;
            }
        }
        $this->template->scripts = $scripts;
    }
    
    private function initTitle() {
        $this->template->title = Loader::getPageModel()->getTitle();
    }
    
    
}
