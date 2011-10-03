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
    
    const LINK_ERROR_404 = 'error-404';
    
    
    
    
    private $setting, $web, $modules, $languages,
            // Defaultní stránka null
            $pageParameters = array(self::ID => null),
            // Deafaultní jazyk
            $language = array(
                'shortcut' => 'cs',
                'location' => 'cz',
            );
    
    protected function __construct() {
        parent::__construct();
        $this->cache->alterDatabase();
        $this->web = $this->cache->loadWeb();
        $this->setting =$this->cache->loadSetting();
        $this->modules = $this->cache->loadModules();
        $this->languages = $this->cache->loadLanguages();
    }
    
    /**
     * Pokusí se updatovat databázi podle schéma a vložit základní prvky do databáze...
     * @todo
     */
    public function alterDatabase() {
        
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
    
    public function loadLanguages() {
        return array(
            1 => array(
                'shortcut' => 'cs',
                'location' => 'cz',
            ),
        );
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
        $sql = 'SELECT page.id_page, page_phrase.text AS page_text, page_phrase.link AS page_link, page.order AS page_order,
                layout.id_layout, layout_phrase.text AS layout_text, layout_phrase.link AS layout_link, 
                cell.*, geometry.width, geometry.width_unit, geometry.height, geometry.height_unit
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
        $res = $q->fetchAll();
        return $this->createPageLayoutFromDBFetch($res);
    }
    
    /**
     * Vrací informace o slotu v daném cellu a stránce del pageParameters
     * @param int $idCell id cellu
     * @return array pole s informaci o slotu
     */
    public function loadSlot($idPage, $idCell, $parameters) {
        unset($parameters[self::ID]);
        $res = false;
        foreach ($parameters as $idModule => $param) {
            // zjisti z modelu danneho modulu zda neovlivnil tento cell... pokud ano vraci true a je treba nacist cell podle modulu
            $moduleModelName = $this->modules[$idModule]['label'] . 'ModuleModel'; // @todo loaduje z cache takze muze byt stara verze modules
            if (!class_exists($moduleModelName)) {
                throw new Kate\ClassNotFoundException('Modul "' . $moduleModelName . '" dosun nebyl implementován.');
            }
            $res = $moduleModelName::get()->loadSlot($idPage, $idCell, $param);
            if ($res !== false) {
                // Pokud se nějak změní podle parametru slot... Priorita modulu je podle ID
                break;
            }
        }
        if ($res !== false) {
            $slot = $this->createSlotFromDBFetch($res);
            if ($slot) {
                $slot['invalidate'] = true;
            }
            return $slot;
        } else {
            $sql = 'SELECT slot.id_slot, slot_phrase.text AS slot_text, slot_phrase.link AS slot_link, 
                    contentinslot.order AS content_order, content.id_content, content.id_module, content_phrase.text AS content_text, content_phrase.link AS content_link
                FROM slotonpageincell
                LEFT JOIN slot ON (slot.id_slot = slotonpageincell.id_slot)
                LEFT JOIN phrase AS slot_phrase ON (slot_phrase.id_phrase = slot.id_phrase)
                LEFT JOIN contentinslot ON (contentinslot.id_slot = slot.id_slot)
                LEFT JOIN content ON (content.id_content = contentinslot.id_content)
                LEFT JOIN phrase AS content_phrase ON (content_phrase.id_phrase = content.id_phrase)
                WHERE slotonpageincell.id_page = ? 
                    AND slotonpageincell.id_cell = ? 
                ORDER BY content_order';
            $args = array($idPage, $idCell);
            $q = $this->db->queryArgs($sql, $args);
            $res = $q->fetchAll();
            $slot = $this->createSlotFromDBFetch($res);
            if ($slot) {
                $slot['invalidate'] = false;
            }
            return $slot;
        }
    }
    
    /**
     * Vrací odpověď daného modulu, který je vnořen do content
     * @param array $content content array
     * @return module informations
     */
    public function loadContent($content, $parameters) {
        $idModule = $content['id_module'];
        $moduleModelName = $content['moduleLabel'] . 'ModuleModel'; // @todo loaduje z cache takze muze byt stara verze modules
        if (!class_exists($moduleModelName)) {
            throw new Kate\ClassNotFoundException('Modul "' . $moduleModelName . '" dosun nebyl implementován.');
        }
        $params = isset($parameters[$idModule]) ?$parameters[$idModule] :false;
        $moduleContent = $moduleModelName::get()->loadContent($content['id_content'], $params);
        return $moduleContent;
    }

    /**
     * Předělá z databázové řádky klasické array s contents
     * @param Nette\Database\Row $res řádek databáze
     * @return array výsledný slot nebo false
     */
    private function createSlotFromDBFetch($res) {
        if (!$res || count($res) == 0 || !is_array($res)) {
            return false;
        }
        $first = reset($res);
        $slot = array(
            'id_slot' => $first->offsetGet('id_slot'),
            'text' => $first->offsetGet('slot_text'),
            'link' => $first->offsetGet('slot_link'),
        );
        $contents = array();
        foreach ($res as $row) {
            $idModule = $row->offsetGet('id_module');
            $contents[] = array(
                'id_content' => $row->offsetGet('id_content'),
                'order' => $row->offsetGet('content_order'),
                'text' => $row->offsetGet('content_text'),
                'link' => $row->offsetGet('content_link'),
                'id_module' => $idModule,
                'moduleLabel' => $this->modules[$idModule]['label'], // @todo spatne nacita z cache
            );
        }
        $slot['contents'] = $contents;
        return $slot;
    }
    
    /**
     * Přemění z řádku databáze na pole
     * @param Nette\Database\Row $res řádek databáze
     * @return pole
     */
    private function createPageLayoutFromDBFetch($res) {
        if (!$res || count($res) == 0 || !is_array($res)) {
            return false;
        }
        $first = reset($res);
        $pageLayout = array(
            'page' => array(
                'id_page' => $first->offsetGet('id_page'),
                'text' => $first->offsetGet('page_text'),
                'link' => $first->offsetGet('page_link'),
                'order' => $first->offsetGet('page_order'),
            ),
            
        );
        $cells = array();
        $layoutWidths = array();
        foreach ($res as $row) {
            $r = $row->offsetGet('row');
            $c = $row->offsetGet('col');
            if (!isset($cells[$r])) {
                $cells[$r] = array();
            }
            $cells[$r][$c] = array(
                'id_cell' => $row->offsetGet('id_cell'),
                'id_geometry' => $row->offsetGet('id_geometry'),
                'row' => $row->offsetGet('row'),
                'col' => $row->offsetGet('col'),
                'static' => $row->offsetGet('static'),
                'rowspan' => $row->offsetGet('rowspan'),
                'colspan' => $row->offsetGet('colspan'),
                'width' => $row->offsetGet('width'),
                'width_unit' => $row->offsetGet('width_unit'),
                'height' => $row->offsetGet('height'),
                'height_unit' => $row->offsetGet('height_unit'),
            );
            if (!isset($layoutWidths[$r])) {
                $layoutWidths[$r] = 0;
            }
            if ($row->offsetGet('width_unit') == 'px') {
                $layoutWidths[$r] += $row->offsetGet('width');
            }
        }
        $pageLayout['cells'] = $cells;
        $pageLayout['layout'] = array(
            'id_layout' => $first->offsetGet('id_layout'),
            'text' => $first->offsetGet('layout_text'),
            'link' => $first->offsetGet('layout_link'),
            'width' => max($layoutWidths),
        );
        return $pageLayout;
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
    
    public function getLanguage() {
        $activeIdLanguage = null;
        foreach ($this->languages as $idLanguage => $language) {
            if ($language['shortcut'] == $this->language['shortcut']) {
                $activeIdLanguage = $idLanguage;
                if ($language['location'] == $this->language['location']) {
                    $activeIdLanguage = $idLanguage;
                }
            }
        }
        return $activeIdLanguage;
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
