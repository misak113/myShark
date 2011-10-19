<?php

namespace Kate\Helper;

class Url {
    
    /**
     * @todo
     * Vygeneruje link z textu
     * @param string $text text
     * @return string link
     */
    public static function generateLink($text) {
        $name = strtolower($text);
        $co = array(
            "ě", "ř", "ť", "š", "ď", "č", "ň", "é", "ú", "í", "ó", "á", 
            "ý", "ů", "ž"," ","Ě", "Ř", "Ť", "Š", "Ď", "Č", "Ň", "É", 
            "Ú", "Í", "Ó", "Á", "Ý", "Ů", "Ž"
        );
        $cim = array(
            "e", "r", "t", "s", "d", "c", "n", "e", "u", "i", 
            "o", "a", "y", "u", "z","-","e", "r", "t", "s", "d", 
            "c", "n", "e", "u", "i", "o", "a", "y", "u", "z"
        );
        $allowedChars = array(
            "a","b","c","d","e","f","g","h","i","j","k","l","m",
            "n","o","p","q","r","s","t","u","v","w","x","y","z",
            "-","0","1","2","3","4","5","6","7","8","9"
        );

        $name = str_replace($co, $cim, $name);
        $name = str_replace("--", "-", $name);
        $link = "";
        for($i = 0;$i < strlen($name);$i++) {
            if (in_array($name[$i], $allowedChars)) {
                $link .= $name[$i];
            }
        }
        return $link;
    }
}
?>
