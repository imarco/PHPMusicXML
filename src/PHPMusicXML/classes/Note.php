<?php

class Note {

	public $notations = array();
	public $articulations = array();
	public $properties = array();

	function __construct($properties = array()) {
		// todo: cast the pitch as a Pitch immediately if it's constructed using a string like 'C4'
		if (!$properties['pitch'] instanceof Pitch) {
			$properties['pitch'] = new Pitch($properties['pitch']);
		}
		
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

	function setProperty($attribute, $value) {
		$this->properties[$attribute] = $value;
	}

	/**
	 * transposes the Pitch of a Note up or down by $interval semitones
	 * @param  integer  $interval  a signed integer telling how many semitones to transpose up or down
	 * @param  integer  $preferredAlteration  either 1, or -1 to indicate whether the transposition should prefer sharps or flats.
	 * @return  null     
	 */
	function transpose($interval, $preferredAlteration = 1) {
		$pitch = $this->properties['pitch'];
		$pitch->transpose($interval, $preferredAlteration = 1);
		$this->properties['pitch'] = $pitch;
	}

	function toXML() {
		$out = '';

		$out .= '<note';
		if (isset($this->properties['default-x'])) {
			$out .= ' default-x="' . $this->properties['default-x'] . '"';
		}
		if (isset($this->properties['default-y'])) {
			$out .= ' default-y="' . $this->properties['default-y'] . '"';
		}
		$out .= '>';

		if (!empty($this->properties['rest'])) {
			$out .= '<rest/>';
		}

		if (!empty($this->properties['chord'])) {
			$out .= '<chord/>';
		}

		if (!empty($this->properties['pitch'])) {
			if ($this->properties['pitch'] instanceof Pitch) {
				$pitch = $this->properties['pitch'];
			} else {
				// we'll presume it's a string then
				$pitch = new Pitch($this->properties['pitch']);
			}
			$out .= $pitch->toXML();
		}

		if (!empty($this->properties['duration'])) {
			$out .= '<duration>' . $this->properties['duration'] . '</duration>';
		}
		if (!empty($this->properties['voice'])) {
			$out .= '<voice>' . $this->properties['voice'] . '</voice>';
		}
		if (!empty($this->properties['type'])) {
			$out .= '<type>' . $this->properties['type'] . '</type>';
		}
		if (!empty($this->properties['dot'])) {
			$out .= '<dot/>';
		}

		if (!empty($this->properties['tie'])) {
			$out .= '<tie style="' . $this->properties['tie'] . '">';
			$this->notations['tie'] = $this->properties['tie'];
		}

		if (!empty($this->properties['staccato'])) {
			$this->notations['staccato'] = $this->properties['staccato'];
		}

		if (!empty($this->properties['stem'])) {
			$out .= '<stem';
			if (isset($this->properties['stem']['default-x'])) {
				$out .= ' default-x="' . $this->properties['stem']['default-x'] . '"';
			}
			if (isset($this->properties['stem']['default-y'])) {
				$out .= ' default-y="' . $this->properties['stem']['default-y'] . '"';
			}
			$out .= '>';
			if (isset($this->properties['stem']['direction'])) {
				$out .= $this->properties['stem']['direction'];
			}
			$out .= '</stem>';
		}
		if (!empty($this->properties['staff'])) {
			$out .= '<staff>' . $this->properties['staff'] . '</staff>';
		}

		if (!empty($this->properties['beam'])) {
			if (!is_array($this->properties['beam'])) {
				$this->properties['beam'] = array($this->properties['beam']);
			}
			foreach ($this->properties['beam'] as $beam) {
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

}

