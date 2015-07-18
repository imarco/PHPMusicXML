<?php

class Part {

	var $measures = array();
	var $properties = array();

	function __construct($name) {
		$this->properties['name'] = $name;
	}

	/**
	 * force deep cloning, so a clone of the measure will contain a clone of all its sub-objects as well
	 * @return [type] [description]
	 */
	public function __clone() {
	    foreach($this as $key => $val) {
	        if (is_object($val) || (is_array($val))) {
	            $this->{$key} = unserialize(serialize($val));
	        }
	    }
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
