<?php


class GeneralModuleModel extends ModuleModel {
    const ID = 2;
    const LABEL = 'General';
    const PARENT_ID = null;
    
    public function getPermissions() {
        return array();
    }
    public function loadSlot($idPage, $idCell, $params) {
        return false;
    }
    public function loadContent($content, $params) {
        return false;
    }
    
    public function postMethod($method, $post) {
	
    }
}
?>
