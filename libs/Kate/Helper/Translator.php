<?php

namespace Kate\Helper;

class Translator extends \Nette\Object implements \Nette\Localization\ITranslator, \Kate\Main\IEnclosed {
	
	protected static $translator = null;
    
    public static function get() {
        if (self::$translator === null) {
            self::$translator = new Translator();
        }
        return self::$translator;
    }
	
	/**
	 * Přeloží zprávu do aktuálního jazyka
	 * @param type $message
	 * @param type $count
	 * @return type 
	 */
	public function translate($message, $count = NULL) {
		
		return '@trans '.$message;
	}
    
    /**
     * @todo
     * Pomocí google api přeloží automatickky text
     * @param string $text text
     * @param int $languageFrom id jazyka z 
     * @param int $languageTo id jazyka do
     * @return string přeložený text
     */
    public static function googleTranslate($text, $languageFrom, $languageTo) {
        return $text.'-'.$languageTo;
    }
}
?>
