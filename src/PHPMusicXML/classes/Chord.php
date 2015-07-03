<?php

class Chord {
	
	public $notes = array();

	public function __construct($notes = array()) {
		if (!is_array($notes)) {
			$notes = array($notes);
		}
		foreach ($notes as $note) {
			if (!$note instanceof Note) {
				$note = new Note($note);
			}
			$this->addNote($note); 
		}
	}

	function addNote($note) {
		if (!$note instanceof Note) {
			$note = new Note($note);
		}
		$this->notes[] = $note;
	}

	function clear() {
		$this->notes[] = array();
	}

	function toXML() {
		$out = '';
		$n = 0;
		foreach($this->notes as $note) {
			if (count($this->notes) > 1 && $n > 0) {
				$note->setAttribute('chord', true);
			}
			$out .= $note->toXML();
			$n++;
		}
		return $out;
	}
}

