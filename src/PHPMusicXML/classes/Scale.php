<?php
/**
 * This class operates on the understanding that all scales are made from the set of 12 chromatic tempered 
 * pitches, and that there is a limited number of possible combinations of those pitches. The "power set"
 * of all possible scales is a set of 4096 scales, and each one can be represented by a decimal number from
 * 0 (no notes) to 4095 (all 12 notes). The index is deterministic. Simply by converting the decimal number
 * to a binary number, it becomes a bitmask defining what pitches are present in the scale, where bit 1 is the root,
 * bit 2 is up one semitone, bit 4 is a major second, bit 8 is the minor third, etc.
 */

class Scale {

	public $properties = array();

	public static $scaleNames = array(
		273 => 'augmented triad',
		585 => 'diminished seventh',
		1235 => 'tritone scale',
		1365 => 'whole tone',
		1371 => 'altered',
		1387 => 'locrian',
		1389 => 'half diminished',
		1451 => 'phrygian',
		1453 => 'aeolian',
		1709 => 'dorian',
		1717 => 'mixolydian',
		1755 => array('octatonic', 'second mode of limited transposition'),
		2275 => 'fifth mode of limited transposition',
		2475 => 'minor neapolitan',
		2509 => 'hungarian minor',
		2535 => 'fourth mode of limited transposition',
		2731 => 'major neapolitan',
		2741 => array('major', 'ionian'),
		2773 => 'lydian',
		2901 => 'lydian augmented',
		3055 => 'seventh mode of limited transposition',
		3445 => 'sixth mode of limited transposition',
		3501 => array('natural minor', 'minor', 'aeolian'),
		3549 => 'third mode of limited transposition',
		3669 => 'prometheus',
		3765 => 'bebop dominant',
		4095 => 'chromatic 12-tone',
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
	    foreach ($this as $key => $val) {
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
	 * return the levenshtein distance between two scales (a measure of similarity)
	 * @param  Scale  $scale1  the first scale
	 * @param  Scale  $scale2  the second scale
	 * @return int    Levenshtein distance between the two scales
	 */
	static function levenshtein_scale($scale1, $scale2) {
		// todo
	}

	/**
	 * Static function: pass in a note, measure, layer etc and get back an array of scales that all the pitches conform to.
	 * @param  Note, Chord, Layer, Measure   $obj  the thing that has pitches in it, however deep they may be
	 * @return array of Scales
	 */
	public static function getScales($obj) {
		$pitches = $obj->getAllPitches();
		// todo figure out how to do this efficiently
	}

}
