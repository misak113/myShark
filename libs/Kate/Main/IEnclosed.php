<?php

namespace Kate\Main;

interface IEnclosed {
    
    /**
     * Vrací aktuální instanci modelu...
     * Zapouzdřuje model aby neměl více instancí
     */
    static function get();
}

?>
