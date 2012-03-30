<?php
 
// uncomment this line if you must temporarily take down your site for maintenance
// require '.maintenance.php';

// the identification of this site
define('SITE', 'myShark');

// absolute filesystem path to the web root
define('WWW_DIR', __DIR__);

// absolute filesystem path to the application root
define('APP_DIR', WWW_DIR . '/../myShark');

// absolute filesystem path to the libraries
define('LIBS_DIR', WWW_DIR . '/../libs');

// absolute filesystem path to the temporary files
define('TEMP_DIR', WWW_DIR . '/../temp');

// absolute filesystem path to the temporary files
define('CONFIG_DIR', WWW_DIR . '/../config');

// absolute filesystem path to log dir
define('LOG_DIR', __DIR__ . '/../log');

//Konstanty pro zkrácení zápisu
define('S', DIRECTORY_SEPARATOR);

// load bootstrap file
require APP_DIR . '/bootstrap.php';
