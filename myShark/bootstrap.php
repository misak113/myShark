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
$configurator = new Nette\Configurator;
$configurator->loadConfig(CONFIG_DIR . '/config.neon');


// Configure application
$application = $configurator->container->application;


// Setup router
$application->onStartup[] = function() use ($configurator) {
    // Naloadování loaderu
    Loader::get($configurator);
};


// Run the application!
$application->run();
