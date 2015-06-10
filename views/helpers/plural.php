<?php
class PluralHelper extends Helper {
	function ize($s, $c, $showNumber = true) {
		if ($c != 1) {
			return $c . ' ' . Inflector::pluralize($s);
		}
			return ($showNumber == true ? $c . ' ' : '') . $s;
		}
	}