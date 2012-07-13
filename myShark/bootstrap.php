<?php

/**
 * My Application bootstrap file.
 */


use Nette\Application\Routers\SimpleRouter,
	Nette\Application\Routers\Route,
        Kate\Main\Loader;


// Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require LIBS_DIR . '/Nette/loader.php';


// Load configuration from config.neon file
$configurator = new Nette\Config\Configurator;
// Enable Nette Debugger for error visualisation & logging
//$configurator->setProductionMode($configurator::AUTO);
$configurator->enableDebugger(LOG_DIR);
// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(TEMP_DIR);
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();
$configurator->addConfig(CONFIG_DIR . '/config.neon');
$configurator->addConfig(CONFIG_DIR . '/load.neon');
$container = $configurator->createContainer();
$application = $container->application;

// Setup router
$application->onStartup[] = function() use ($configurator, $container) {
    // Naloadování loaderu
    $loader = $container->getService('loader');
    $loader->setConfigurator($configurator);
    $loader->loadDatabase();
	$loader->initApplication();
    
    // Nastavení z config.neon
    //$loader->setPageModel(PageModel::get());
    $loader->loadCache();
    $loader->loadPageModel();

    
    //$loader->setKinqModules(array('Default'));
    //$loader->loadKinqModules();

    $loader->setRouterModel(RouterModel::get());
    $loader->loadRouters();
    
};


// Run the application!
$application->run();
