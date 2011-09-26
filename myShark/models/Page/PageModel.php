<?php

use \Kate\Main\Model;
/**
 * Obstarává veškerá data co se základního rozvržení týká
 * @author Michael Žabka
 */
class PageModel extends Model {
    const ID = 0;
    
    const VERSION = '1.0.12';
    
    const DEFAULT_PAGE_NAME_LINK = 'myShark';
    const DEFAULT_PAGE_NAME = 'Redakční systém myShark';
    
    
    
    
    private $setting, $web, $modules,  $pageId,
            // Defaultní stránka null
            $pageParameters = array(self::ID => null),
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
    
    /**
     * Načte základní info o stránkách
     * @return array names
     */
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
    
    /**
     * Načte moduly z databáze
     * @return array moduly
     */
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
     * @return string link
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
    
    
    public function loadPageId($pageLink) {
        $args = array();
        $sql = 'SELECT page.id_page
            FROM page
            LEFT JOIN phrase AS page_phrase ON (page.id_phrase = page_phrase.id_phrase)
            ';
        if ($pageLink == null) {
            $sql .= 'WHERE page.order = (SELECT MIN(page.order) FROM page) ';
        } else {
            $sql .= 'WHERE page_phrase.link = ? ';
            $args[] = $pageLink;
        }
        $sql .= 'LIMIT 1 ';
        $pageId = null;
        $q = $this->db->queryArgs($sql, $args);
        $res = $q->fetch();
        if (isset($res['id_page'])) {
            $pageId = $res['id_page'];
        }
        return $pageId;
    }
    
    /**
     * Načte layout do mrizky v poli pro dannou stranku
     * @param string $pageLink
     * @return array
     */
    public function loadPageLayout($idPage) {
        $sql = 'SELECT *
            FROM cell
            JOIN layout ON (cell.id_layout = layout.id_layout)
            JOIN phrase AS layout_phrase ON (layout.id_phrase = layout_phrase.id_phrase)
            JOIN page ON (layout.id_layout = page.id_layout)
            JOIN phrase AS page_phrase ON (page.id_phrase = page_phrase.id_phrase)
            JOIN geometry ON (geometry.id_geometry = cell.id_geometry) 
            WHERE page.id_page = ? 
            ORDER BY cell.row, cell.col ';
        $args = array($idPage);
        $q = $this->db->queryArgs($sql, $args);
        return $q->fetchAll();
    }
    
    /**
     * Vrací zda request žádá o změnu danného cell podle pageParameters
     * @param int $idCell id cellu
     * @return boolean žádá či ne
     */
    public function loadCellChanged($idPage, $idCell, $parameters) {
        unset($parameters[self::ID]);
        foreach ($parameters as $idModule => $param) {
            // zjisti z modelu danneho modulu zda neovlivnil tento cell... pokud ano vraci true a je treba nacist cell podle modulu
            $moduleModelName = $this->modules[$idModule]['label'].'ModuleModel'; // @todo loaduje z cache takze muze byt stara verze modules
            if (!class_exists($moduleModelName)) {
                throw new Kate\ClassNotFoundException('Modul "'.$moduleModelName.'" dosun nebyl implementován.');
            }
            if ($moduleModelName::get()->loadCellChanged($idPage, $idCell, $param)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Vrací informace o slotu v daném cellu a stránce del pageParameters
     * @param int $idCell id cellu
     * @return array pole s informaci o slotu
     */
    public function loadSlot($idPage, $idCell, $parameters) {
        if ($this->loadCellChanged($idPage, $idCell, $parameters)) {
            unset($parameters[self::ID]);
            foreach ($parameters as $idModule => $param) {
                // zjisti z modelu danneho modulu zda neovlivnil tento cell... pokud ano vraci true a je treba nacist cell podle modulu
                $moduleModelName = $this->modules[$idModule]['label'].'ModuleModel'; // @todo loaduje z cache takze muze byt stara verze modules
                if (!class_exists($moduleModelName)) {
                    throw new Kate\ClassNotFoundException('Modul "'.$moduleModelName.'" dosun nebyl implementován.');
                }
                $slot = $moduleModelName::get()->loadSlot($idPage, $idCell, $param);
                if ($slot) {
                    return $slot;
                }
            }
        } else {
            $sql = 'SELECT slot.*, slot_phrase.*, contentinslot.*, content.*, content_phrase.*
                FROM slotonpageincell
                LEFT JOIN slot ON (slot.id_slot = slotonpageincell.id_slot)
                LEFT JOIN phrase AS slot_phrase ON (slot_phrase.id_phrase = slot.id_phrase)
                LEFT JOIN contentinslot ON (contentinslot.id_slot = slot.id_slot)
                LEFT JOIN content ON (content.id_content = contentinslot.id_content)
                LEFT JOIN phrase AS content_phrase ON (content_phrase.id_phrase = content.id_phrase)
                WHERE slotonpageincell.id_page = ? 
                    AND slotonpageincell.id_cell = ? ';
            $args = array($idPage, $idCell);
            $q = $this->db->queryArgs($sql, $args);
            $res = $q->fetchAll();
            return $res;
        }
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
