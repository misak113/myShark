<?php

use \Kate\Main\Model;

class PageModel extends Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getPages() {
        $db = $this->db;
        $q = $db->query('SELECT * FROM page;');
        $q->dump();
    }
}
?>
