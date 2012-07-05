<?php

/**
 * Modul editovatelného obsahu
 * Tento modul je základní. Je do něj vkládán obsah HTML.
 */
class HtmlModuleModel extends ModuleModel {
    const ID = 3;
    const LABEL = 'Html';
    const PARENT_ID = 2;
    private static $permissions = array(
        array('type' => 'section', 'operation' => 'display', 'text' => 'Zobrazení sekcí'),
        array('type' => 'section', 'operation' => 'edit', 'text' => 'Editace sekcí'),
    );
    
    /**
     * Vrací pole všech dostupných práv pro module html
     * @return array práva
     */
    public function getPermissions() {
        return self::$permissions;
    }
    
    /**
     * Metoda loaduje jak modul ovlivňuje zadaný cell podle zadaných parametrů
     * @param int $idPage id stránky
     * @param int $idCell id buňky
     * @param array $params parametry z adresy
     * @return Nette\Database\Row výsledek z databáze
     */
    public function loadSlot($idPage, $idCell, $params) {
        return false;
    }
    
    /**
     * Vrací obsah který je danného modulu tedy html v danném content
     * @param int $idContent
     * @param array $params
     * @return array parametry pro html module 
     */
    public function loadContent($idContent, $params) {
        $moduleContent = array();
        $moduleContent['sections'] = $this->loadSections($idContent, $params);
		$moduleContent['canEdit'] = isAllowed('ModuleHtml_section', 'edit');
        return $moduleContent;
    }
    
    
    
    private function loadSections($idContent, $params) {
        $args = array();
        $sql = 'SELECT style.id_style, style_phrase.link AS style_link, style_phrase.text AS style_text, 
                style.css, modulehtml_section.id_section, section_phrase.link AS section_link, 
                section_phrase.text AS section_text, modulehtml_section.order
            FROM modulehtml_section
            LEFT JOIN style ON (style.id_style = modulehtml_section.id_style)
            LEFT JOIN phrase AS section_phrase ON (section_phrase.id_phrase = modulehtml_section.id_phrase)
            LEFT JOIN phrase AS style_phrase ON (style_phrase.id_phrase = style.id_phrase)
            WHERE modulehtml_section.id_content = ?
            ORDER BY modulehtml_section.order ';
        $args[] = $idContent;
        $q = $this->db->queryArgs($sql, $args);
        $res = $q->fetchAll();
        if (empty($res)) {
            return false;
        }
        $sections = array();
        foreach ($res as $row) {
            $style = array(
                'id_style' => $row->offsetGet('id_style'),
                'link' => $row->offsetGet('style_link'),
                'text' => $row->offsetGet('style_text'),
                'css' => $row->offsetGet('css'),
            );
            $section = array(
                'id_section' => $row->offsetGet('id_section'),
                'link' => $row->offsetGet('section_link'),
                'text' => $row->offsetGet('section_text'),
                'order' => $row->offsetGet('order'),
                'style' => $style,
            );
            $sections[] = $section;
        }
        return $sections;
    }
    
    public function postMethod($method, $post) {
	
    }
}
?>
