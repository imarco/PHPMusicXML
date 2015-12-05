<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

?>
<html>
<head>
	<style>
	.red {color:red;}
	td{font-family: monospace;white-space: pre;}
	body {
		width:800px;
		margin:0 auto;
		font-family: helvetica;
	}
	.scale-player{
		color:blue;
		text-decoration: underline;
		cursor: pointer;
	}
	</style>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<!-- polyfill -->
	<script src="../demo/MIDI.js/inc/shim/Base64.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/inc/shim/Base64binary.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/inc/shim/WebAudioAPI.js" type="text/javascript"></script>
	<!-- midi.js package -->
	<script src="../demo/MIDI.js/js/midi/audioDetect.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/midi/gm.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/midi/loader.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/midi/plugin.audiotag.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/midi/plugin.webaudio.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/midi/plugin.webmidi.js" type="text/javascript"></script>
	<!-- utils -->
	<script src="../demo/MIDI.js/js/util/dom_request_xhr.js" type="text/javascript"></script>
	<script src="../demo/MIDI.js/js/util/dom_request_script.js" type="text/javascript"></script>


    <script src="../demo/vexflow/vexflow-debug.js"></script>


</head>


</head>
<body>

<?php
// see how easy it is to create a power set?
$allscales = range(0, 4095);

		// how about a little inline PHP to illustrate this. booyah!
		foreach ($allscales as $index => $set) {
			// remove if the root is not in it - ie the lowest bit is not turned on
			if ((1 & $index) == 0) {
				unset($allscales[$index]);
			}
		}

		// for convenience we'll populate the array with the set of tones that are turned on
		foreach ($allscales as $index => $set) {
			$allscales[$index] = array();
			$newset = array();
			// echo '<br/>'.$index.' - ';
			// echo decbin($index) . ' - ';

			for ($i = 0; $i < 12; $i++) {
				if ($index & (1 << ($i))) {
					$newset[] = $i;
				}
			}

			// echo '<br/>';
			// print_r($newset);
			$allscales[$index]['tones'] = $newset;
		}


		$maxinterval = 4;
		foreach ($allscales as $index => $set) {
			// Here is where that earlier step comes in handy. It's easier to analyze the precomputed array
			// than it is to do all this using the binary bitmask
			$setsize = count($set['tones']);
			for ($i = 0; $i < $setsize-1; $i++) {
				// find the distance between this note and the one above it
				if ($set['tones'][$i+1] - $set['tones'][$i] > $maxinterval) {
					unset($allscales[$index]);
				}
			}
			// and check the last one too
			if (12 - $set['tones'][$setsize-1] > $maxinterval) {
				unset($allscales[$index]);
			}
		}
		?>

<?php
foreach ($allscales as $index => $set) {

		echo '<canvas id="vf'.$index.'" width="500" height="100"></canvas>';
		echo '<script>
		var canvas = $("#vf' . $index . '")[0];
		var renderer = new Vex.Flow.Renderer(canvas, Vex.Flow.Renderer.Backends.CANVAS);
		var ctx = renderer.getContext();
		var stave = new Vex.Flow.Stave(10, 0, 450);
		stave.addClef("treble").setContext(ctx).draw();
		  var notes = [
		 ';
		foreach ($set['tones'] as $tone) {
			$pitch = tone2pitch($tone);
		    echo 'new Vex.Flow.StaveNote({ keys: ["' . $pitch['letter'] . '"], duration: "w" })';
		    if (!is_null($pitch['accidental'])) {
		    	echo '.addAccidental(0, new Vex.Flow.Accidental("'.$pitch['accidental'].'"))';
		    }
		    echo ',';
		    echo "\n";
		}
		echo '
		  ];
		  var voice = new Vex.Flow.Voice({
		    num_beats: '.count($set['tones']).',
		    beat_value: 1,
		    resolution: Vex.Flow.RESOLUTION
		  });
		  voice.addTickables(notes);
		  var formatter = new Vex.Flow.Formatter().joinVoices([voice]).format([voice], 400);
		  voice.draw(ctx, stave);

		  // var canvas = document.getElementById("vf'.$index.'");
		  // var img    = canvas.toDataURL("image/png");
		  // document.write(\'<img src="\'+img+\'"/>\');

			var canvasData = canvas.toDataURL("image/png");

			$.ajax({
				contentType: \'application/x-www-form-urlencoded\',
				url: \'canvas_save.php\',
				data: {
					\'canvas\': canvasData	,
					\'scaleNumber\': '.$index.'
				},
				\'type\':\'post\'
			});
		</script>
		';
}

