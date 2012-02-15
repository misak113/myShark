<?php

use Kate\Main\Model,
    Kate\Main\Loader;
/**
 * Obstarává veškerá data co se základního rozvržení týká
 * @author Michael Žabka
 */
class PageModel extends Model {
    const ID = 0;
    
    const VERSION = '1.0.21';
    
    const DEFAULT_PAGE_NAME_LINK = 'myShark';
    const DEFAULT_PAGE_NAME = 'Redakční systém myShark';
    
    const LINK_ERROR_404 = 'error-404';
    
    
    
    
    private $setting = null, $web = array(), $modules = array(), $languages = null,
            // Defaultní stránka null
            $pageParameters = array(self::ID => null),
            $language = null;
            // Deafaultní jazyk
            private static $defaultLanguage = array(
                'id_language' => 1,
                'shortcut' => 'cs',
                'location' => 'cz',
                'title' => 'Česky',
            ),
            $defaultLayouts = array(
                1 => array('text' => 'Standardní', 'cells' => array(
                        array('width' => 1000, 'height' => 300, 'row' => 1, 'col' => 1, 'static' => true, 'rowspan' => 1, 'colspan' => 2),
                        array('width' => 300, 'height' => null, 'row' => 2, 'col' => 1, 'static' => true, 'rowspan' => 1, 'colspan' => 1),
                        array('width' => 700, 'height' => null, 'row' => 2, 'col' => 2, 'static' => false, 'rowspan' => 1, 'colspan' => 1),
                        array('width' => 1000, 'height' => 40, 'row' => 3, 'col' => 1, 'static' => true, 'rowspan' => 1, 'colspan' => 2),
                    ),
                ),
            );
            
    
    
    
    // Pole pro čas expirace při cachování jednotlivých funkcí ve třídách
    private static $cacheExpirations = array(
        //Classes
        'PageModel' => array(
            //methods
            'alterDatabase' => '+7 days',
            'loadWeb' => '+20 minutes',
            'loadSetting' => '+20 minutes',
            'loadLanguages' => '+20 minutes',
            'loadContent' => '+20 minutes',
            'loadModules' => '+20 minutes',
            'loadPageId' => '+20 minutes',
            'loadPageLayout' => '+20 minutes',
            'loadSlot' => '+20 minutes',
        ),
        'UserModel' => array(
            'loadUser' => '+60 minutes',
            'alterPermissions' => '+7 days',
        ),
    );
    
    
    private static $iconMap = array(
	'general' => array(
            'default' => array(
                'width' => 1,
                'height' => 1,
                'left' => 0,
                'top' => 0,
            ),
	),
        'language' => array(
            'cs_cz' => array(
                'width' => 23,
                'height' => 13,
                'left' => 0,
                'top' => 0,
            ),
            'en_us' => array(
                'width' => 23,
                'height' => 13,
                'left' => 0,
                'top' => 13,
            ),
        ),
	'admin' => array(
	    'edit' => array(
		'width' => 16,
		'height' => 16,
		'top' => 0,
		'left' => 24,
	    ),
	    'edit-small' => array(
		'width' => 10,
		'height' => 10,
		'top' => 0,
		'left' => 40,
	    ),
	    'move-small' => array(
		'width' => 10,
		'height' => 10,
		'top' => 10,
		'left' => 40,
	    ),
	),
    );
    
    public function init() {
        $this->cache->alterDatabase();
        
	// Vytvoří obrázek pro vykreslování ikon
        Kate\Helper\ImagePrinter::create(array(
            'iconPath' => Loader::getBaseUrl().'/'.Loader::IMAGES_DIR.'/'.Loader::ICON_DIR.'/icons.png',
            'iconMap' => self::$iconMap,
        ));
    }
    
