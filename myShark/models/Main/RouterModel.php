<?php

use Nette\Application\Routers\SimpleRouter,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList,
	Kate\Main\Loader;

/**
 * Routuje adresu
 * 
 * @autor Michael Žabka
 */
class RouterModel extends \Kate\Main\RouterModel {

	/**
	 * Přidá do routeru správné routy
	 * @param RouterList $router object routů od Nette
	 */
	public function setRouters(RouteList $router) {

		//robots.txt a sitemap.txt
		$router[] = new Route('robots.txt', 'Help:robots');
		$router[] = new Route('sitemap.xml', 'Help:sitemap');
		$router[] = new Route('favicon.ico', 'Help:favicon');
		//Administrace
		//$router[] = new Route('[<path .*>/]admin', 'Admin:default');
		$language = PageModel::get()->cache()->getDefaultLanguage();
		//Frontend
		$router[] = new Route('[<language>/][<path>]', array(
					'presenter' => 'Homepage',
					'action' => 'default',
					'language' => array(
						Route::VALUE => $language['shortcut'],
						Route::PATTERN => PageModel::get()->cache()->getPatternOfAllowedLanguages(),
						Route::FILTER_IN => array(__CLASS__, 'parseLanguage'),
						Route::FILTER_OUT => array(__CLASS__, 'buildLanguage'),
					),
					'path' => array(
						Route::PATTERN => '.*',
						Route::FILTER_IN => array(__CLASS__, 'parsePath'),
						Route::FILTER_OUT => array(__CLASS__, 'buildPath'),
					),
				));
	}

	/**
	 * Naparsuje jazyk a ulozi do page modelu
	 * @param string $lang language
	 * @return string language
	 */
	public static function parseLanguage($lang) {
		$l = strtolower($lang);

		$l = explode('_', $l);
		$location = null;
		if (isset($l[1])) {
			$location = $l[1];
		}
		$shortcut = $l[0];
		$return = $shortcut . ($location !== null ? '_' . strtoupper($location) : '');

		// Nastaví jazyk v pageModelu
		Loader::get()->getPageModel()->setLanguage($shortcut, $location);
		return $return;
	}

	/**
	 * Vytvoří správný typ jazyka
	 * @param string $lang language
	 * @return string language
	 */
	public static function buildLanguage($lang) {
		$l = strtolower($lang);

		$l = explode('_', $l);
		$location = null;
		if (isset($l[1])) {
			$location = $l[1];
		}
		$shortcut = $l[0];

		if (Loader::get()->getPageModel()->isDefaultLanguage()) {
			$language = PageModel::get()->cache()->getDefaultLanguage();
			return $language['shortcut'];
		}

		$return = $shortcut . ($location !== null ? '_' . strtoupper($location) : '');
		return $return;
	}

	/**
	 * Parsuje url a získává další parametry které předá pageModelu pro pozdější zpracování
	 * @param string $path url parametry v adresa
	 * @return string path
	 */
	public static function parsePath($path) {
		$path = strtolower($path);
		$params = explode('/', $path);

		if (end($params) === AdminModel::ADMIN_LINK) {
			AdminModel::get()->setLoadAdminLogin();
			unset($params[count($params) - 1]);
		}

		$page = null;
		if (isset($params[0]) && $params[0] !== '') {
			$page = $params[0];
		}
		unset($params[0]);

		$moduleLinks = Loader::get()->getPageModel()->getModuleLinks();
		$moduleId = MenuModuleModel::ID; // id pro menu Module
		$parameters = array(
			PageModel::ID => $page
		);

		foreach ($params as $param) {
			if (in_array($param, $moduleLinks)) {
				$moduleId = array_search($param, $moduleLinks);
				$parameters[$moduleId] = array();
				continue;
			}
			$parameters[$moduleId][] = $param;
		}

		Loader::get()->getPageModel()->setPageParameters($parameters);
		return $path;
	}

	/**
	 * Vytvoří správný path ze zadaného
	 * @param string $path path
	 * @return string path
	 */
	public static function buildPath($path) {
		$path = strtolower($path);
		return $path;
	}

}

?>
