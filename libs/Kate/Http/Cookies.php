<?php

namespace Kate\Http;

class Cookies implements \Kate\Main\IEnclosed {
    
    const COOKIE_ENABLED = 'cookie_enabled';
    
    protected static $cookies = null;
    
    protected function __construct() {
        $response = \Nette\Environment::getHttpResponse();
        $response->setCookie(self::COOKIE_ENABLED, true, '+10 years', '/');
    }
    
    public static function get() {
        if (self::$cookies === null) {
            self::$cookies = new Cookies();
        }
        return self::$cookies;
    }
    
    public function isEnabled() {
        $request = \Nette\Environment::getHttpRequest();
        $enabled = $request->getCookie(self::COOKIE_ENABLED, false);
        return $enabled;
    }
}

?>
