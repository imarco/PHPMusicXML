<?php

class Barline {

	public $properties = array();

	public function __construct($options) {
		if (is_array($options)) {
			$this->properties['location'] = $options['location'];
			$this->properties['bar-style'] = $options['bar-style'];
			$this->properties['repeat'] = $options['repeat'];
			$this->properties['ending'] = $options['ending'];
		}
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


	function toXML() {
		$out = '';
		$out .= '<barline';
		if (isset($this->properties['location'])) {
			$out .= ' location="' . $this->properties['location'] . '"';
		}
		$out .= '>';
		if (isset($this->properties['bar-style'])) {
			$out .= '<bar-style>' . $this->properties['bar-style'] . '</bar-style>';
		}
		if (isset($this->properties['footnote'])) {
			$out .= '<footnote>' . $this->properties['footnote'] . '</footnote>';
		}
		if (isset($this->properties['ending'])) {
			$out .= '<ending';
			if (isset($this->properties['ending']['number'])) {
				$out .= ' number="' . $this->properties['ending']['number'] . '"';
			} else {
				$out .= ' number="1"';
			}
			if (isset($this->properties['ending']['type'])) {
				$out .= ' type="' . $this->properties['ending']['type'] . '"';
			}
			$out .= '>';
			$out .= '</ending>';
		}
		if (isset($this->properties['repeat'])) {
			$out .= '<repeat';
			if (isset($this->properties['repeat']['direction'])) {
				$out .= ' direction="' . $this->properties['repeat']['direction'] . '"';
			}
			if (isset($this->properties['repeat']['winged'])) {
				$out .= ' winged="' . $this->properties['repeat']['winged'] . '"';
			}
			$out .= '></repeat>';
		}

		$out .= '</barline>';
		return $out;

	}
}
