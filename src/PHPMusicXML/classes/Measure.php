<?php

class Measure {

	var $properties = array();
	var $layers = array();

	function __construct($properties = array()) {
		$this->properties = $properties;
	}

	function setProperty($name, $value) {
		$this->properties[$name] = $value;
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
		$out .= 'number="' . $number . '"';
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

		foreach ($this->properties['barline'] as $barline) {
			$out .= $barline->toXML();
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
	public function transpose($interval, $preferredAlteration) {
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

