<?php

use \Kate\Main\Model,
        \Kate\Main\Loader;
/**
 * Obstarává veškerá data co se základního rozvržení týká
 * @author Michael Žabka
 */
class HelpModel extends Model {
    
    
    protected function __construct() {
        parent::__construct();
    }
    
    
    /**
     * Vrací cestu k aktuální favicon
     * @return string cesta k favicon
     */
    public function getFaviconPath() {
        $pageModel = Loader::get()->getPageModel();
        $pageNameLink = $pageModel->getPageNameLink();
        $faviconPath = Loader::getUserfilesPath().S.Loader::IMAGES_DIR.S.$pageNameLink.S.Loader::MAIN_DIR.S.'favicon.ico';
        if (!file_exists($faviconPath)) {
            return Loader::getImagesPath().S.Loader::MAIN_DIR.S.'favicon.ico';
        }
        return $faviconPath;
    }
    
}
?>
