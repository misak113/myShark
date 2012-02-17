<?php

/**
 * Module Menu
 * Tento modul je brán jako hlavní a je bezprostřední aby byl funkční... závisí na něm jiné třídy...
 * 
 */
class MenuModuleModel extends ModuleModel {
    const ID = 1;
    const LABEL = 'Menu';
    const PARENT_ID = 2;
    
    const TYPE_PAGE = 'page';
    const TYPE_SLOT = 'slot';
    const TYPE_URL = 'url';
    const TYPE_NONE = 'none';
    public static $referenceTypes = array(
	self::TYPE_PAGE => 'Na tránku', 
	self::TYPE_SLOT => 'Na slot', 
	self::TYPE_URL => 'Na URL',
	self::TYPE_NONE => 'Bez odkazu',
    );
    
    const SUB_MENU_TYPE_NORMAL = 'normal';
    const SUB_MENU_TYPE_ROLL_DOWN = 'roll_down';
    const SUB_MENU_TYPE_FADE_IN = 'fade_in';
    public static $subMenuTypes = array(
	self::SUB_MENU_TYPE_NORMAL => 'Běžné',
	self::SUB_MENU_TYPE_ROLL_DOWN => 'Rolovací dolů', 
	self::SUB_MENU_TYPE_FADE_IN => 'Zprůhlednění', 
    );
    
    private static $permissions = array(
        array('type' => 'item', 'operation' => 'display', 'text' => 'Zobrazení položek'),
        array('type' => 'item', 'operation' => 'edit', 'text' => 'Editace položek'),
        array('type' => 'item', 'operation' => 'add', 'text' => 'Přidání položek'),
    );
    
    /**
     * Vrací pole všech dostupných práv pro module menu
     * @return array práva
     */
    public function getPermissions() {
        return self::$permissions;
    }
    
    /**
     * Tato funkce zjistuje, zda parametry predané tomuto modulu neovlivnuji 
     * aktualni cell (jeho obsah tedy zobrazovany slot uvnitr). Pokud ano
     * Vrací informace o tomto slotu. Pokud ne, vrací false
     * 
     * @param int $idPage id_page
     * @param int $idCell id_cell
     * @param array $params parametry predané modulu
     * @param int $i pro iteraci pri regresnim volani po parametrech
     * @return array Vrací slot a pole jeho contentů
     */
    public function loadSlot($idPage, $idCell, $params, $i = 0) {
        if (key_exists($i, $params)) {
            // pokud žádný z potomku parametru není nalezen pokusi se o tento parameter
            $res = $this->loadSlot($idPage, $idCell, $params, $i+1);
            if (empty($res)) $res = false;
            if ($res !== false) {
                return $res;
            }
            
            $args = array();
            $sql = 'SELECT DISTINCT slot_ref.id_slot, slot_ref_phrase.text AS slot_text, slot_ref_phrase.link AS slot_link, 
                    contentinslot_ref.order AS content_order, content_ref.id_content, content_ref.id_module, content_ref_phrase.text AS content_text, content_ref_phrase.link AS content_link
                FROM modulemenu_item
                LEFT JOIN slot AS slot_ref ON (slot_ref.id_slot = modulemenu_item.id_slot_reference)
                LEFT JOIN phrase AS slot_ref_phrase ON (slot_ref_phrase.id_phrase = slot_ref.id_phrase)
                LEFT JOIN contentinslot AS contentinslot_ref ON (contentinslot_ref.id_slot = slot_ref.id_slot)
                LEFT JOIN content AS content_ref ON (content_ref.id_content = contentinslot_ref.id_content)
                LEFT JOIN phrase AS content_ref_phrase ON (content_ref_phrase.id_phrase = content_ref.id_phrase)
                
                LEFT JOIN content ON (content.id_content = modulemenu_item.id_content)
                LEFT JOIN contentinslot ON (contentinslot.id_content = content.id_content)
                LEFT JOIN slot ON (slot.id_slot = contentinslot.id_slot)
                LEFT JOIN slotonpageincell ON (slotonpageincell.id_slot = slot.id_slot)
                
                LEFT JOIN modulemenu_item AS item_parent ON (item_parent.id_item = modulemenu_item.id_item_parent)
                LEFT JOIN slot AS slot_parent_ref ON (slot_parent_ref.id_slot = item_parent.id_slot_reference)
                LEFT JOIN phrase AS slot_parent_ref_phrase ON (slot_parent_ref_phrase.id_phrase = slot_parent_ref.id_phrase)
                
                WHERE slot_ref_phrase.link = ?
                    AND modulemenu_item.id_cell_reference = ?
                    AND slotonpageincell.id_page = ? ';
            $args[] = $params[$i];
            $args[] = $idCell;
            $args[] = $idPage;
            
            if ($i > 0) {
                // @todo Tato podmínka zajištujě validaci zda je heararchie menu az k polozce správná... /menu/sub-menu/sub-sub-menu/ pokud ne, dany prvek nenajde... Funguje pouze pro 2 prvky nad sebou, takze pokud zada uzivatel /fake-menu/sub-menu/sub-sub-menu/ tak to naloaduje vsechny co maji tvar /cokoliv/sub-menu/sub-sub-menu/
                $sql .= 'AND slot_parent_ref_phrase.link = ? ';
                $args[] = $params[$i-1];
            } else {
                $sql .= 'AND modulemenu_item.id_item_parent IS NULL ';
            }
            $sql .= 'ORDER BY content_order ';
            
            $q = $this->db->queryArgs($sql, $args);
            $res = $q->fetchAll();
            if (empty($res)) $res = false;
            return $res;
        }
        return false;
    }
    
