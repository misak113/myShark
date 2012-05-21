<?php

namespace Kate\Helper;

class UtilHelper {

	public static function parseMagnitude($magnitude) {
		if (preg_match('~(\d*)(\w*)~', $magnitude, $matches)) {
			$parse = array(
				'value' => $matches[1],
				'unit' => $matches[2],
			);
			return $parse;
		}
		return null;
	}
}