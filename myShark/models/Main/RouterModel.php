<?php


use Nette\Application\Routers\SimpleRouter,
	Nette\Application\Routers\Route,
        Nette\Application\Routers\RouteList;
/**
 * Routuje adresu
 * 
 * @autor Michael Žabka
 */

class RouterModel extends \Kate\Main\Model
{
    
    
    
    /**
     * Přidá do routeru správné routy
     * @param RouterList $router object routů od Nette
     */
    public static function loadRouters(RouteList &$router) {
        
        //robots.txt a sitemap.txt
        $router[] = new Route('robots.txt', 'Help:robots');
        $router[] = new Route('sitemap.xml', 'Help:sitemap');
        $router[] = new Route('favicon.ico', 'Help:favicon');
        //Administrace
        $router[] = new Route('[<path .*>/]admin', 'Admin:default');
        //Frontend
	$router[] = new Route('[<path .*>]', 'Homepage:default');
    }
}
?>
