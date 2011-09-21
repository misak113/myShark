<?php

use \Kate\Main\Model;
/**
 * Obstarává veškerá data co se základního rozvržení týká
 * @author Michael Žabka
 */
class PageModel extends Model {
    
    const VERSION = '1.0.8';
    
    const DEFAULT_PAGE_NAME_LINK = 'myShark';
    const DEFAULT_PAGE_NAME = 'Redakční systém myShark';
    
    
    private $setting, $pageName, $pageNameLink;
    
    public function __construct() {
        parent::__construct();
        $this->loadPage();
        $this->loadSetting();
    }
    
    private function loadPage() {
        $row = $this->db->table('page')
                ->order('`order` ASC')
                ->limit(1)->fetch();
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
    
    public function getPageName() {
        return $this->pageName;
    }
    
    /**
     * Vrací nastavení podle klíče z tabulky setting
     * @param string $key klíč
     * @return string hodnota
     */
    public function getSetting($key = false) {
        if ($this->setting == null) {
            /** @todo load setting from database */
        }
        if ($key === false) {
            return $this->setting;
        } else {
            if (key_exists($key, $this->setting)) {
                return $this->setting[$key];
            } else {
                return false;
            }
        }
    }
}
?>
