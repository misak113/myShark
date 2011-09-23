<?php

/**
 * Abstraktní model pro všechny modely v aplikaci
 * 
 * @author Michael Žabka
 * 
 */

namespace Kate\Main;
use Kate;

abstract class Model extends \Nette\Object implements IEnclosed {
    
    
    
    protected $db;
    
    protected function __construct() {
        $this->db = Loader::get()->getDatabase();
    }
    
    
    protected static $model = array();
    
    public static function get() {
        $childClass = get_called_class();
        if (!isset(self::$model[$childClass])) {
            self::$model[$childClass] = new $childClass();
        }
        return self::$model[$childClass];
    }
    
    
}
?>
