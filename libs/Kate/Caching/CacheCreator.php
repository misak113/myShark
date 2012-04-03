<?php

namespace Kate\Caching;
use Kate;

class CacheCreator extends \Nette\Object {
    protected $forceCall, $path, $expirations;

    public function __construct($path, $expirations, $forceCall) {
	$this->path = $path;
	$this->expirations = $expirations;
	$this->forceCall = $forceCall;
    }


    public function create(\Nette\Object $class) {

	return new Cache($class, $this->path, $this->getExpirations($class), $this->forceCall);
    }

    /**
     * Vrátí dobu expiraci pro danou metodu třídy z configu
     * @return string expirace
     * @todo
     */
    protected function getExpirations($class) {
        $expirations = $this->expirations;
        $className = get_class($class);
        if (!key_exists($className, $expirations)) {
            return array();
        }
        $methods = $expirations[$className];
        return $methods;
    }
}
?>
