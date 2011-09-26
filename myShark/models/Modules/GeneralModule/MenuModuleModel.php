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
    
    public function loadCellChanged($idPage, $idCell, $params, $i = 0) {
        if (key_exists($i, $params)) {
            $args = array();
            $sql = 'SELECT COUNT(modulemenu_item.id_item) AS count
                FROM modulemenu_item
                LEFT JOIN slot AS slot_ref ON (slot_ref.id_slot = modulemenu_item.id_slot_reference)
                LEFT JOIN phrase AS slot_ref_phrase ON (slot_ref_phrase.id_phrase = slot_ref.id_phrase)
                
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
                $sql .= 'AND slot_parent_ref_phrase.link = ? ';
                $args[] = $params[$i-1];
            } else {
                $sql .= 'AND modulemenu_item.id_item_parent IS NULL ';
            }
            
            $sql .= 'LIMIT 1';
            $q = $this->db->queryArgs($sql, $args);
            $row = $q->fetch();
            return ($row['count'] > 0) || $this->loadCellChanged($idPage, $idCell, $params, ++$i);
        }
        return false;
    }
    
    
    public function loadSlot($idPage, $idCell, $params, $i = 0) {
        if (key_exists($i, $params)) {
            $args = array();
            $sql = 'SELECT DISTINCT slot_ref.*, slot_ref_phrase.*, contentinslot_ref.*, content_ref.*, content_ref_phrase.*
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
                $sql .= 'AND slot_parent_ref_phrase.link = ? ';
                $args[] = $params[$i-1];
            } else {
                $sql .= 'AND modulemenu_item.id_item_parent IS NULL ';
            }
            
            $q = $this->db->queryArgs($sql, $args);
            $slot = $q->fetchAll();
            $subSlot = $this->loadSlot($idPage, $idCell, $params, ++$i);
            if ($subSlot === false) {
                return $slot;
            } else {
                return $subSlot;
            }
        }
        return false;
    }
}
?>
