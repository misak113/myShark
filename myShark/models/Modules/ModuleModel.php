<?php
use Kate\Main\Model;

abstract class ModuleModel extends Model {
    
    protected $setting;
    
    public function __construct() {
        parent::__construct();
        $this->setting = $this->cache()->loadSetting();
    }
    
    public function loadSetting() {
        $childClass = get_called_class();
        $q = $this->db->table('module_setting')
                ->select('id_moduleSetting, objectModuleType, id_objectModule, name, value')
                ->where('id_module', $childClass::ID);
        $setting = array();
        while ($row = $q->fetch()) {
            $set = array(
                'name' => $row['name'],
                'value' => $row['value'],
                'type' => $row['objectModuleType'],
                'id_object' => $row['id_objectModule'],
            );
            $setting[$row['id_moduleSetting']] = $set;
        }
        return $setting;
    }
    
    abstract function loadSlot($idPage, $idCell, $params);
    abstract function loadContent($idContent, $params);
    abstract function getPermissions();
}
?>
