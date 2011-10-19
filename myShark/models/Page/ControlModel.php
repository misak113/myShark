<?php



class ControlModel extends \Kate\Main\Model {
    
    
    /**
     * Vytvoří phrase v databázy
     * @param array $language jazyk zadaného phrase
     * @param string $text text phrase
     * @param string $link link
     * @return int id phrase vloženého 
     */
    public function insertPhrase($language, $text, $link = false) {
        if ($link === false) {
            $link = \Kate\Helper\Url::generateLink($text);
        }
        $sql = 'SELECT (MAX(id_phrase)+1) AS id_phrase FROM phrase';
        $q = $this->db->queryForce($sql);
        $res = $q->fetch();
        $idPhrase = $res['id_phrase'];
        $data = array(
            'id_phrase' => $idPhrase,
            'text' => $text,
            'link' => $link,
            'id_language' => $language['id_language'],
        );
        $this->db->table('phrase')->insert($data);
        
        $languages = PageModel::get()->getLanguages();
        foreach ($languages as $id_language => $lang) {
            if ($id_language === $language['id_language']) {
                continue;
            }
            $textTrans = \Kate\Helper\Translator::googleTranslate($text, $language['id_language'], $id_language);
            $link = \Kate\Helper\Url::generateLink($textTrans);
            $data = array(
                'id_phrase' => $idPhrase,
                'text' => $textTrans,
                'link' => $link,
                'id_language' => $id_language,
            );
            $this->db->table('phrase')->insert($data);
        
        }
        return $idPhrase;
    }
    
    /**
     * Vytvoří geometry a vrátí její ID
     * @param int $width šířka
     * @param int $height výška
     * @param string $widthUnit jednotka šířky
     * @param string $heightUnit jednotka výšky
     * @return int id geometry
     */
    public function insertGeometry($width, $height, $widthUnit = 'px', $heightUnit = 'px') {
        $data = array(
            'width' => $width,
            'height' => $height,
            'width_unit' => $widthUnit,
            'height_unit' => $heightUnit,
        );
        $this->db->table('geometry')->insert($data);
        $idGeometry = $this->db->lastInsertId();
        return $idGeometry;
    }

}

?>
