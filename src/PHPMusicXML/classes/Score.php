<?php

class Score {

	public $attributes = array();
	public $parts = array();

	function __construct($attributes = array()) {
	}

	function setAttribute($attribute) {

	}

	function toXML($wise = 'timewise') {
		$out = '';
		$out .= '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
		$out .= '<!DOCTYPE score-partwise PUBLIC "-//Recordare//DTD MusicXML 3.0 Partwise//EN" "http://www.musicxml.org/dtds/partwise.dtd">';

		foreach ($this->parts as $key => $part) {
			$out .= $part->toXML($key, $wise);
		}

		return $out;
	}

	function toPNG() {

	}

	function toPDF() {
		
	}

	function addPart($part) {
		$this->parts[] = $part;
	}

	function addMeasure($measure) {
		$this->measures[] = $measure;
	}

}
