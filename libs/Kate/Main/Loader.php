<?php

/**
 * Hlavní model Aplikace
 * 
 * @autor Michael Žabka
 */

namespace Kate\Main;

use Nette\Diagnostics\Debugger,
    Kate;

class Loader extends \Nette\Object implements IEnclosed {
    const MAIN_DIR = 'main';
    const CACHE_DIR = 'cache';
    const IMAGES_DIR = 'images';
    const USERFILES_DIR = 'userfiles';
    const MODULES_DIR = 'modules';
    const CSS_DIR = 'css';
    const ICON_DIR = 'icon';
    const LANGUAGE_DIR = 'language';
    const WINDOWS_DIR = 'windows';
    const DEFAULT_DIR = 'default';



    private static $loader = null;
    private $application = null;
    private $database = null;
    private $configurator = null;
    private $container = null;
    private static $DEBUG_MODE, $CACHE_MODE;
    private static $BASE_PATH, $CACHE_STORAGE_PATH, $TEMP_PATH, $WWW_PATH, $IMAGES_PATH, $USERFILES_PATH,
    $BASE_URL;

    private function __construct(\Nette\Config\Configurator $configurator) {
	$this->setConfigurator($configurator);
	$this->container = \Nette\Environment::getContext();
	$this->application = $this->container->application;
    }

    /**
     * Zapouzdřené vracení jedné instance Louderu
     * @param Application $application Nette aplikace
     * @return Loader jedinečný Loader
     */
    public static function get(\Nette\Config\Configurator $configurator = null) {
	if (self::$loader == null) {
	    if (!($configurator instanceof \Nette\Config\Configurator)) {
		throw new \Nette\InvalidArgumentException('Poprvé je třeba zadat Parametry!');
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
	new \shorthands; // Pro naloadování daného common helperu pro zkracování zápisů
	$cookies = \Kate\Http\Cookies::get();


	//zjistí zda je v debugovacím módu
	self::$DEBUG_MODE = isset($this->container->params['debugMode']) ? $this->container->params['debugMode'] : false;
	self::$CACHE_MODE = isset($this->container->params['cacheMode']) ? $this->container->params['cacheMode'] : false;

	// Enable Nette\Debug for error visualisation & logging
	if (self::$DEBUG_MODE) {
	    Debugger::$strictMode = TRUE;
	    Debugger::enable();
	}
	$this->application->catchExceptions = !self::$DEBUG_MODE;



	//načte databázi
	$this->loadDatabase();


	if (!class_exists('PageModel')) {
	    throw new Kate\ClassNotFoundException('Vytvořte třídu PageModel Která bude obstarávat základní data pro zobrazení.');
	}
	$this->initPathAndUrl();
	\PageModel::get()->init();



	//naloduje routery
	$router = $this->application->getRouter();
	if (class_exists('RouterModel')) {
	    \RouterModel::setRouters($router);
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
	self::$BASE_PATH = substr(WWW_DIR, 0, strlen(WWW_DIR) - strlen($exp) - 1);
	self::$CACHE_STORAGE_PATH = self::$TEMP_PATH . S . self::CACHE_DIR;
	self::$IMAGES_PATH = self::$WWW_PATH . S . self::IMAGES_DIR;
	self::$USERFILES_PATH = self::$WWW_PATH . S . self::USERFILES_DIR;
	self::$BASE_URL = rtrim(\Nette\Environment::getHttpRequest()->getUrl()->getBaseUrl(), '/');
    }

    /**
     * Nalouduje databázi do proměné
     */
    private function loadDatabase() {
	$reflection = new \Nette\Database\Reflection\ConventionalReflection('id_%s', 'id_%s', '%s');

	$db = $this->container->params['database'];
	$dsn = "{$db['driver']}:host={$db['host']};dbname={$db['database']}" .
		((isset($db['port'])) ? ";port={$db['port']}" : "");
	if (class_exists('\Kate\Database\Connection')) {
	    $connectionName = '\Kate\Database\Connection';
	} else {
	    $connectionName = '\Nette\Database\Connection';
	}
	$this->database = new $connectionName($dsn, $db['username'], $db['password']);
	$this->database->setDatabaseReflection($reflection);


	/* function addService () {
	  $service = new \Nette\Database\Diagnostics\ConnectionPanel;
	  $service->explain = TRUE;
	  Debugger::$bar->addPanel($service);
	  return $service;
	  }
	  $this->database->onQuery[] = array('addService'); */
    }

    /**
     * Vrátí instanci databáze
     * @return Connection
     */
    public function getDatabase() {
	return $this->database;
    }

    private function setConfigurator(\Nette\Config\Configurator $configurator) {
	$this->configurator = $configurator;
    }

    public function getContainer() {
	return $this->container;
    }

    public function getPresenter() {
	return $this->application->getPresenter();
    }

    public function getUserModel() {
	return \PageModel::get()->getUserModel();
    }

    public static function isDebugMode() {
	return self::$DEBUG_MODE;
    }

    public static function isCacheMode() {
	return self::$CACHE_MODE;
    }

    public static function getBasePath() {
	return self::$BASE_PATH;
    }

    public static function getWindowTemplatePath($name) {
	return self::WINDOWS_DIR . S . $name . '.latte';
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
	return \PageModel::get();
    }

}

?>
