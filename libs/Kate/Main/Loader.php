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
    
    const MAIN_DIR = 'main';
    const CACHE_DIR = 'cache';
    const IMAGES_DIR = 'images';
    const USERFILES_DIR = 'userfiles';
    
    
    
    private static $loader = null;
    private $application = null;
    private $database = null;
    private $configurator = null;
    
    
    public static $DEBUG_MODE, $pageModel;
    
    public static $BASE_PATH, $CACHE_STORAGE_PATH, $TEMP_PATH, $WWW_PATH, $IMAGES_PATH, $USERFILES_PATH,
            $BASE_URL;


    private function __construct(\Nette\Configurator $configurator) {
        $this->setConfigurator($configurator);
        $this->application = $configurator->container->application;
        
    }
    
    /**
     * Zapouzdřené vracení jedné instance Louderu
     * @param Application $application Nette aplikace
     * @return Loader jedinečný Loader
     */
    public static function getLoader(\Nette\Configurator $configurator = null) {
        if (self::$loader == null) {
            if (!($configurator instanceof \Nette\Configurator)) {
                throw new \Nette\InvalidArgumentException('Argument musí být Configurator!');
            }
            self::$loader = new Loader($configurator);
            self::$loader->initApplication();
        }
        return self::$loader;
    }
    
    /**
     * Zajistí základní prvky pro běh aplikace
     */
    private function initApplication() {
        //načte databázi
        $this->loadDatabase();
        
        if (class_exists('PageModel')) {
            self::$pageModel = new \PageModel();
        } else {
            throw new Kate\ClassNotFoundException('Vytvořte třídu PageModel Která bude obstarávat základní data pro zobrazení.');
        }
        $this->initPathAndUrl();
        
        //zjistí zda je v debugovacím módu
        self::$DEBUG_MODE = isset($this->configurator->container->params['debugMode'])?$this->configurator->container->params['debugMode']:false;
        
        // Enable Nette\Debug for error visualisation & logging
        if (self::$DEBUG_MODE) {
            Debugger::$strictMode = TRUE;
            Debugger::enable();
        }
        $this->application->catchExceptions = !self::$DEBUG_MODE;
        
        //naloduje routery
        $router = $this->application->getRouter();
        if (class_exists('RouterModel')) {
            \RouterModel::loadRouters($router);
        } else {
            throw new Kate\ClassNotFoundException('Vytvořte třídu RouterModel pro správné routování aplikace.');
        }
    }
    
    private function initPathAndUrl() {
        self::$TEMP_PATH = \Nette\Environment::getVariable('tempDir');
        self::$WWW_PATH = WWW_DIR;
        $exp = explode('/', WWW_DIR);
        $exp = explode('\\', end($exp));
        $exp = end($exp);
        self::$BASE_PATH = substr(WWW_DIR, 0, strlen(WWW_DIR)-strlen($exp)-1);
        self::$CACHE_STORAGE_PATH = self::$TEMP_PATH.S.self::CACHE_DIR;
        self::$IMAGES_PATH = self::$WWW_PATH.S.self::IMAGES_DIR;
        self::$USERFILES_PATH = self::$WWW_PATH.S.self::USERFILES_DIR;
        /**
         * @todo dynamicky url zjistit
         */
        self::$BASE_PATH = '/';
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
    
    private function setConfigurator(\Nette\Configurator $configurator) {
        $this->configurator = $configurator;
    }
    
    
    
    
    
    public static function isDebugMode() {
        return self::$DEBUG_MODE;
    }
    
    public static function getBasePath() {
        return self::$BASE_PATH;
    }
    
    public static function getCacheStoragePath() {
        return self::$CACHE_STORAGE_PATH;
    }
    
    public static function getImagesPath() {
        return self::$IMAGES_PATH;
    }
    
    public static function getUserfilesPath() {
        return self::$USERFILES_PATH;
    }
    
    public static function getBaseUrl() {
        return self::$BASE_URL;
    }
    
    public static function getPageModel() {
        return self::$pageModel;
    }
}
?>
