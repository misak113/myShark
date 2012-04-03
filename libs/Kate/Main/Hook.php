<?php

namespace Kate\Main;

/**
 * Háčkování aplikace
 */
class Hook extends \Nette\Object implements IEnclosed {
	
	const BACK = 1;
	const RELOAD = 2;
	const UP = 3;

	const REDIRECT = 'redirect';
	const EXAMPLE = 'example';
	
	protected $hooks;
	protected static $hook = null;
	
	protected function __construct() {
		$this->hooks = array();
	}
	
	public static function get() {
		if (self::$hook === null) {
			self::$hook = new Hook();
		}
        return self::$hook;
    }

	/**
	 * Přidá hook pro přesměrování
	 * @param mixed $target buď integer z místních konstant BACK apod. nebo URI string
	 */
	public function redirect($target) {
		if (is_int($target)) {
			// @todo dopracovat presmerovani podle konstant BACK atd.
			switch ($target) {
				case self::UP:
					$path = Loader::get()->getPageModel()->getActualPath();
					$path = explode('/', $path);
					unset($path[count($path)-1]);
					$target = Loader::getBaseUrl().'/'.implode('/', $path);
					break;
			}
		}
		$this->addHook(self::REDIRECT, $target);
	}
	
	protected function addHook($type, $value) {
		if (!\array_key_exists($type, $this->hooks)) {
			$this->hooks[$type] = array();
		}
		$this->hooks[$type][] = $value;
	}
	
	/**
	 * Spustí všechny hooky resp. provede
	 */
	public function process() {
		
		if (\key_exists(self::EXAMPLE, $this->hooks)) {
			foreach ($this->hooks[self::EXAMPLE] as $hook) {
				
			}
		}
		
		// Redirect až nakonci
		if (\array_key_exists(self::REDIRECT, $this->hooks)) {
			Loader::get()->getPresenter()->redirectUrl(\end($this->hooks[self::REDIRECT]), 302);
		}
	}
}

?>
