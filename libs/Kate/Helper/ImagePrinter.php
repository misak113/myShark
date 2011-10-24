<?php

namespace Kate\Helper;
use \Nette\Utils\Html;

class ImagePrinter implements \Kate\Main\IEnclosed {
    
    private static $imagePrinter = null;
    
    private $iconMap = array();
    private $iconPath = '';
    
    private function __construct($setting) {
        if (isset($setting['iconMap'])) {
            $this->iconMap = $setting['iconMap'];
        }
        if (isset($setting['iconPath'])) {
            $this->iconPath = $setting['iconPath'];
        }
    }
    
    /**
     * Vytvoří imagePrinter a zadá se mu nastavení
     * @param array $setting nastavení 
     */
    public static function create($setting = false) {
        if ($setting === false) {
            $setting = array();
        }
        if (self::$imagePrinter === null) {
            self::$imagePrinter = new ImagePrinter($setting);
        }
        
    }
    
    /**
     * Vrátí instanci
     * @return ImagePrinter
     */
    public static function get() {
        return self::$imagePrinter;
    }
    
    
    /**
     * Vrátí html objekt podoby pro obrázek dle specifikací
     * @param string $iconName jmeno ikony
     * @param string $alt alt popisek
     * @param string $namespace jmený prostor ikony
     * @param array $addClasses dodatečné třídy
     * @return Utils\Html ikona v html
     */
    public function getHtmlIcon($iconName, $alt, $namespace = 'general', $addClasses = false) {
        $icon = $this->iconMap[$namespace][$iconName];
        $iconEl = Html::el('div', array(
            'style' => array(
                'width' => $icon['width'].'px',
                'height' => $icon['height'].'px',
                'background-image' => "url('".$this->iconPath."')",
                'background-position' => '-'.$icon['left'].'px -'.$icon['top'].'px',
            ),
            'class' => array('image', 'icon'),
            'title' => $alt,
        ));
        if ($addClasses)
            foreach ($addClasses as $class) 
                $iconEl->class[] = $class;
        return $iconEl;
    }
    
}

?>
