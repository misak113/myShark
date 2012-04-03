<?php

/**
 * Cache třída
 * 
 * @autor Michael Žabka
 */

namespace Kate\Caching;
use Kate;

class Cache extends \Nette\Object {
    
    
    private $class, $cache, $expirations, $forceCall;
    

    public function __construct(\Nette\Object $class, $path, $expirations, $forceCall) {
	$this->forceCall = $forceCall;
	$this->expirations = $expirations;
        $this->class = $class;
        $className = '_'.str_replace('\\', '.', get_class($class));
        $storagePath = $path.DIRECTORY_SEPARATOR.$className;
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        $storage = new \Nette\Caching\Storages\FileStorage($storagePath);
        $this->cache = new \Nette\Caching\Cache($storage);
    }
    
    public function __call($name, $args) {
        $key = array_merge(array('name' => $name), $args);
        
        $forceCall = $this->hasForceCall($name);
		try {
			$value = $this->cache->load($key);
		} catch(\Nette\NotImplementedException $e) {
			/** @todo \Nette\Caching\Storages\FileStorage::readData(), line:355, error: @unserialize($data) */
			\Nette\Diagnostics\Debugger::log($e);
			$value = NULL;
		}
        if ($value === NULL || $forceCall === true) {
            $expiration = isset($this->expirations[$name]) ?$this->expirations[$name] :null;
            // @todo udelat aby se do klice cache zapisovali i stavy instance tedy jeji atributy a nejlepe i jeji stavy atributu po provedeni fce
            // 
            // Zviditelnění privatní fce
            /* @todo dodelat pristup k privatnim fci pres nejaky tricek... tohle funguje, ale je treba aby byla fce deklarovana ve tride :/
            $publicName = 'public_'.$name;
            $stringArgs = (count($args) > 0?'$':'').implode(', $', array_keys($args));
            //$this->class->__set($publicName, create_function($stringArgs, 'return $this->'.$name.'('.$stringArgs.');'));
            $this->class->{$publicName} = create_function($stringArgs, 'return $this->'.$name.'('.$stringArgs.');');
            $name = $publicName;
            */
            
            $value = call_user_func_array(array($this->class, $name), $args);
            
            try {
				$this->cache->save($key, $value, array(
					\Nette\Caching\Cache::EXPIRE => $expiration,
				));
			} catch(\Exception $e) {
				/** @todo \Nette\Caching\Storages\FileStorage::???, line:205, error: serialize($data) */
				\Nette\Diagnostics\Debugger::log($e);
			}
        }
        return $value;
    }
    
    
    
    /**
     * Vrací true pokud je treba funkci zavolat i kdyz je zakeshovaná... 
     * @param string $name
     * @return boolean 
     * @todo
     */
    protected function hasForceCall($name) {
        return $this->forceCall;
    }
    
    
    /**
     * Vrátí ne Cachovanou instanci
     * @return class Nezacachovaná instance třídy
     */
    public function getInstance() {
        return $this->class;
    }
    
    
}
?>
