<?php

class Part {

	var $attributes = array();
	var $measures = array();

	function __construct($attributes = array()) {
	}

	function setAttribute($attribute) {

	}

	function toXML() {
		$out = '';

		if (!empty($this->measures)) {
			foreach ($this->measures as $key => $measure) {
				$out .= $measure->toXML($key);
			}
		}

		return $out;
	}

	function addMeasure($measure) {
		$this->measures[] = $measure;
	}

}
