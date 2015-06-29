<?php

class Measure {

	var $notes = array();
	var $attributes = array();

	function __construct($attributes = array()) {
		$this->attributes = $attributes;
	}

	function setAttribute($attribute) {

	}

	function toXML($number) {
		$out = '';

		$out .= '<measure number="' . $number . '">';

		$out .= '<attributes>';
		$out .= $this->_renderAttributes();
		$out .= '</attributes>';

		foreach ($this->notes as $note) {
			$out .= $note->toXML();
		}

		$out .= '</measure>';
		return $out;
	}

	private function _renderAttributes() {
		$out = '';

		$out .= '<divisions>'.$this->attributes['divisions'].'</divisions>';

		if (isset($this->attributes['key'])) {
			$out .= '<key>';
			if (isset($this->attributes['key']['fifths'])) {
				$out .= '<fifths>' . $this->attributes['key']['fifths'] . '</fifths>';
			}
			if (isset($this->attributes['key']['mode'])) {
				$out .= '<mode>' . $this->attributes['key']['mode'] . '</mode>';
			}
			$out .= '</key>';
		}

		if (isset($this->attributes['time'])) {
			$out .= '<time';
			if (isset($this->attributes['time']['symbol'])) {
				$out .= ' symbol="' . $this->attributes['time']['symbol'] . '"';
			}
			$out .= '>';
			if (isset($this->attributes['time']['beats'])) {
				$out .= '<beats>' . $this->attributes['time']['beats'] . '</beats>';
			}
			if (isset($this->attributes['time']['beat-type'])) {
				$out .= '<beat-type>' . $this->attributes['time']['beat-type'] . '</beat-type>';
			}
			$out .= '</time>';
		}

		if (isset($this->attributes['clef'])) {
			$out .= '<clef>';
			if (isset($this->attributes['clef']['sign'])) {
				$out .= '<sign>' . $this->attributes['clef']['sign'] . '</sign>';
			}
			if (isset($this->attributes['clef']['line'])) {
				$out .= '<line>' . $this->attributes['clef']['line'] . '</line>';
			}
			$out .= '</clef>';
		}

		return $out;
	}

	function addNote($note) {
		$this->notes[] = $note;
	}

	function clear() {
		$this->notes[] = array();
	}

	function backup($duration) {

	}

	function forward($duration) {

	}

	public function transpose($interval) {
		foreach ($this->notes as &$note) {
			$note->transpose($interval);
		}
	}

}

