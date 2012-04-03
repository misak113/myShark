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
    
    
    
    protected $db, $container;
    private $cache = null;
    
    protected function __construct() {
		$this->container = Loader::get()->getContainer();
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
    
    
    /**
     * Vrátí instanci modelu, která je zachachovaná a lze bezproblémů volat metody třidy přes tuto
     * @return Model cachovaný model
     */
    public function cache() {
	if ($this->cache === null) {
	    $this->cache = Loader::get()->getCacheCreator()->create($this);
	}
        return $this->cache;
    }
    
}
?>