    /**
     * Tato funkce nacita obsah contentu pro daný model, tedy Menu.
     * @param array $idContent content
     * @return array obsah modelu
     */
    public function loadContent($idContent, $params) {
        $moduleContent = array();
        $moduleContent['items'] = $this->loadItems($idContent);
	$moduleContent['canEdit'] = isAllowed('ModuleMenu_item', 'edit');
        return $moduleContent;
    }
    
    private function loadItems($idContent, $idItemParent = false, $linksParent = '') {
        $args = array();
        $sql = 'SELECT modulemenu_item.id_item, modulemenu_item.id_page_reference, modulemenu_item.id_slot_reference, 
                modulemenu_item.id_cell_reference, modulemenu_item.referenceType, modulemenu_item.referenceUrl, 
		modulemenu_item.subMenuType, modulemenu_item.active, modulemenu_item.visible,
                item_phrase.link AS item_link, item_phrase.text AS item_text, 
                page_phrase.link AS page_link, slot_phrase.link AS slot_link,
                COUNT(item_child.id_item) AS num_childs
            FROM modulemenu_item
            LEFT JOIN phrase AS item_phrase ON (item_phrase.id_phrase = modulemenu_item.id_phrase)
            LEFT JOIN modulemenu_item AS item_child ON (item_child.id_item_parent = modulemenu_item.id_item)
            
            LEFT JOIN page ON (page.id_page = modulemenu_item.id_page_reference)
            LEFT JOIN phrase AS page_phrase ON (page_phrase.id_phrase = page.id_phrase)
            LEFT JOIN slot ON (slot.id_slot = modulemenu_item.id_slot_reference)
            LEFT JOIN phrase AS slot_phrase ON (slot_phrase.id_phrase = slot.id_phrase)
            
            WHERE modulemenu_item.id_content = ? AND ';
        $args[] = $idContent;
        if ($idItemParent) {
            $sql .= 'modulemenu_item.id_item_parent = ? ';
            $args[] = $idItemParent;
        } else {
            $sql .= 'modulemenu_item.id_item_parent IS NULL ';
        }
	if (!UserModel::get()->getUser()->isAllowed('ModuleMenu_item', 'edit')) {
	    $sql .= 'AND modulemenu_item.active = 1 ';
	    $sql .= 'AND modulemenu_item.visible = 1 ';
	}
        $sql .= 'GROUP BY modulemenu_item.id_item
            ORDER BY modulemenu_item.order ';
        $q = $this->db->queryArgs($sql, $args);
        $res = $q->fetchAll();
        if (empty($res)) {
            return false;
        }
        $items = array();
        foreach ($res as $row) {
            $itemsChild = false;
            if ($row->offsetGet('num_childs') > 0) {
                $itemsChild = $this->loadItems($idContent, $row->offsetGet('id_item'), $linksParent.$row->offsetGet('slot_link').'/');
            }
            $item = array(
                'id_item' => $row->offsetGet('id_item'),
                'items' => $itemsChild,
                'references' => array(
                    self::TYPE_PAGE => array(
                        'id_page' => $row->offsetGet('id_page_reference'),
                        'link' => $row->offsetGet('page_link'),
                    ),
                    self::TYPE_SLOT => array(
                        'id_cell' => $row->offsetGet('id_cell_reference'),
                        'id_slot' => $row->offsetGet('id_slot_reference'),
                        'link' => $row->offsetGet('slot_link'),
                    ),
                    self::TYPE_URL => array(
                        'uri' => $row->offsetGet('referenceUrl'),
                    ),
                    'type' => $row->offsetGet('referenceType'),
                ),
		'subMenuType' => $row->offsetGet('subMenuType'),
                'active' => $row->offsetGet('active'),
                'visible' => $row->offsetGet('visible'),
                'link' => $row->offsetGet('item_link'),
                'text' => $row->offsetGet('item_text'),
            );
            
            switch ($row->offsetGet('referenceType')) {
                case self::TYPE_PAGE:
                    $reference = PageModel::get()->getUrl($item['references'][self::TYPE_PAGE]['link']);
                    break;
                case self::TYPE_SLOT:
                    $reference = PageModel::get()->getUrl($item['references'][self::TYPE_PAGE]['link'].'/'.$linksParent.$item['references'][self::TYPE_SLOT]['link']);
                    break;
                case self::TYPE_URL:
                    $reference = $item['references'][self::TYPE_URL]['uri'];
                    break;
                default:
                    $reference = false;
            }
            $item['reference'] = $reference;
            
            // vytvorit spravne pole podle referenceType
            $items[] = $item;
        }
        return $items;
    }
    
    
    public function postMethod($method, $post) {
	
    }
}
?>
