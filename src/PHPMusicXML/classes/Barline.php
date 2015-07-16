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
		if (isset($this->properties['ending'])) {
			$out .= '<ending>';
			if (isset($this->properties['ending']['type'])) {
				$out .= '<type>' . $this->properties['ending']['type'] . '</type>';
			}
			if (isset($this->properties['ending']['number'])) {
				$out .= '<number>' . $this->properties['ending']['number'] . '</number>';
			}
			$out .= '</ending>';
		}
		$out .= '</barline>';
		return $out;

	}
}
