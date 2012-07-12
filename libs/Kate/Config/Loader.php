<?php
namespace Kate\Config;

use Nette\Config\Adapters\NeonAdapter;
use Nette\DI\Container;

/**
 * Description of Loader
 *
 * @author misak113
 */
class Loader {

	protected $dir = '';
	/**
	 *
	 * @var NeonAdapter 
	 */
	protected $adapter;

	public function __construct(NeonAdapter $adapter, Container $context) {
		$this->adapter = $adapter;
		$params = $context->getParameters();
		$dir = APP_DIR.S.$params['settingsDir'];
		if (!file_exists($dir)) {
			throw new FileNotFoundException('Složka "'.$dir.'" neexistuje');
		}
		$this->dir = $dir;
	}

	public function getConfig($name) {
		$file = $this->dir.S.$name.'.neon';
		if (!file_exists($file)) {
			throw new FileNotFoundException('Soubor "'.$file.'" neexistuje');
		}
		$config = $this->adapter->load($file);
		return $config;
	}
}