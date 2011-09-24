<?php

use Kate\Main\Cache,
        Kate\External\HeaderControl,
        Kate\Main\Model,
        Nette\Application\Responses\TextResponse;

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
        $cacheHelpModel = HelpModel::get()->cache();
        $httpResponse = Nette\Environment::getHttpResponse();
        $httpResponse->setContentType('image/x-icon');
        $favicon = file_get_contents($cacheHelpModel->getFaviconPath());
        $response = new TextResponse($favicon);
        $this->sendResponse($response);
    }
}
?>