    /**
     * Pokusí se updatovat databázi podle schéma a vložit základní prvky do databáze...
     */
    public function alterDatabase() {
        // @todo Updatovat strukturu
        
        // Language default
        $data = array(
            'id_language' => self::$defaultLanguage['id_language'],
            'shortcut' => self::$defaultLanguage['shortcut'],
            'location' => self::$defaultLanguage['location'],
            'title' => self::$defaultLanguage['title'],
        );
        try {
            $this->db->table('language')->insert($data);
        } catch (PDOException $e) {
            //Již existuje
        }
        
        // Layout default
        foreach (self::$defaultLayouts as $idLayout => $layout) {
            try {
                $this->db->beginTransaction();
                $idPhrase = ControlModel::get()->insertPhrase(self::$defaultLanguage, $layout['text']);
                $data = array(
                    'id_phrase' => $idPhrase,
                    'id_layout' => $idLayout,
                );
                $this->db->table('layout')->insert($data);
                foreach ($layout['cells'] as $cell) {
                    $idGeometry = ControlModel::get()->insertGeometry($cell['width'], $cell['height']);
                    $data = array(
                        'id_layout' => $idLayout,
                        'id_geometry' => $idGeometry,
                        'row' => $cell['row'],
                        'col' => $cell['col'],
                        'static' => $cell['static'],
                        'rowspan' => $cell['rowspan'],
                        'colspan' => $cell['colspan'],
                    );
                    $this->db->table('cell')->insert($data);
                }
                $this->db->commit();
            } catch (PDOException $e) {
                $this->db->rollBack();
            }
        }
        
        // Modules
        
        
        // Error 404 stránka
        
        return true;
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
    
    /**
     * Vrátí nastavení z databáze
     * @return array nastavení
     */
    public function loadSetting() {
        $q = $this->db->table('setting')
                ->select('setting.value, setting.name');
        $setting = $q->fetchPairs('name', 'value');
        return $setting;
    }
    
    /**
     * Vrátí povolené jazyky z databáze
     * @return array jazyky
     */
    public function loadLanguages() {
        $q = $this->db->table('language')
                ->select('language.id_language, language.shortcut, language.location, language.title');
        $langs = array();
        while ($row = $q->fetch()) {
            $lang = array(
                'shortcut' => strtolower($row['shortcut']),
                'location' => strtolower($row['location']),
                'title' => $row['title'],
            );
            $langs[$row['id_language']] = $lang;
        }
        return $langs;
    }
    
    /**
     * Vrací zda je právě nastaven defaultní jazyk
     * @return boolean je defaultní
     */
    public function isDefaultLanguage() {
        $shortcut = $this->language['shortcut'];
        $location = $this->language['location'];
        $defaultLanguage = $this->getDefaultLanguage();
        if ($shortcut == $defaultLanguage['shortcut'] &&
                ($location == $defaultLanguage['location'] || $location == null)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Vrací adresu pro odkaz na zadanou stránku i s jazykovou vlozkou
     * @param string $path pozadovaný path stránky pro odkaz
     * @param string jazyk zobrazený v url
     * @return string výsledná uri pro odkaz
     */
    public function getUrl($path, $lang = false) {
        if ($lang === false) {
            if ($this->isDefaultLanguage()) {
                $lang = false;
            } else {
                $lang = $this->language['shortcut'].($this->language['location'] ?'_'.strtoupper($this->language['location']) :'');
            }
        }
        return Loader::getBaseUrl().($lang ?'/'.$lang :'').'/'.$path;
    }
    
    
    /**
     * Vrací povolené jazyky z DB... něco ve stylu '[a-zA-Z]{2}(_[a-zA-Z]{2})?'
     * @return string pattern
     */
    public function getPatternOfAllowedLanguages() {
        $pat = array();
        foreach ($this->getLanguages() as $id => $lang) {
            $pat[] = $lang['shortcut'].'(_'.strtoupper($lang['location']).')?';
        }
        return '('.implode('|', $pat).')';
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
        if (empty($this->web)) {
            $this->web = $this->cache()->loadWeb();
        }
        return $this->web['nameLink'];
    }
    
    /**
     * Vrací aktuální název stránky
     * @return string název
     */
    public function getWebName() {
        if (empty($this->web)) {
            $this->web = $this->cache()->loadWeb();
        }
        return $this->web['name'];
    }
    
    
    /**
     * Podle link vrátí id aktuální stránky
     * @param string $pageLink
     * @return int id page
     */
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
        $page = $this->createPageLayoutFromDBFetch($res);
		$page['loginForm'] = AdminModel::get()->createLoginForm();
		return $page;
    }
    
    /**
     * Vrací informace o slotu v daném cellu a stránce del pageParameters
     * @param int $idCell id cellu
     * @return array pole s informaci o slotu
     */
    public function loadSlot($idPage, $idCell, $parameters) {
        unset($parameters[self::ID]);
        $res = false;
        $modules = $this->getModules();
        foreach ($parameters as $idModule => $param) {
            // zjisti z modelu danneho modulu zda neovlivnil tento cell... pokud ano vraci true a je treba nacist cell podle modulu
            $moduleModelName = $modules[$idModule]['label'] . 'ModuleModel'; // @todo loaduje z cache takze muze byt stara verze modules
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
        $moduleModelName = $content['moduleLabel'] . 'ModuleModel'; 
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
        $modules = $this->getModules();
        foreach ($res as $row) {
            $idModule = $row->offsetGet('id_module');
            $contents[] = array(
                'id_content' => $row->offsetGet('id_content'),
                'order' => $row->offsetGet('content_order'),
                'text' => $row->offsetGet('content_text'),
                'link' => $row->offsetGet('content_link'),
                'id_module' => $idModule,
                'moduleLabel' => $modules[$idModule]['label'], // @todo spatne nacita z cache
            );
        }
        $slot['contents'] = $contents;
        return $slot;
    }
    
    /**
     * Přemění z řádku databáze na pole
     * @param Nette\Database\Row $res řádek databáze
     * @return array pole
     */
    private function createPageLayoutFromDBFetch($res) {
        if (!$res || count($res) == 0 || !is_array($res)) {
            return false;
        }
		$langs =  $this->getLanguages();
        $first = reset($res);
        $pageLayout = array(
            'page' => array(
                'id_page' => $first->offsetGet('id_page'),
                'text' => $first->offsetGet('page_text'),
                'link' => $first->offsetGet('page_link'),
                'order' => $first->offsetGet('page_order'),
				'activeLanguage' => $langs[$this->getLanguage()],
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
        
        // Jazyky
        $languages = array();
        $langD = $this->getDefaultLanguage();
        foreach ($langs as $idLang => $lang) {
            $language = array();
            
            $full = false;
            foreach ($langs as $idLangSC => $langSC) {
                if ($idLangSC !== $idLang) {
                    if ($langSC['shortcut'] == $lang['shortcut']) {
                        $full = true;
                        break;
                    }
                }
            }
            $langFull = $language['lang'] = $lang['shortcut'] . '_' . $lang['location'];
            $langPath = $language['lang'] = $lang['shortcut'] . ($full ?'_'.$lang['location'] :'');
            if ($lang['shortcut'] == $langD['shortcut'] && $lang['location'] == $langD['location']) {
                $langPath = '';
            }
            $language['actualPath'] = $this->getUrl($this->getActualPath(), $langPath);
            $language['title'] = $lang['title'];
            //$langauge['imgPath'] = Loader::getBaseUrl().'/'.Loader::IMAGES_DIR.'/'.Loader::ICON_DIR.'/'.Loader::LANGUAGE_DIR.'/'.$langFull.'.png';
            $language['active'] = $this->getLanguage() === $idLang;
            $language['imgHtml'] = Kate\Helper\ImagePrinter::get()->getHtmlIcon($langFull, $language['title'], 'language', $language['active'] ?array('active') :false);
            $languages[] = $language;
        }
        $pageLayout['languages'] = $languages;
        
        
        return $pageLayout;
    }
    
    public function getActualPath() {
        foreach ($this->pageParameters as $id => $params) {
            if ($id === self::ID) {
                $part[] = $params;
                continue;
            }
            $modules = $this->getModules();
            if ($id !== MenuModuleModel::ID) {
                $part[] =  $modules[$id]['link'];
            }
            foreach ($params as $param) {
                $part[] = $param;
            }
        }
		$path = implode('/', $part);
		if (AdminModel::get()->getLoadAdminLogin()) {
			$path .= '/'.AdminModel::ADMIN_LINK;
		}
        return $path;
    }
	
	public function getActualRealPath() {
		$path = \Nette\Environment::getHttpRequest()->getUrl()->getPath();
		$base = \Nette\Environment::getHttpRequest()->getUrl()->getBasePath();
		$realPath = preg_replace('~^'.$base.'~', '', $path);
		return $realPath;
	}
	
    
    /**
     * Vrací nastavení podle klíče z tabulky setting
     * @param string $key klíč
     * @return string hodnota
     */
    public function getSetting($key = null) {
        if ($this->setting == null) {
            $this->setting = $this->cache->loadSetting();
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
    
    /**
     * Nastaví aktuální jazyk dle mezinárodního standardu
     * @param string $shortcut zkratka
     * @param string $location umístění
     */
    public function setLanguage($shortcut, $location) {
        $this->language['shortcut'] = $shortcut;
        $this->language['location'] = $location;
    }
    
    /**
     * Vrátí id aktuálního jazyku
     * @return int id_language
     */
    public function getLanguage() {
        if ($this->language === null) {
            $this->language = self::$defaultLanguage;
        }
        $activeIdLanguage = null;
        foreach ($this->getLanguages() as $idLanguage => $language) {
            if ($language['shortcut'] == $this->language['shortcut']) {
                $activeIdLanguage = $idLanguage;
                if ($language['location'] == $this->language['location']) {
                    $activeIdLanguage = $idLanguage;
                }
            }
        }
        return $activeIdLanguage;
    }
    
    /**
     * Nastaví parametry získané z URL
     * @param array $parameters parametry
     */
    public function setPageParameters($parameters) {
        $this->pageParameters = $parameters;
    }
    
    /**
     * Vrátí parametry z URL
     * @return array paramtery
     */
    public function getPageParameters() {
        return $this->pageParameters;
    }
    
    /**
     * Vrací linky všech modulů v poli pod klíči id_module
     * @return array links of modules
     */
    public function getModuleLinks() {
        $moduleLinks = array();
        foreach ($this->getModules() as $id => $module) {
            $moduleLinks[$id] = $module['link'];
        }
        return $moduleLinks;
    }
    
    /**
     * Vrátí časy expirace cache pro jednotlivé třídy a metody
     * @return array expirace
     */
    public static function getCacheExpirations() {
        return self::$cacheExpirations;
    }
    
    /**
     * Vrátí všechny moduly
     * @return array moduly
     */
    public function getModules() {
        if (empty($this->modules)) {
            $this->modules = $this->cache()->loadModules();
        }
        return $this->modules;
    }
    
    /**
     * Vrátí defaultní jazyk: standart v poly
     * @return array jazyk
     */
    public function getDefaultLanguage() {
        return self::$defaultLanguage;
    }
    
    public function getLanguages() {
        if ($this->languages === null) {
            $this->languages = $this->cache->loadLanguages();
        }
        return $this->languages;
    }
}
?>
