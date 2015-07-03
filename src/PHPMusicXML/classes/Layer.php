<?php

class Layer {
	
	public $chords = array();

	public function __construct() {
	}

	function addNote($note) {
		$chord = new Chord();
		$chord->addNote($note);
		$this->addChord($chord);
	}

	function addChord($chord) {
		$this->chords[] = $chord;
	}

	function clear() {
		$this->chords[] = array();
	}

	function toXML() {
		$out = '';
		foreach($this->chords as $chord) {
			$out .= $chord->toXML();
		}
		return $out;
	}
}
