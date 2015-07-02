<?php

class Pitch {
	
	public $step = 'C';
	public $alter = 0;
	public $octave = 4;

	private $_noteNumber = 0; // zero is middle C4

	public function __construct($pitch) {
		$this->_resolvePitchString($pitch);
	}

	public function toXML() {
		$out = '<pitch>';

		$out .= '<step>' . $this->step . '</step>';
		$out .= '<octave>' . $this->octave . '</octave>';
		$out .= '<alter>' . $this->alter . '</alter>';

		$out .= '</pitch>';

		return $out;
	}

	private function _resolvePitchString($pitch) {

		if (is_array($pitch)) {
			$this->step = $pitch['step'];
			$this->alter = $pitch['alter'];
			$this->octave = $pitch['octave'];
			return;
		}

		preg_match('/([A-Ga-g+#-b]+?)(\d+)/', $pitch, $matches);
		$chroma = $matches[1];
		$octave = $matches[2];
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
		$this->step = $step;
		$this->alter = $alter;
		$this->octave = $octave;

		return true;
	}


	public function transpose($interval, $preferredAlteration = 1) {
		// if the original number has a flat, the new one should use a flat too
		if (!empty($this->alter)) {
			$preferredAlteration = $this->alter;
		}

		$num = $this->_pitchToNoteNumber();
		$num += $interval;
		$this->_noteNumberToPitch($num, $preferredAlteration);
	}

	/**
	 * translates a pitch array into a signed integer, abitrarily centered with zero on middle C
	 * @param $[name] [<description>]
	 * @return [type] [description]
	 */
	private function _pitchToNoteNumber() {
		$chromas = array('C' => 0, 'D' => 2, 'E' => 4, 'F' => 5, 'G' => 7, 'A' => 9, 'B' => 11);
		$num = ($this->octave - 4) * 12;
		$num += $chromas[$this->step];
		$num += $this->alter; // adds a sharp or flat, e.g. 1 = sharp, -1 = flat
		return $num;
	}

	/**
	 * [_noteNumberToPitch description]
	 * @param  integer  $noteNumber          signed integer with origin zero as middle C4
	 * @param  integer  $preferredAlteration 1 for sharp or -1 for flats
	 * @return array                       returns a pitch array, containing step and alter elements.
	 */
	private function _noteNumberToPitch($noteNumber, $preferredAlteration = 1) {
		$chromas = array(
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

		// if the note is negative, octave it up until it's positive
		

		$chroma = $this->_truemod($noteNumber, 12); // chroma is the note pitch independent of octave

		$octave = (($noteNumber - $chroma) / 12) + 4;

		$this->octave = $octave;

		if (is_array($chromas[$chroma])) {
			if ($preferredAlteration === 1) {
				$this->step = $chromas[$chroma][0]['step'];
				$this->alter = $chromas[$chroma][0]['alter'];
			} else {
				$this->step = $chromas[$chroma][1]['step'];
				$this->alter = $chromas[$chroma][1]['alter'];
			}
		} else {
			$this->step = $chromas[$chroma];
			$this->alter = 0;
		}

	}

	/**
	 * required because PHP doesn't do modulo correctly with negative numbers.
	 */
	private function _truemod($num, $mod) {
		return ($mod + ($num % $mod)) % $mod;
	}


}
