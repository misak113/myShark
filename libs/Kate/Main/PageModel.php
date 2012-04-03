<?php

namespace Kate\Main;

use Kate;

class PageModel extends Kate\Main\Model implements IPageModel {


    public function init() {
	
    }

    public function getUserModel() {
	return null;
    }

    public function getTitle() {
	return 'Main Application';
    }

    public function getLaunguage() {
	return null;
    }

    public function getCacheExpirations() {
	return null;
    }

    public function getActualPath() {
	return '/';
    }
}

?>
