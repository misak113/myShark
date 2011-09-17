<?php

/**
 * Abstraktní model pro všechny modely v aplikaci
 * 
 * @author Michael Žabka
 * 
 */

namespace Kate\Main;
use Kate;

abstract class Model extends \Nette\Object {
    
    protected $db;
    
    protected function __construct() {
        $this->db = Loader::getLoader()->getDatabase();
    }
}
?>
