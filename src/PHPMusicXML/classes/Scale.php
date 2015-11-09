<?php

class Scale {

	public $properties = array();

	public static $modes = array(
		'major' => 						array(0, 2, 4, 5, 7, 9, 11),
		'minor' => 						'natural minor', //  alias
		'minor harmonic' => 			array(0, 2, 3, 5, 7, 8, 11),
		'minor melodic descending' => 	array(0, 2, 4, 5, 7, 9, 11),
		'minor melodic ascending' => 	array(0, 2, 4, 5, 7, 9, 11),
		'aeolian' => 					'natural minor', // alias
		'altered' => 					array(0, 1, 3, 4, 6, 8, 10),
		'bebop dominant' => 			array(0, 2, 4, 5, 7, 9, 10, 11),
		'dorian' => 					array(0, 2, 3, 5, 7, 8, 10),
		'half diminished' => 			array(0, 2, 3, 5, 6, 8, 10),
		'hungarian minor' => 			array(0, 2, 3, 6, 7, 8, 11),
		'ionian' => 					'major',
		'locrian' => 					array(0, 1, 3, 5, 6, 8, 10),
		'lydian augmented' => 			array(0, 2, 4, 6, 8, 9, 11),
		'lydian' => 					array(0, 2, 4, 6, 7, 9, 11),
		'mixolydian' => 				array(0, 2, 4, 5, 7, 9, 10),
		'major neapolitan' => 			array(0, 1, 3, 5, 7, 9, 11),
		'minor neapolitan' => 			array(0, 1, 3, 5, 7, 8, 11),
		'natural minor' => 				array(0, 2, 3, 5, 7, 8, 10),
		'phrygian' => 					array(0, 1, 3, 5, 7, 8, 10),
		'prometheus' => 				array(0, 2, 4, 6, 9, 10),
		'tritone' => 					array(0, 1, 4, 6, 7, 10),
		'whole tone' => 				array(0, 2, 4, 6, 8, 10),
	);

	public function __construct($scale) {
		if (is_array($scale)) {
			if ($scale['root'] instanceof Pitch) {
				$this->properties['root'] = $scale['root'];
			} else {				
				$this->properties['root'] = new Pitch($scale['root']);
			}

			$this->properties['mode'] = $scale['mode'];
		} else {
			$this->_resolveScaleString($scale);
		}
		if (empty($this->properties['direction'])) {
			$this->properties['direction'] = 'ascending';
		}

	}

	/**
	 * accept a string, like "C# major ascending" or "D# minor",
	 * "E4 aolian ascending" or "dorian"
	 * leaving ambiguities intact to be filled in with setProperty
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	function _resolveScaleString($string) {
		// todo: this
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

	function setProperty($name, $value) {
		$this->properties[$name] = $value;
	}

	// gets pitches in sequence for the scale, of one octave
	// todo: make this better
	function getPitches() {
		$root = $this->properties['root'];
		$pitches = array();
		foreach (self::$modes[$this->properties['mode']] as $interval) {
			$newroot = clone $root;
			$newroot->transpose($interval);
			$pitches[] = $newroot;
		}
		return $pitches;
	}

	/**
	 * Static function: pass in a note, measure, layer etc and get back an array of scales that all the pitches conform to.
	 * @param  [type] $notes [description]
	 * @return [type]        [description]
	 */
	public static function getScales($obj) {

		$pitches = $obj->getAllPitches();

		// todo figure out how to do this efficiently

	}

}
