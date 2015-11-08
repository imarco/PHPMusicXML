<?php

class Measure {

	var $properties = array();
	var $layers = array();

	function __construct($properties = array()) {
		$this->properties = $properties;

		// set defaults
		if (empty($properties['time'])) {
			$this->properties['time'] = array(
				'symbol' => 'common',
				'beats' => 4,
				'beat-type' => 4
			);
		}
		if (empty($this->properties['clef'])) {
			$this->properties['clef'] = new Clef('treble');
		}
		if (empty($this->properties['key'])) {
			$this->properties['key'] = new Key('C major');
		}
		if (empty($this->properties['divisions'])) {
			$this->properties['divisions'] = 24;
		}

		// this line allows us to chain commands!
		return $this;
	}

	function setProperty($name, $value) {
		$this->properties[$name] = $value;
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

	function toXML($number) {
		$out = '';

		$out .= '<measure ';
		if (isset($this->properties['implicit'])) {
			$out .= ' implicit="' . ($this->properties['implicit'] ? 'yes' : 'no') . '"';
		}
		if (isset($this->properties['non-controlling'])) {
			$out .= ' non-controlling="' . ($this->properties['non-controlling'] ? 'yes' : 'no') . '"';
		}
		$out .= ' number="' . $number . '"';
		$out .= '>';

		$out .= '<attributes>';
		$out .= $this->_renderproperties();
		$out .= '</attributes>';

		$ticks = $this->properties['divisions'] * $this->properties['time']['beats'];

		$i = 0;
		foreach ($this->layers as $layer) {
			$out .= $layer->toXML();
			$i++;

			if ($i < count($this->layers)) {
				$out .= '<backup>';
		    	$out .= '<duration>'.$ticks.'</duration>';
		  		$out .= '</backup>';
				$out .= '<forward>';
		    	$out .= '<duration>0</duration>';
		  		$out .= '</forward>';
			}
		}

		if (!empty($this->properties['barline'])) {
			foreach ($this->properties['barline'] as $barline) {
				if (!$barline instanceof Barline) {
					$barline = new Barline($barline);
				}
				$out .= $barline->toXML();
			}
		}

		$out .= '</measure>';
		return $out;
	}

	/**
	 * renders the object's properties as XML
	 * @return  string  the XML
	 */
	private function _renderproperties() {
		$out = '';

		$out .= '<divisions>'.$this->properties['divisions'].'</divisions>';
		$staves = 1;

		if (isset($this->properties['key'])) {
			$key = $this->properties['key'];
			if (!$key instanceof Key) {
				$key = new Key($key);
			}
			$out .= $key->toXML();
		}

		if (isset($this->properties['time'])) {
			$out .= '<time';
			if (isset($this->properties['time']['symbol'])) {
				$out .= ' symbol="' . $this->properties['time']['symbol'] . '"';
			}
			$out .= '>';
			if (isset($this->properties['time']['beats'])) {
				$out .= '<beats>' . $this->properties['time']['beats'] . '</beats>';
			}
			if (isset($this->properties['time']['beat-type'])) {
				$out .= '<beat-type>' . $this->properties['time']['beat-type'] . '</beat-type>';
			}
			$out .= '</time>';
		}

		$clefs = '';
		if (isset($this->properties['clef'])) {
			if (!is_array($this->properties['clef'])) {
				$this->properties['clef'] = array($this->properties['clef']);
			}
			$num = 0;
			foreach ($this->properties['clef'] as $clef) {
				$num++;
				if (!$clef instanceof Clef) {
					$clef = new Clef($clef);
				}
				$clefs .= $clef->toXML($num);
			}
			$staves = $num;
		}

		if (isset($this->properties['staves'])) {
			$staves = $this->properties['staves'];
		}

		// output staves first, and then clefs.
		$out .= '<staves>'.$staves.'</staves>';
		$out .= $clefs;

		return $out;
	}

	function addLayer($layer) {
		$this->layers[] = $layer;
	}

	/**
	 * adds a note to a measure. Assumes that it should be added to the first layer, and if there are no layers in
	 * the measure one should be created. The note is enclosed in its own Chord object.
	 * @param Note $note the note to add
	 */
	function addNote($note) {
		if (!count($this->layers)) {
			$layer = new Layer();
			$this->addLayer($layer);
		} else {
			$layer = $this->layers[0];
		}
		$layer->addNote($note);
	}

	/**
	 * adds a  bunch of notes all at once.
	 * @param array $array an array of Notes
	 */
	function addNotes($array) {
		foreach($array as $note) {
			$this->addNote($note);
		}
	}

	function backup($duration) {

	}

	function forward($duration) {

	}

	/**
	 * transposes all the notes in this measure by $interval
	 * @param  integer  $interval  a signed integer telling how many semitones to transpose up or down
	 * @param  integer  $preferredAlteration  either 1, or -1 to indicate whether the transposition should prefer sharps or flats.
	 * @return  null     
	 */
	public function transpose($interval, $preferredAlteration = 1) {
		foreach ($this->layers as &$layer) {
			$layer->transpose($interval);
		}
	}

	/**
	 * using the measure's own Key, will quantize all the notes to be part of a given scale.
	 * If scale is omitted, will use the scale implied by the Key's "mode" property.
	 * @param   $scale  a Scale object
	 * @return null
	 */
	public function autoTune($scale = null) {
		// todo: figure out the key and scale, based on the measure's Key property
		foreach ($this->layers as &$layer) {
			$layer->autoTune($scale);
		}
	}


	/**
	 * analyze the current measure, and return an array of all the Scales that its notes fit into.
	 * @param  Pitch  $root  if the root is known and we only want to learn about matching modes, provide a Pitch for the root.
	 * @return [type] [description]
	 */
	public function getScales($root = null) {
		$scales = Scale::getScales($this);
	}

	/**
	 * returns an array of Pitch objects, for every pitch of every note in the measure.
	 * @param  boolean  $heightless  if true, will return heightless pitches all mudul to the same octave. Useful for
	 *                              analysis, determining mode etc.
	 * @return array  an array of Pitch objects
	 */
	public function getAllPitches($heightless = false) {
		$pitches = array();
		foreach ($this->layers as $layer) {
			$layerPitches = $layer->getAllPitches($heightless);
			$pitches = array_merge_recursive($pitches, $layerPitches);
		}
		return $pitches;
	}

}

