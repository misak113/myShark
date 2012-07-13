<?php

/**
 * Abstraktní model pro všechny modely v aplikaci
 * 
 * @author Michael Žabka
 * 
 */

namespace Kate\Main;
use Kate;
use Nette\DI\Container;
use Kate\Database\Connection;
use Kate\Caching\Cache;

abstract class Model extends \Nette\Object implements IEnclosed {
    
    
    /** @var Connection */
    protected $db;
	/** @var Container */
	protected $container;
	/** @var Cache */
    private $cache = null;

	protected function __construct() {
		$childClass = get_called_class();
		self::$model[$childClass] = $this;
		$this->initModel();
	}
    
    public function initModel() {
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
