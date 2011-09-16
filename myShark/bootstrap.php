<?php

/**
 * My Application bootstrap file.
 */


use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\SimpleRouter,
	Nette\Application\Routers\Route;


// Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require LIBS_DIR . '/Nette/loader.php';


// Enable Nette\Debug for error visualisation & logging
Debugger::$strictMode = TRUE;
Debugger::enable();


// Load configuration from config.neon file
$configurator = new Nette\Configurator;
$configurator->loadConfig(__DIR__ . '/config.neon');


// Configure application
$application = $configurator->container->application;
$application->errorPresenter = 'Error';
//$application->catchExceptions = TRUE;


// Setup router
$application->onStartup[] = function() use ($application) {
    $main = MainModel::getMainModel($application);
    $main->initApplication();
};


// Run the application!
$application->run();
