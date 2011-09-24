<?php

use \Kate\Main\Model;
/**
 * Obstarává veškerá data co se základního rozvržení týká
 * @author Michael Žabka
 */
class PageModel extends Model {
    const ID = 0;
    
    const VERSION = '1.0.8';
    
    const DEFAULT_PAGE_NAME_LINK = 'myShark';
    const DEFAULT_PAGE_NAME = 'Redakční systém myShark';
    
    
    
    
    private $setting, $pageName, $pageNameLink, $modules, $pageParameters, 
            // Deafaultní jazyk
            $language = array(
                'shortcut' => 'cs',
                'location' => 'cz',
            );
    
    protected function __construct() {
        parent::__construct();
        $this->loadPage();
        $this->loadSetting();
        $this->loadModules();
    }
    
    private function loadPage() {
        $row = $this->db->table('page')
                ->select('phrase.text, phrase.link')
                ->order('`order` ASC')
                ->limit(1)
                ->fetch();
        if ($row) {
            $this->pageName = $row['text'];
            $this->pageNameLink = $row['link'];
        } else {
            $this->pageName = self::DEFAULT_PAGE_NAME.' '.self::VERSION;
            $this->pageNameLink = self::DEFAULT_PAGE_NAME_LINK;
        }
    }
    
    private function loadSetting() {
        $this->setting = array();
    }
    
    private function loadModules() {
        $this->modules = array();
        $q = $this->db->table('module')
                ->select('module.*, phrase.text, phrase.link');
        while ($row = $q->fetch()) {
            $this->modules[$row['id_module']] = array(
                'id_module_parent' => $row['id_module_parent'],
                'label' => $row['label'],
                'text' => $row['text'],
                'link' => $row['link'], // @todo 
            );
        }
    }
    
    /**
     * Vrací z databáze titulek pro stránku
     * @return string titulek
     */
    public function getTitle() {
        return $this->getPageName();
    }
    
    /**
     * Vrací link z názvu aktuální stránky, která je aktivní pro myShark... např.: 'janarandakova.cz'
     * Toto získává z databáze z tabulky setting
     * @todo napojit na databázi
     * @return link
     */
    public function getPageNameLink() {
        return $this->pageNameLink;
    }
    
    /**
     * Vrací aktuální název stránky
     * @return string název
     */
    public function getPageName() {
        return $this->pageName;
    }
    
    /**
     * Vrací nastavení podle klíče z tabulky setting
     * @param string $key klíč
     * @return string hodnota
     */
    public function getSetting($key = null) {
        if ($this->setting == null) {
            /** @todo load setting from database */
        }
        if ($key === null) {
            return $this->setting;
        } else {
            if (key_exists($key, $this->setting)) {
                return $this->setting[$key];
            } else {
                return false;
            }
        }
    }
    
    public function setLanguage($shortcut, $location) {
        $this->language['shortcut'] = $shortcut;
        $this->language['location'] = $location;
    }
    
    public function setPageParameters($parameters) {
        $this->pageParameters = $parameters;
    }
    
    public function getPageParameters() {
        return $this->pageParameters;
    }
    
    
    public function getModuleLinks() {
        $moduleLinks = array();
        foreach ($this->modules as $id => $module) {
            $moduleLinks[$id] = $module['link'];
        }
        return $moduleLinks;
    }
}
?>
