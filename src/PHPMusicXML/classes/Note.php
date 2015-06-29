<?php

class Note {

	public $notations = array();
	public $articulations = array();
	public $attributes = array();

	function __construct($attributes = array()) {
	}

	function setAttribute($attribute) {

	}

	function toXML() {
		$out = '';
		$out .= '<note';
		if (isset($this->attributes['default-x'])) {
			$out .= ' default-x="' . $this->attributes['default-x'] . '"';
		}
		if (isset($this->attributes['default-y'])) {
			$out .= ' default-y="' . $this->attributes['default-y'] . '"';
		}
		$out .= '>';

		if (!empty($this->attributes['rest'])) {
			$out .= '<rest/>';
		}

		if (!empty($this->attributes['chord'])) {
			$out .= '<chord/>';
		}

		if (!empty($this->attributes['pitch'])) {
			$out .= '<pitch>';
			$out .= $this->_resolvePitchAsXML($this->attributes['pitch']);
			$out .= '</pitch>';
		}

		if (!empty($this->attributes['duration'])) {
			$out .= '<duration>' . $this->attributes['duration'] . '</duration>';
		}
		if (!empty($this->attributes['voice'])) {
			$out .= '<voice>' . $this->attributes['voice'] . '</voice>';
		}
		if (!empty($this->attributes['type'])) {
			$out .= '<type>' . $this->attributes['type'] . '</type>';
		}
		if (!empty($this->attributes['dot'])) {
			$out .= '<dot/>';
		}

		if (!empty($this->attributes['tie'])) {
			$out .= '<tie style="' . $this->attributes['tie'] . '">';
			$this->notations['tie'] = $this->attributes['tie'];
		}

		if (!empty($this->attributes['staccato'])) {
			$this->notations['staccato'] = $this->attributes['staccato'];
		}

		if (!empty($this->attributes['stem'])) {
			$out .= '<stem';
			if (isset($this->attributes['stem']['default-x'])) {
				$out .= ' default-x="' . $this->attributes['stem']['default-x'] . '"';
			}
			if (isset($this->attributes['stem']['default-y'])) {
				$out .= ' default-y="' . $this->attributes['stem']['default-y'] . '"';
			}
			$out .= '>';
			if (isset($this->attributes['stem']['direction'])) {
				$out .= $this->attributes['stem']['direction'];
			}
			$out .= '</stem>';
		}
		if (!empty($this->attributes['staff'])) {
			$out .= '<staff>' . $this->attributes['staff'] . '</staff>';
		}

		if (!empty($this->attributes['beam'])) {
			if (!is_array($this->attributes['beam'])) {
				$this->attributes['beam'] = array($this->attributes['beam']);
			}
			foreach($this->attributes['beam'] as $beam) {
				$out .= '<beam';
				if (isset($beam['beam']['number'])) {
					$out .= ' number="' . $beam['beam']['number'] . '"';
				}
				$out .= '>';
				if (!empty($beam['beam']['type'])) {
					$out .= $beam['beam']['type'];
				}
				$out .= '</beam>';				
			}
		}

		if (!empty($this->notations) || !empty($this->articulations)) {
			$out .= '<notations>';

			foreach ($this->notations as $notationType => $n) {
				switch ($notationType) {
					case 'slur':
						$out .= '<slur';
						$out .= ' number="' . $n['number'] . '"';
						$out .= ' placement="' . $n['placement'] . '"';
						$out .= ' type="' . $n['type'] . '"';
						$out .= ' bezier-x="' . $n['bezier-x'] . '"';
						$out .= ' bezier-y="' . $n['bezier-y'] . '"';
						$out .= ' default-x="' . $n['default-x'] . '"';
						$out .= ' default-y="' . $n['default-y'] . '"';
						$out .= '/>';
						break;
					case 'tie':
						$out .= '<tied type="' . $n . '"/>';
						break;
					case 'tuplet':
						$out .= '<tuplet';
						$out .= ' bracket="' . $n['bracket'] . '"';
						$out .= ' number="' . $n['number'] . '"';
						$out .= ' placement="' . $n['placement'] . '"';
						$out .= ' type="' . $n['type'] . '"';
						$out .= '/>';
						break;
					case 'arpeggiate':
						$out .= '<arpeggiate';
						$out .= ' default-x="' . $n['default-x'] . '"';
						$out .= ' number="' . $n['number'] . '"';
						$out .= '/>';

						break;
				}
			}

			if (!empty($this->articulations)) {
				$out .= '<articulations>';
				foreach ($this->articulations as $articulation => $value) {
					switch ($articulation) {
						case 'staccato':
							$out .= ($value ? '<staccato/>' : '');
					}
				}
				$out .= '</articulations>';

			}
			$out .= '</notations>';
		}

		$out .= '</note>';
		return $out;
	}

	private function _resolvePitchAsXML($pitch) {
		$out = '';
		if (!is_array($pitch)) {
			// todo: make this intelligent enough to parse symbols like "C+4" and "D#5"
		}
		$out .= '<step>' . $pitch['step'] . '</step>';
		$out .= '<alter>' . $pitch['alter'] . '</alter>';
		$out .= '<octave>' . $pitch['octave'] . '</octave>';
		return $out;
	}

	public function transpose($interval, $preferredAlteration = 1) {
		if (!isset($this->attributes['pitch'])) {
			return null;
		}
		$pitch = $this->attributes['pitch'];

		// if the original number has a flat, the new one should use a flat too
		if (isset($this->attributes['pitch']['alter'])) {
			$preferredAlteration = $this->attributes['pitch']['alter'];
		}

		$num = $this->_pitchToNoteNumber($pitch);
		$num += $interval;
		$pitch = $this->_noteNumberToPitch($noteNumber, $preferredAlteration);

		return $pitch;
	}

	/**
	 * translates a pitch array into a signed integer, abitrarily centered with zero on middle C
	 * @param $[name] [<description>]
	 * @return [type] [description]
	 */
	private function _pitchToNoteNumber($pitch) {
		$chromas = array('C' => 0, 'D' => 2, 'E' => 4, 'F' => 5, 'G' => 7, 'A' => 9, 'B' => 11);
		$num = ($pitch['octave'] - 4) * 12;
		$num += $chromas[$pitch['step']];
		$num += $pitch['alter']; // adds a sharp or flat, e.g. 1 = sharp, -1 = flat
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

		$chroma = ($noteNumber % 12); // chroma is the note pitch independent of octave
		$octave = (($noteNumber - $chroma) / 12) + 4;

		$pitch = array(
			'octave' => $octave
		);

		if (is_array($chromas[$chroma])) {
			if ($preferredAlteration === 1) {
				$pitch = array_merge($pitch, $chromas[$chroma][0]);
			}
			$pitch = array_merge($pitch, $chromas[$chroma][1]);
		} else {
			$pitch['step'] = $chromas[$chroma];
		}

		return $pitch;

	}

}