function tone2pitch($tone) {
	$pitches = array(
		0 => array('letter' => 'c/4', 'accidental' => null),
		1 => array('letter' => 'c#/4', 'accidental' => '#'),
		2 => array('letter' => 'd/4', 'accidental' => null),
		3 => array('letter' => 'd#/4', 'accidental' => '#'),
		4 => array('letter' => 'e/4', 'accidental' => null),
		5 => array('letter' => 'f/4', 'accidental' => null),
		6 => array('letter' => 'f#/4', 'accidental' => '#'),
		7 => array('letter' => 'g/4', 'accidental' => null),
		8 => array('letter' => 'g#/4', 'accidental' => '#'),
		9 => array('letter' => 'a/4', 'accidental' => null),
		10 => array('letter' => 'a#/4', 'accidental' => '#'),
		11 => array('letter' => 'b/4', 'accidental' => null),
	);
	return $pitches[$tone];
}


?>
</body>
</html>















<?php
function find_imperfections($set) {
	$imperfections = array();
	foreach ($set as $pitch) {
		$fifthabove = ($pitch + 7) % 12;
		if (!in_array($fifthabove, $set)) {
			$imperfections[] = $pitch;
		}
	}
	return $imperfections;
}

function find_modes_and_symmetries($index) {
	$rotateme = $index;
	$modes = array();
//	$modes[] = $index;
	$symmetries = array();
	for ($i = 0; $i < 12; $i++) {
		$rotateme = rotate_bitmask($rotateme);
		if (($rotateme & 1) == 0) {
			continue;
		}
		$modes[] = $rotateme;
		if ($rotateme == $index) {
			if ($i != 11) {
				$symmetries[] = $i+1;
			}
		}
	}
	$output = array('modes' => $modes, 'symmetries' => $symmetries);
	return $output;
}

function rotate_bitmask($bits, $direction = 1) {
	if ($direction == 1) {
		$firstbit = $bits & 1;
		$bits = $bits >> 1;
		$bits = $bits | ($firstbit << 11);
		return $bits;
	} else {
		$firstbit = $bits & (1 << 11);
		$bits = $bits << 1;
		$bits = $bits & ~(1 << 12);
		$bits = $bits | ($firstbit >> 11);
		return $bits;

	}
}

function name($index) {
	$names = array(
		273 => 'augmented triad',
		585 => 'diminished seventh',
		1123 => 'iwato',
		1186 => 'insen',
		1365 => 'whole tone',
		1371 => array('altered', 'altered dominant', 'Super Locrian'),
		1387 => 'locrian',
		1389 => array('half diminished', 'Locrian ♮2'),
		1451 => 'phrygian',
		1453 => array('aeolian', 'natural minor'),
		1459 => array('phrygian dominant', 'spanish romani'),
		1485 => array('aolian #4', 'romani scale'),
		1707 => array('Phrygian ♮6', 'Dorian ♭2'),
		1709 => 'dorian',
		1717 => 'mixolydian',
		1741 => array('ukranian dorian','romanian scale','altered dorian'),
		1749 => array('acoustic', 'lydian dominant', 'Lydian ♭7', 'Mixolydian ♯4'),
		2257 => 'hirajoshi',
		2733 => array('heptatonia seconda', 'ascending melodic minor', 'jazz minor'),
		2741 => array('major' ,'ionian'),
		2773 => 'lydian',
		2483 => 'enigmatic',
		2457 => 'augmented',
		2477 => 'harmonic minor',
		2483 => 'flamenco mode',
		2509 => 'hungarian minor',
		2901 => array('lydian augmented', 'lydian ♯5'),
		2731 => 'major neapolitan',
		2475 => 'minor neapolitan',
		2483 => 'double harmonic',
		3669 => 'prometheus',
		1235 => 'tritone scale',
		1755 => array('octatonic', 'second mode of limited transposition'),
		3549 => 'third mode of limited transposition',
		2535 => 'fourth mode of limited transposition',
		2275 => 'fifth mode of limited transposition',
		3445 => 'sixth mode of limited transposition',
		3055 => 'seventh mode of limited transposition',
		3765 => 'bebop dominant',
		4095 => 'chromatic 12-tone',
	);
	if (isset($names[$index])) {
		if (is_array($names[$index])) {
			return implode(', ',$names[$index]);
		}
		return $names[$index];
	}
	return '';
}
