<?php
use ianring\PHPMusicXML;

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<h1>Demo</h1>
<p>This script demonstrates the things that this library is capable of.</p>
<?php
//require_once SITE_ROOT . '/current/vendor/autoload.php';

require_once '../PHPMusicXML.php';

$score = new Score();

$measure = new Measure(
	array(
		'divisions' => 24,
		'key' => array(
			'fifths' => 3,
			'mode' => 'major'
		),
		'time' => array(
			'symbol' => 'common', // omit this to represent a normal signature
			'beats' => 4,
			'beat-type' => 4
		),
		'clef' => array(
			'sign' => 'G',
			'line' => 2
		),
		'direction' => array(
			'placement' => 'below',
			'direction-type' => array(
				'words' => array(
					'default-x' => 0,
					'default-y' => 15,
					'font-size' => 10,
					'font-weight' => 'bold',
					'font-style' => 'italic',
					'text' => 'Andantino'
				)
			),
			'staff' => 1,
			'sound-dynamics' => 40
		),
		'barline' => array(
			array(
				'location' => 'left',
				'bar-style' => 'light-heavy',
				'repeat' => 'forward',
				'ending' => array(
					'type' => 'stop',
					'number' => 1
				)
			),
			array(
				'location' => 'right',
				'bar-style' => 'heavy-light',
				'repeat' => 'backward',
				'ending' => array(
					'type' => 'stop',
					'number' => 1
				)
			)
		),
		'implicit' => true,
		'number' => 1,
		'width' => 180
	)
);

// pitch can be any of the following 
// C4, c+4, C+4, C#4, c#4, c-4, C-4, Cb4, 
// array('step'=>'C','alter'=>-1,'octave'=>4)

$note = new Note(
	array(
		'pitch' => 'C4',
		'duration' => 4,
		'type' => 'whole'
	)
);

$note = new Note(
	array(
		'rest' => true,
		'dot' => true,
		'staccato' => true,
		'chord' => false,

		'voice' => 1,
		'staff' => 1, // useful for multistaff parts like piano

		'pitch' => 'C4', // there are many ways to notate this - see above
		'duration' => 4,
		'type' => 'quarter',
		'tied' => 'stop',

		'tuplet' => array(
			'bracket' => 'no',
			'number' => 1,
			'placement' => 'above',
			'type' => 'start'
		),
		'stem' => array( 
			'default-y' => 3, // for up or down stems, measured in 1/10 of an interline space from top of staff
			'direction' => 'up', // up, down, none, or double
		),
		'beam' => array(
			// this can be a single beam, or an array of beams
			array(
				'number' => 1,
				'type' => 'begin'  // begin, continue, end, forward hook, and backward hook
			),
			array(
				'number' => 1,
				'type' => 'begin'  // begin, continue, end, forward hook, and backward hoo
			)
		),
		'accidental' => array(
			'courtesy' => true,
			'editorial' => null,
			'bracket' => false,
			'parentheses' => true,
			'size' => false,
			'type' => 'natural' // sharp, flat, natural, double-sharp, sharp-sharp, flat-flat, natural-sharp, natural-flat, quarter-flat, quarter-sharp, three- quarters-flat, and three-quarters-sharp
		)
	)
);


// a quarter-note triplet
$note = new Note(
	array(
		'chord' => false, // this indicates that this note is synchonous with the previous one
		'dot' => false,
		'pitch' => 'E4',
		'duration' => 8,
		'type' => 'quarter',
		'time-modification' => array(
			'actual-notes' => 3,
			'normal-notes' => 2,
			'normal-type' => 'eighth' // this tells the parser that it's a quarter note part of an eighth-note triplet
		)
	)
);

$measure->addNote($note);
$note->transpose(2); // transposes the note down 4 semitones
$measure->addNote($note);
$note->transpose(2); // transposes the note down 4 semitones
$measure->addNote($note);


$note = new Note(
	array(
		'rest' => array(
			'measure' => true // this means the voice is resting for the entire measure
		),
		'duration' => 8,
		'voice' => 1
	)
);


$note = new Note(
	array(
		'chord' => true, // this indicates that this note is synchonous with the previous one
		'pitch' => 'C4',
		'duration' => 4,
		'type' => 'whole'
	)
);

$direction = new Direction(
	array(
		'placement' => 'above',
		'direction-type' => array(
			'wedge' => array(
				'default-y' => 20,
				'spread' => 0,
				'type' => 'crescendo' // crescendo, diminuendo, or stop
			)
		),
		'offset' => -8
	)
);

$direction = new Direction(
	array(
		'placement' => 'above',
		'direction-type' => array(
			'words' => array( // words, dynamics, wedge, segno, coda, rehearsal, dashes, pedal, metronome, and octave-shift
				'default-x' => 15, // units of tenths of interline space
				'default-y' => 15, // units of tenths of interline space
				'font-size' => 9,
				'font-style' => 'italic',
				'words' => 'dolce'
			)
		),
		'offset' => -8
	)
);


// many direction-types can go together into one direction
$direction = new Direction(
	array(
		'placement' => 'above',
		'direction-type' => array(
			array(
				'words' => array(
					'default-x' => 15, // units of tenths of interline space
					'default-y' => 15, // units of tenths of interline space
					'font-size' => 9,
					'font-style' => 'italic',
					'words' => 'dolce'
				)
			),
			array(
				'wedge' => array(
				)
			),
		),
		'offset' => -8
	)
);



$measure->addNote($note);

$note = new Note(
	array(
		'pitch' => array(
			'step' => 'C',
			'alter' => -1,
			'octave' => 4
		),
		'duration' => 4,
		'tie' => 'start',
		'type' => 'whole',
		'lyric' => array(
			'syllabic' => 'end',
			'text' => 'meil',
			'extend' => true
		)
	)
);

$note->transpose(4); // transposes the note up 4 semitones
$measure->addNote($note);
$note->transpose(-4); // transposes the note down 4 semitones
$measure->addNote($note);

// backup and forward lets us add "layers" to a measure with independent voicing
// $duration = 16;
// $measure->backup($duration);
// $measure->forward($duration);

$part = new Part();
$part->addMeasure($measure);

$score->addPart($part);

echo '<pre>';
echo htmlspecialchars($score->toXML('partwise'));
echo '</pre>';



echo $score->toPNG();

echo $score->toPDF();
