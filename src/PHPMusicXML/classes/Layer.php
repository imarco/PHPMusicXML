<?php


class Layer {
	
	public $notes = array();

	public function __construct() {
	}

	function addNote($note) {
		$this->notes[] = $note;
	}

	function clear() {
		$this->notes[] = array();
	}

	function toXML() {
		$out = '';
		foreach($this->notes as $note) {
			$out .= $note->toXML();
		}
		return $out;
	}
}
