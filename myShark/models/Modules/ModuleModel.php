<?php
use Kate\Main\Model;

abstract class ModuleModel extends Model {
    
    abstract function loadCellChanged($idPage, $idCell, $params);
}
?>
