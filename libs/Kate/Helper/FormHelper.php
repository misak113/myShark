<?php
namespace Kate\Helper;

class FormHelper {

	public static function control($type, $value, $options = false) {
		switch ($type) {
			case 'name':
				if (strlen($value) <= 20 && strlen($value) >= 1) {
					return true;
				}
				return false;
				break;
			case 'link':
				if (preg_match('~[a-z1-9][a-z1-9\-]{0,20}~', $value)) {
					return true;
				}
				return false;
				break;
			case 'url':
				if (preg_match("~^[a-b\-+]+://.+$~", $value) || $value == '') {
					//if (file_exists($value)) {
						return true;
					//}
				}
				return false;
				break;
			case 'arrayValue':
				if (in_array($value, $options)) {
					return true;
				}
				return false;
				break;
			case 'id':
				$table = $options;
				if (strpos($options, 'module') !== false) {
					$ex = explode('_', $options);
					$options = $ex[count($ex)-1];
				}
				$idKey = 'id_'.$options;
				$db = \Kate\Main\Loader::get()->getDatabase();
				$sql = "SELECT $idKey FROM $table WHERE $idKey = '$value';";
				$select = $db->query($sql);
				//$res = $db->table($options)->select()->where(array($idKey => $value))->get();
				if (count($select) > 0 || $value == '') {
					return true;
				}
				return false;
				break;
			case 'bool':
				if (is_bool($value) || $value == 0 || $value == 1) {
					return true;
				}
				return false;
				break;
			case 'dimension':
				if (preg_match('~\d+(px|em|in|%|cm|mm|ex|pt|pc)~', $value) || $value == '') {
					return true;
				}
				return false;
				break;
		}
	}
}
?>
