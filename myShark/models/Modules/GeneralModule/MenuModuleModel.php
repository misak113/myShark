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
        return $moduleContent;
    }
    
    private function loadItems($idContent, $idItemParent = false) {
        $args = array();
        $sql = 'SELECT modulemenu_item.id_item, modulemenu_item.id_page_reference, modulemenu_item.id_slot_reference, 
                modulemenu_item.id_cell_reference, modulemenu_item.referenceType, modulemenu_item.referenceUrl, 
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
                $itemsChild = $this->loadItems($idContent, $row->offsetGet('id_item'));
            }
            $item = array(
                'id_item' => $row->offsetGet('id_item'),
                'items' => $itemsChild,
                'references' => array(
                    'page' => array(
                        'id_page' => $row->offsetGet('id_page_reference'),
                        'link' => $row->offsetGet('page_link'),
                    ),
                    'slot' => array(
                        'id_cell' => $row->offsetGet('id_cell_reference'),
                        'id_slot' => $row->offsetGet('id_slot_reference'),
                        'link' => $row->offsetGet('slot_link'),
                    ),
                    'url' => array(
                        'uri' => $row->offsetGet('referenceUrl'),
                    ),
                    'type' => $row->offsetGet('referenceType'),
                ),
                'link' => $row->offsetGet('item_link'),
                'text' => $row->offsetGet('item_text'),
            );
            
            switch ($row->offsetGet('referenceType')) {
                case 'page':
                    $reference = Kate\Main\Loader::$BASE_URL.'/'.$item['references']['page']['link'];
                    break;
                case 'slot':
                    $reference = Kate\Main\Loader::$BASE_URL.'/'.$item['references']['page']['link'].'/'.$item['references']['slot']['link'];
                    break;
                case 'url':
                    $reference = $item['references']['url']['uri'];
                    break;
                default:
                    $reference = '';
            }
            $item['reference'] = $reference;
            
            // vytvorit spravne pole podle referenceType
            $items[] = $item;
        }
        return $items;
    }
}
?>
