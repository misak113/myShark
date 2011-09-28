<?php
use Kate\Main\Model;

abstract class ModuleModel extends Model {
    
    abstract function loadSlot($idPage, $idCell, $params);
    abstract function loadContent($content, $params);
    
}
?>
