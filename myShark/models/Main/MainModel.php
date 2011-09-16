<?php


/**
 * Hlavní model Aplikace
 * 
 * @autor Michael Žabka
 */

class MainModel extends Nette\Object {
    
    private static $application = null;
    
    private function __construct($application) {
        self::$application = $application;
    }
    
    public static function getMainModel($application = false) {
        if (!($application instanceof Nette\Application\Application) && $application !== false) {
            throw new Nette\InvalidArgumentException('Argument musí být aplikace!');
        }
        if (self::$application == null) {
            $main = new MainModel($application);
        }
        return $main;
    }
    
    public function initApplication() {
        
        
        $this->loadDatabase();
        
        //naloduje routery
        $router = self::$application->getRouter();
        RouterModel::loadRouters($router);
    }
    
    public function loadDatabase() {
        
    }
}
?>
