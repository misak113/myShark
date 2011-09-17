<?php


use Nette\Application\Routers\SimpleRouter,
	Nette\Application\Routers\Route;
/**
 * Routuje adresu
 * 
 * @autor Michael Žabka
 */

class RouterModel extends \Kate\Main\Model
{
    
    /**
     * Přidá do routeru správné routy
     * @param Router[] $router object routů od Nette
     */
    public static function loadRouters(&$router) {
        
        //robots.txt a sitemap.txt
        $router[] = new Route('robots.txt', 'Help:robots');
        $router[] = new Route('sitemap.xml', 'Help:sitemap');
        //Administrace
        $router[] = new Route('[<path .*>/]admin', 'Admin:default');
        //Frontend
	$router[] = new Route('<path .*>', 'Homepage:default');
    }
}
?>
