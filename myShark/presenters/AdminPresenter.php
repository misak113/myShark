<?php

/**
 * Presenter pro administraci (pouze přihlášení)
 * @author Michael Žabka
 */

class AdminPresenter extends BasePresenter {
    
    
    public function renderDefault() {
        $this->initPresenter();
        $path = $this->params['path'];
    }
}
?>
