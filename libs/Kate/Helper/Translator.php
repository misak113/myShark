<?php

namespace Kate\Helper;

class Translator {
    
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
