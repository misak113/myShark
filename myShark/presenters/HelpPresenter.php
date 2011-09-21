<?php

use Kate\Main\Cache,
        Kate\External\HeaderControl;

/**
 * Pomocní presenter např. pro seo sitemap.xml a robots.txt
 * @autor Michael Žabka
 */
class HelpPresenter extends Kate\Main\Presenter {
    
    
    public function renderRobots() {
        
    }
    
    public function renderSitemap() {
        
    }
    
    public function renderFavicon() {
        $helpModel = new HelpModel();
        $cacheHelpModel = new Cache($helpModel);
        $httpResponse = Nette\Environment::getHttpResponse();
        $httpResponse->setContentType('image/x-icon');
        echo file_get_contents($cacheHelpModel->getFaviconPath());
        die();
    }
}
?>