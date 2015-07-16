<?php

class Pitch {
	
	public $properties = array(
		'step' => 'C',
		'alter' => 0,
		'octave' => 4
	);

	public static $chromas = array(
		0 => 'C',
		1 => array(
			array('step' => 'C', 'alter' => 1),
			array('step' => 'D', 'alter' => -1)
		),
		2 => 'D',
		3 => array(
			array('step' => 'D', 'alter' => 1),
			array('step' => 'E', 'alter' => -1),
		),
		4 => 'E',
		5 => 'F',
		6 => array(
			array('step' => 'F', 'alter' => 1),
			array('step' => 'G', 'alter' => -1),
		),
		7 => 'G',
		8 => array(
			array('step' => 'G', 'alter' => 1),
			array('step' => 'A', 'alter' => -1),
		),
		9 => 'A',
		10 => array(
			array('step' => 'A', 'alter' => 1),
			array('step' => 'B', 'alter' => -1),
		),
		11 => 'B'
	);

	public function __construct($pitch) {
		if (is_array($pitch)) {
			$this->properties['step'] = $pitch['step'];
			$this->properties['alter'] = $pitch['alter'];
			$this->properties['octave'] = $pitch['octave'];
		} else {
			$this->_resolvePitchString($pitch);
		}
	}

	function setProperty($name, $value) {
		$this->properties[$name] = $value;
	}

	public function isHeightless() {
		return $this->properties['octave'] == null;
	}

	public function toXML() {
		if ($this->properties['octave'] == null) {
			throw new Exception('heightless pitches can not be rendered as XML. Provide an "octave" property. '.print_r($this->properties, true));
		}

		$out = '<pitch>';

		$out .= '<step>' . $this->properties['step'] . '</step>';
		$out .= '<octave>' . $this->properties['octave'] . '</octave>';
		$out .= '<alter>' . $this->properties['alter'] . '</alter>';

		$out .= '</pitch>';

		return $out;
	}

	private function _resolvePitchString($pitch) {

		if (is_array($pitch)) {
			$this->properties['step'] = $pitch['step'];
			$this->properties['alter'] = $pitch['alter'];
			$this->properties['octave'] = $pitch['octave'];
			return;
		}

		preg_match('/([A-Ga-g+#-b]+?)(\d+)/', $pitch, $matches);
		$chroma = $matches[1];

		// there might be no octave part, if we're creating a heightless pitch, like "D#". Default to octave 4.
		$octave = null;
		if (!empty($matches[2])) {
			$octave = $matches[2];
		}

		preg_match('/([A-Ga-g]+?)(.*)/', $chroma, $matches);
		$step = $matches[1];
		switch ($matches[2]) {
			case '##':
			case '++':
				$alter = 2;
				break;
			case '#':
			case '+':
				$alter = 1;
				break;
			case 'b':
			case '-':
				$alter = -1;
				break;
			case 'bb':
			case '--':
				$alter = -2;
				break;
			default:
				$alter = 0;
		}
		$this->properties['step'] = $step;
		$this->properties['alter'] = $alter;
		$this->properties['octave'] = $octave;

		return true;
	}


	/**
	 * transposes a Pitch up or down by $interval semitones.
	 * @param  integer  $interval  a signed integer telling how many semitones to transpose up or down
	 * @param  integer  $preferredAlteration  either 1, or -1 to indicate whether the transposition should prefer sharps or flats.
	 * @return  null     
	 */
	public function transpose($interval, $preferredAlteration = 1) {
		$isHeightless = $this->isHeightless();
		// if the original number has a flat, the new one should use a flat too
		if (!empty($this->properties['alter'])) {
			$preferredAlteration = $this->properties['alter'];
		}
		if (!in_array($preferredAlteration, array(1, -1))) {
			$preferredAlteration = 1; // default to prefer sharps.
		}

		if ($isHeightless) {
			$this->properties['octave'] = 4; // just choose some arbitrary octave to use for calculation
		}

		$num = $this->_pitchToNoteNumber();
		$num += $interval;
		$this->_noteNumberToPitch($num, $preferredAlteration);

		if ($isHeightless) {
			// set it back the way it was
			$this->properties['octave'] = null;
		}
	}

	public function toString() {
		$str = '';
		$str .= $this->properties['step'];
		switch ($this->properties['alter']) {
			case 1:
				$str .= '#';
				break;
			case -1:
				$str .= '-';
				break;
			default:
				break;
		}
		$str .= $this->properties['octave'];
		return $str;
	}

	/**
	 * translates a pitch properties into a signed integer, abitrarily centered with zero on middle C
	 * @param $[name] [<description>]
	 * @return [type] [description]
	 */
	private function _pitchToNoteNumber() {
		$chromas = array('C' => 0, 'D' => 2, 'E' => 4, 'F' => 5, 'G' => 7, 'A' => 9, 'B' => 11);
		$num = ($this->properties['octave'] - 4) * 12;
		$num += $chromas[$this->properties['step']];
		$num += $this->properties['alter']; // adds a sharp or flat, e.g. 1 = sharp, -1 = flat
		return $num;
	}

	/**
	 * accepts a note number and sets the pitch properties
	 * @param  integer  $noteNumber          signed integer with origin zero as middle C4
	 * @param  integer  $preferredAlteration 1 for sharp or -1 for flats
	 * @return array                       returns a pitch array, containing step and alter elements.
	 */
	private function _noteNumberToPitch($noteNumber, $preferredAlteration = 1) {
		$chroma = $this->_truemod($noteNumber, 12); // chroma is the note pitch independent of octave

		$octave = (($noteNumber - $chroma) / 12) + 4;

		$this->properties['octave'] = $octave;

		if (is_array(self::$chromas[$chroma])) {
			if ($preferredAlteration === 1) {
				$this->properties['step'] = self::$chromas[$chroma][0]['step'];
				$this->properties['alter'] = self::$chromas[$chroma][0]['alter'];
			} else {
				$this->properties['step'] = self::$chromas[$chroma][1]['step'];
				$this->properties['alter'] = self::$chromas[$chroma][1]['alter'];
			}
		} else {
			$this->properties['step'] = self::$chromas[$chroma];
			$this->properties['alter'] = 0;
		}

	}

	/**
	 * required because PHP doesn't do modulo correctly with negative numbers.
	 */
	private function _truemod($num, $mod) {
		return ($mod + ($num % $mod)) % $mod;
	}


}
