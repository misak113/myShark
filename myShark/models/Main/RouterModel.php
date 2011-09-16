<?php


use Nette\Application\Routers\SimpleRouter,
	Nette\Application\Routers\Route;
/**
 * Routuje adresu
 * 
 * @autor Michael Žabka
 */

class RouterModel extends Nette\Object
{
    
    public static function loadRouters(&$router) {
        $router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);

	$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
    }
}
?>
