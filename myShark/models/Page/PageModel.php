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
    
    
    
    
    private $setting, $web, $modules, $pageParameters, 
            // Deafaultní jazyk
            $language = array(
                'shortcut' => 'cs',
                'location' => 'cz',
            );
    
    protected function __construct() {
        parent::__construct();
        $this->web = $this->cache->loadWeb();
        $this->setting =$this->cache->loadSetting();
        $this->modules = $this->cache->loadModules();
    }
    
    public function loadWeb() {
        $web = array();
        $row = $this->db->table('page')
                ->select('phrase.text, phrase.link')
                ->order('`order` ASC')
                ->limit(1)
                ->fetch();
        if ($row) {
            $web['name'] = $row['text'];
            $web['nameLink'] = $row['link'];
        } else {
            $web['name'] = self::DEFAULT_PAGE_NAME.' '.self::VERSION;
            $web['nameLink'] = self::DEFAULT_PAGE_NAME_LINK;
        }
        return $web;
    }
    
    public function loadSetting() {
        return array();
    }
    
    public function loadModules() {
        $modules = array();
        $q = $this->db->table('module')
                ->select('module.*, phrase.text, phrase.link');
        while ($row = $q->fetch()) {
            $modules[$row['id_module']] = array(
                'id_module_parent' => $row['id_module_parent'],
                'label' => $row['label'],
                'text' => $row['text'],
                'link' => $row['link'],
            );
        }
        return $modules;
    }
    
    /**
     * Vrací z databáze titulek pro stránku
     * @return string titulek
     */
    public function getTitle() {
        return $this->getWebName();
    }
    
    /**
     * Vrací link z názvu aktuální stránky, která je aktivní pro myShark... např.: 'janarandakova.cz'
     * Toto získává z databáze z tabulky setting
     * @todo napojit na databázi
     * @return link
     */
    public function getWebNameLink() {
        return $this->web['nameLink'];
    }
    
    /**
     * Vrací aktuální název stránky
     * @return string název
     */
    public function getWebName() {
        return $this->web['name'];
    }
    
    
    public function loadPageLayout($pageLink = null) {
        $sql = 'SELECT *
            FROM cell
            LEFT JOIN layout ON (cell.id_layout = layout.id_layout)
            LEFT JOIN phrase AS layout_phrase ON (layout.id_phrase = layout_phrase.id_phrase)
            LEFT JOIN page ON (layout.id_layout = page.id_layout)
            LEFT JOIN phrase AS page_phrase ON (page.id_phrase = page_phrase.id_phrase)
            LEFT JOIN geometry ON (geometry.id_geometry = cell.id_geometry) ';
        /*$q = $this->db->table('layout')
                ->select('cell.id_layout, layout.id_layout, page.id_layout');
        if ($pageLink == null) {
            $q = $q->where('page.order', '1');
        } else {
            $q = $q->where('phrase.link', $pageLink);
        }*/
        if ($pageLink == null) {
            $sql .= 'WHERE page.order = 1 ';
        } else {
            $sql .= 'WHERE page_phrase.link = \''.$pageLink.'\' ';
        }
        $sql .= 'ORDER BY cell.row, cell.col ';
        $q = $this->db->query($sql);
        $cells = array();
        while ($row = $q->fetch()) {
            $cells[$row['row']][] = $row;
        }
        return $cells;
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
    
    /**
     * Vrací linky všech modulů v poli pod klíči id_module
     * @return array links of modules
     */
    public function getModuleLinks() {
        $moduleLinks = array();
        foreach ($this->modules as $id => $module) {
            $moduleLinks[$id] = $module['link'];
        }
        return $moduleLinks;
    }
}
?>
