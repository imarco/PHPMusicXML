<?php

class Part {

	var $measures = array();

	function __construct() {
	}

	function toXML() {
		$out = '<part>';

		if (!empty($this->measures)) {
			foreach ($this->measures as $key => $measure) {
				$out .= $measure->toXML($key);
			}
		}
		$out .= '</part>';

		return $out;
	}

	function addMeasure($measure) {
		$this->measures[] = $measure;
	}

}
