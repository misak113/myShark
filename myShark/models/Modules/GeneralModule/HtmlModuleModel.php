<?php

/**
 * Modul editovatelného obsahu
 * Tento modul je základní. Je do něj vkládán obsah HTML.
 */
class HtmlModuleModel extends ModuleModel {
    const ID = 3;
    const LABEL = 'Html';
    const PARENT_ID = 2;
    
    public function loadSlot($idPage, $idCell, $params) {
        return false;
    }
    
    public function loadContent($content) {
        return false;
    }
}
?>
