<?php

/**
 * Presenter pro administraci (pouze přihlášení)
 * @author Michael Žabka
 */

class AdminPresenter extends Kate\Main\Presenter {
    
    
    public function renderDefault() {
        $this->initPresenter();
        $path = $this->params['path'];
    }
}
?>
