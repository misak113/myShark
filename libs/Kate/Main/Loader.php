<?php


/**
 * Hlavní model Aplikace
 * 
 * @autor Michael Žabka
 */

namespace Kate\Main;
use Nette\Diagnostics\Debugger,
	Kate;

class Loader extends \Nette\Object {
    
    private static $loader = null;
    private $application = null;
    private $database = null;
    private $configurator = null;
    
    private function __construct($application) {
        $this->application = $application;
    }
    
    /**
     * Zapouzdřené vracení jedné instance Louderu
     * @param Application $application Nette aplikace
     * @return Loader jedinečný Loader
     */
    public static function getLoader($application = false) {
        if (self::$loader == null) {
            if (!($application instanceof \Nette\Application\Application)) {
                throw new Nette\InvalidArgumentException('Argument musí být aplikace!');
            }
            self::$loader = new Loader($application);
        }
        return self::$loader;
    }
    
    /**
     * Zajistí základní prvky pro běh aplikace
     */
    public function initApplication() {
        
        //zjistí zda je v debugovacím módu
        $debugMode = isset($this->configurator->container->params['debugMode'])?$this->configurator->container->params['debugMode']:false;
        
        // Enable Nette\Debug for error visualisation & logging
        if ($debugMode) {
            Debugger::$strictMode = TRUE;
            Debugger::enable();
        }
        $this->application->catchExceptions = !$debugMode;
        
        //načte databázi
        $this->loadDatabase();
        
        //naloduje routery
        $router = $this->application->getRouter();
        if (class_exists('RouterModel')) {
            \RouterModel::loadRouters($router);
        } else {
            throw new Kate\ClassNotFoundException('Vytvořte třídu RouterModel pro správné routování aplikace.');
        }
    }
    
    /**
     * Nalouduje databázi do proměné
     */
    private function loadDatabase() {
        $db = $this->configurator->container->params['database'];
        $dsn = "{$db->driver}:host={$db->host};dbname={$db->database}".
                ((isset($db->port)) ?";port={$db->port}" :"");
        $this->database = new \Nette\Database\Connection($dsn, $db->username, $db->password);
    }
    
    /**
     * Vrátí instanci databáze
     * @return Connection
     */
    public function getDatabase() {
        return $this->database;
    }
    
    public function setConfigurator($configurator) {
        $this->configurator = $configurator;
    }
}
?>
