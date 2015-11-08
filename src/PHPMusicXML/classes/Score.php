<?php

class Score {

	public $properties = array();
	public $parts = array();

	function __construct($properties = array()) {
		$this->properties = $properties;
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

	function setAttribute($property) {

	}

	function toXML($wise = 'partwise') {
		$out = '';
		$out .= '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
		$out .= '<!DOCTYPE score-partwise PUBLIC "-//Recordare//DTD MusicXML 3.0 Partwise//EN" "http://www.musicxml.org/dtds/partwise.dtd">';

		$out .= '<score-partwise version="3.0">';

		$out .= '<part-list>';
		foreach ($this->parts as $key => $part) {
			$out .= '<score-part id="P' . $key . '">';
			$out .= '<part-name>' . $part->properties['name'] . '</part-name>';
			$out .= '</score-part>';
		}
		$out .= '</part-list>';

		foreach ($this->parts as $key => $part) {
			$out .= $part->toXML($key);
		}

		$out .= '</score-partwise>';
		return $out;
	}

	function toPNG() {

	}

	function toPDF() {
		
	}

	function addPart($part) {
		$this->parts[] = clone $part;
	}

	function addMeasure($measure) {
		$this->measures[] = clone $measure;
	}

}
