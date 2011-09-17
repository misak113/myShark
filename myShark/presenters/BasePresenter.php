<?php

/**
 * myShark
 *
 * @copyright  Copyright (c) 2011 Michael Žabka
 * @package    myShark
 */


/**
 * Base class for all application presenters.
 *
 * @author     Michael Žabka
 * @package    myShark
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    // Atributy
    // Vždy načítané styly
    private $styles = array(
        array('/css/screen.css', 'screen,projection,tv', 'text/css'),
        array('/css/print.css', 'print', 'text/css'),
    );
    // Vždy načítané javascripty
    private $scripts = array(
        'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js',
        '/js/netteForms.js',
    );
    
    private $pageModel, $basePath;


    public function __construct() {
        $this->pageModel = new PageModel();
        //$this->initPresenter();
    }
    
    protected function initPresenter() {
        $this->basePath = $this->template->basePath;
        $this->initStyles();
        $this->initScripts();
        $this->initTitle();
    }
    
    private function initStyles() {
        foreach ($this->styles as &$style) {
            if (strpos('http://', $style[0]) === false) {
                $style[0] = $this->basePath.$style[0];
            }
        }
        $this->template->styles = $this->styles;
    }
    
    private function initScripts() {
        $scripts = array();
        foreach ($this->scripts as $script) {
            if (strpos('http://', $script) === false) {
                $scripts[] = $this->basePath.$script;
            } else {
                $scripts[] = $script;
            }
        }
        $this->template->scripts = $scripts;
    }
    
    private function initTitle() {
        $this->template->title = $this->pageModel->getTitle();
    }
}
