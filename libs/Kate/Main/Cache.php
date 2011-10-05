<?php

/**
 * Cache třída
 * 
 * @autor Michael Žabka
 */

namespace Kate\Main;
use Kate;

class Cache extends \Nette\Object {
    
    
    private $class, $cache;
    
    


    public function __construct(\Nette\Object $class) {
        $this->class = $class;
        $className = '_'.str_replace('\\', '.', get_class($class));
        $storagePath = Loader::getCacheStoragePath().DIRECTORY_SEPARATOR.$className;
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        $storage = new \Nette\Caching\Storages\FileStorage($storagePath);
        $this->cache = new \Nette\Caching\Cache($storage);
    }
    
    public function __call($name, $args) {
        $key = array_merge(array('name' => $name), $args);
        
        $forceCall = $this->hasForceCall($name);
        $value = $this->cache->load($key);
        if ($value === NULL || $forceCall === true) {
            $expiration = $this->getExpiration($name);
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
            
            
            $this->cache->save($key, $value, array(
                \Nette\Caching\Cache::EXPIRE => $expiration,
                )
            );
        }
        return $value;
    }
    
    /**
     * Vrátí dobu expiraci pro danou metodu třídy z configu
     * @param string $name jméno metody
     * @return string expirace
     * @todo
     */
    private function getExpiration($name) {
        $expirations = \PageModel::getCacheExpirations();
        $className = get_class($this->class);
        if (!key_exists($className, $expirations)) {
            return null;
        }
        $methods = $expirations[$className];
        if (!key_exists($name, $methods)) {
            return null;
        }
        return $methods[$name];
    }
    
    /**
     * Vrací true pokud je treba funkci zavolat i kdyz je zakeshovaná... 
     * @param string $name
     * @return boolean 
     * @todo
     */
    private function hasForceCall($name) {
        return !Loader::isCacheMode();
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
