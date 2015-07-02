<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

?><html>
<head>
    <meta name="viewport" content="initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 1.0, user-scalable = no">

<?php


require_once '../PHPMusicXML.php';

$divisions = 24; // we'll say we can divide the beats into 24 parts;
$beats = 4; // we'll say we can divide the beats into 24 parts;
$ticks = $divisions * $beats;

$measureOptions = array(
	'divisions' => 4,
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
		array(
			'sign' => 'G',
			'line' => 2
		),
		array(
			'sign' => 'F',
			'line' => 4
		),
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
);


$pitches = array('C4','C#4','D4','E-4','E4','F4','F#4','F##4','G4','A4','B4');


$score = new Score();
$part = new Part();
$measure = new Measure($measureOptions);
$layer = new Layer();

foreach ($pitches as $pitch) {
	$note = new Note(
		array(
			'pitch' => $pitch,
			'duration' => 4,
			'type' => 'quarter'
		)
	);
	$layer->addNote($note);
}
$measure->addLayer($layer);
$part->addMeasure($measure);

// 2nd measure


$measure = new Measure($measureOptions);
$layer = new Layer();
foreach ($pitches as $pitch) {
	$note = new Note(
		array(
			'pitch' => $pitch,
			'duration' => 4,
			'type' => 'quarter',
			'staff' => 1,
			'voice' => 1,
			'stem' => 'up'
		)
	);
	$layer->addNote($note);
}
$measure->addLayer($layer);

$layer = new Layer();
foreach ($pitches as $pitch) {

	$pitch = new Pitch($pitch);
	$pitch->transpose(-12);

	$note = new Note(
		array(
			'pitch' => $pitch,
			'duration' => 4,
			'type' => 'quarter',
			'staff' => 2,
			'voice' => 2,
			'stem' => 'down'
		)
	);
	$layer->addNote($note);
}
$measure->addLayer($layer);

$part->addMeasure($measure);



$score->addPart($part);

$xml2 = $score->toXML();

?>
<script src="vexflow/jquery.js"></script>
<script src="vexflow/vexflow-debug.js"></script>

    <script>
	$(document).ready(function() {

		var xml2 = '<?php echo $xml2; ?>';
		var doc = null;
		doc = new Vex.Flow.Document(xml2);
		doc.getFormatter().setWidth(800).draw($("#viewer2")[0]);

	});

    </script>
    <style>
      #viewer {
        width: 100%;
        overflow: hidden;
      }
    </style>
  </head>
  <body>

  	<h3>Diminished Scale, all keys</h3>

    <textarea style="width:800px;height:400px;">
    	<?php echo htmlspecialchars($xml2); ?>
    </textarea>
    <div id="viewer2">
      <p>Please enable JavaScript to use the viewer.</p>
    </div>
  </body>
</html>
