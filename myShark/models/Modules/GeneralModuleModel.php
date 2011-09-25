<?php


class GeneralModuleModel extends ModuleModel {
    const ID = 2;
    const LABEL = 'General';
    const PARENT_ID = null;
    
    public function loadCellChanged($idPage, $idCell, $params) {
        return false;
    }
}
?>
