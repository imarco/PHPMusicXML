<?php

class Clef {

	public $properties = array();

	public function __construct($clef) {
		if (is_array($clef)) {
			$this->properties['sign'] = $clef['sign'];
			$this->properties['line'] = $clef['line'];
		} else {
			$this->_resolveClefString($clef);			
		}
	}

	function setProperty($name, $value) {
		$this->properties[$name] = $value;
	}

	private function _resolveClefString($string) {
		$string = strtolower($string);
		switch ($string) {
			case 'treble':
				$this->properties['sign'] = 'G';
				$this->properties['line'] = 2;
				break;
			case 'bass':
				$this->properties['sign'] = 'F';
				$this->properties['line'] = 4;
				break;
			// todo: add more clefs here
			default:
				// todo: throw an exception here instead
				$this->properties['sign'] = 'G';
				$this->properties['line'] = 2;
				break;
		}
	}

	function toXML($num) {
		$out = '';

		$out .= '<clef number="' . $num . '">';
			$out .= '<sign>' . $this->properties['sign'] . '</sign>';
			$out .= '<line>' . $this->properties['line'] . '</line>';
		$out .= '</clef>';

		return $out;
	}

}
