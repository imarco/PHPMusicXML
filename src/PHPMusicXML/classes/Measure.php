<?php

class Measure {

	var $attributes = array();
	var $layers = array();

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

		foreach ($this->layers as $layer) {
			$out .= $layer->toXML();
		}

		$ticks = $this->attributes['divisions'] * $this->attributes['time']['beats'];
		$out .= '<backup>';
    	$out .= '<duration>'.$ticks.'</duration>';
  		$out .= '</backup>';

		$out .= '</measure>';
		return $out;
	}

	private function _renderAttributes() {
		$out = '';

		$out .= '<divisions>'.$this->attributes['divisions'].'</divisions>';
		$staves = 1;

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
			if (!is_array($this->attributes['clef'])) {
				$this->attributes['clef'] = array($this->attributes['clef']);
			}
			$num = 0;
			foreach ($this->attributes['clef'] as $clef) {
				$num++;
				$out .= '<clef number="' . $num . '">';
				if (isset($clef['sign'])) {
					$out .= '<sign>' . $clef['sign'] . '</sign>';
				}
				if (isset($clef['line'])) {
					$out .= '<line>' . $clef['line'] . '</line>';
				}
				$out .= '</clef>';
			}
			$staves = $num;
		}

		if (isset($this->attributes['staves'])) {
			$staves = $this->attributes['staves'];
		}
		$out .= '<staves>'.$staves.'</staves>';
		return $out;
	}

	function addLayer($layer) {
		$this->layers[] = $layer;
	}

	// function addNote($note) {
	// 	$this->notes[] = $note;
	// }

	// function clear() {
	// 	$this->notes[] = array();
	// }

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

