<?php

class Part {

	var $measures = array();
	var $properties = array();

	function __construct($name) {
		$this->properties['name'] = $name;
	}

	function toXML($num = 1) {
		$out = '<part id="P' . $num . '">';

		if (!empty($this->measures)) {
			foreach ($this->measures as $key => $measure) {
				$out .= $measure->toXML($key);
			}
		}
		$out .= '</part>';

		return $out;
	}

	function addMeasure($measure) {
		$newmeasure = clone $measure;
		$this->measures[] = $newmeasure;
	}

}
