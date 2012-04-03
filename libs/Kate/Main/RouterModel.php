<?php

namespace Kate\Main;

use Kate, \Nette\Application\Routers\Route;

class RouterModel extends Kate\Main\Model {


    public function setRouters(\Nette\Application\Routers\RouteList $router) {
	// Setup router
	$router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
	$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
    }
}

?>
