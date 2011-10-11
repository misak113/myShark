<?php

namespace Kate\Http;

/**
 * Třída která získává informace z userAgent
 * @todo predelat na pravé agenty robotů
 */
class UserAgentParser {
    
    private static $robotUserAgentContains = array(
        'robot', 'googlebot'
    );
    
    /**
     * Vrací true pokud je useragent robot
     * @param string $userAgent useragent
     * @return boolean je robot
     */
    public static function isRobot($userAgent) {
        foreach (self::$robotUserAgentContains as $robot) {
            if (strpos($userAgent, $robot)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Vrací všechny useragenty robotů
     * @return array roboti
     */
    public static function getRobots() {
        return self::$robotUserAgentContains;
    }
}

?>
