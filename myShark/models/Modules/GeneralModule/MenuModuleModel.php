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
     * @param array $content content
     * @return array obsah modelu
     */
    public function loadContent($content, $params) {
        return 'MENU';
    }
}
?>
