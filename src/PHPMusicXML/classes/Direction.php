<?php

class Direction {

	public $properties = array();

	function __construct($properties = array()) {
	}

	function setProperty($name, $value) {
		$this->properties[$name] = $value;
	}

	function toXML() {
		return 'it works!';
	}

}
