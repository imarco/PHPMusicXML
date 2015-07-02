<?php

class Note {

	public $notations = array();
	public $articulations = array();
	public $attributes = array();

	function __construct($attributes = array()) {
		$this->attributes = $attributes;
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
			if ($this->attributes['pitch'] instanceof Pitch) {
				$pitch = $this->attributes['pitch'];
			} else {
				// we'll presume it's a string then
				$pitch = new Pitch($this->attributes['pitch']);
			}
			$out .= $pitch->toXML();
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
			foreach ($this->attributes['beam'] as $beam) {
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

