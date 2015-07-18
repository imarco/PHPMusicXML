# PHP-MusicXML
A library for generating and outputting MusicXML using PHP

# Resources
 - http://www.musicxml.com/tutorial/
 - http://www.musicxml.com/UserManuals/MusicXML/MusicXML.htm
 - https://github.com/0xfe/vexflow
 - https://en.wikipedia.org/wiki/MusicXML
 - https://www.soundslice.com/musicxml-viewer/
  


# Classes

## Pitch
A pitch object has three properties: step, octave, and alter. These are directly mapped to the MusicXML elements that represent the notation of a pitch, so there is a difference between B flat and A sharp.

You can create a pitch like this:

```php
$pitch = new Pitch(array(
	'step' => 'C',
	'octave' => '4',
	'alter' => -1
));
```

You can also use this shorthand:

```php
$p = new Pitch('C-4');
```

Pitches are cool because you can do transposition on them:

```php
$p->transpose(6); // transposes the pitch up 6 semitones
```

A transposition might result in an altered note, e.g. if $pitch is 'C4', $pitch->transpose(-4) will result in either a G sharp or an A flat. To resolve this ambiguity, transpose() accepts a second argument for its preferred alteration. If omitted, the alteration will be the same as the original note if there is one.

```php
$p->transpose(6, 1); // will create an F sharp.
$p->transpose(6, -1); // will create a G flat.
```

In MusicXML, pitch is described using "step", "alter", and "octave", whereas in music analysis, pitch is often described using "chroma" and "height". Chroma is the chromatic position in the 12-tone scale, for which we have names like "C sharp". Whereas music notation cares about "step" and "alter" to know the difference between the visual representation of a B flat or A sharp, chroma doesn't carry information about notation, and can be represented as a number from 0 to 12.
"Height" is the octave in which the chroma resides. Two pitches can have the same chroma in different heights (e.g. C#4 and C#5), and notes can of course have the same height and different chromas (e.g. D4 and F4).
A Pitch might be "heightless", in which case it represents a Chroma with no "octave" property. Heightlessness is a useful concept in music analysis, because it allows you to examine the use of chromas without regard for their octave. Transposing a heightless pitch will result in another heightless pitch. Heightless pitches can be assigned to Notes (which would be a weird thing to do, but it is possible), but they can not be rendered as XML, so if you're mixing heightless pitches into music that is being rendered as XML, be careful.
A heightless pitch may seem like a theoretical construct, but it is in fact possible to generate sounds that have chroma but no height. These are called "Shepard's Tones", and consist of layereed harmonics balanced in a way that makes the pitch perceptually heightless.


## Note

create a note by setting its properties:

```php
$note = new Note(
	array(
		'pitch' => new Pitch('C4'),
		'duration' => 4,
		'type' => 'quarter'
	)
);
```

A note can be transposed too.

```php
$note->transpose(5, -1); // transpose up 5 semitones, preferring flats.
```

## Chord

A chord is a group of notes that sound simultaneously. 

```php
$chord = new Chord(array($note1, $note2, $note3));
$chord->addNote($note4);
```

Chords may be transposed:

```php
$chord->transpose(5, -1); // transpose up 5 semitones, preferring flats.
```


## Layer

A layer allows multiple sequences of chords to share a single staff. This is common for notation of counterpoint, and for polyphonic instruments like the piano. Layers have no special properties of their own.

```php
$layer = new Layer();
```

You can add a chord, or a note.

```php
$layer->addChord($chord);
$layer->addNote($note);
```

You can add a Note directly to a Layer, but internally it's creating a Chord with one Note in it, and adding that.

Layers may be transposed:

```php
$layer->transpose(5, -1); // transpose up 5 semitones, preferring flats.
```

## Measure

A measure is a collection of layers, together describing one discrete duration of music defined by the time signature. 

```php
$measure = new Measure($properties);
$measure->addLayer($layer);
```

A measure has many properties:
<dl>
	<dt>number</dt>
	<dd>the sequential number of this measure, starting from 1</dd>

	<dt>division</dt>
	<dd>the number of divisions of a single beat. If the time signature is 4/4, then one beat is a quarter note. If your measure contains any sixteenth notes, you will need the division to be at least 4. If you also use eighth-note triplets, then the division must be 12. The number of "ticks" (discrete equal time durations, as in a ticking clock) in a measure is beats * divisions, and each note in your measure must be assigned to one of the ticks.</dd>

	<dt>key</dt>
	<dd>Must be a Key object, as described below</dd>

	<dt>time</dt>
	<dd>has properties "beats", and "beat-type", and optionally "symbol".</dd>

	<dt>clef</dt>
	<dd>must be a Clef object, as described below</dd>

	<dt>barline</dt>
	<dd>
		may be a single barline or an array of barlines, if the measure has multiple staves.	
		each must be a Barline object, as described below
	</dd>

	<dt>implicit</dt>
	<dd>boolean. Defaults to false if omitted. If true, then the measure won't be counted with a measure number, as in pickup measures and the last half of mid-measure repeats.</dd>

	<dt>non-controlling</dt>
	<dd>boolean, defaults to false. If true, the left barline of this measure does not coincide with the left barline of measures in other parts.</dd>

	<dt>width</dt>
	<dd>to explicitly set the width of a measure</dd>

</dl>

### Key

The Key class builds a Key, to use as the 'key' property in your Measure. It is represented using the properties "fifths" and "mode". In major mode, C major is 0 fifths, G is 1, D is 2... F is -1, B flat is -2. The class provides a shorthand for building a key, by constructing it with a string (like "F minor") instead of an array. 

The following two lines of code accomplish the same thing:
```php
$key = new Key(array('fiths' => 3, 'mode' => 'major'));
```
```php
$key = new Key('D major'));
```

As with most objects in PHPMusicXML, there is a setter for its properties. 
```php
$key->setProperty('mode', 'minor');
$key->setProperty('fifths', 0);
```

### Clefs

There is a Clef class which provides a shorthand for creating common clefs. The clef has two properties: sign and line. 

```php
// this creates a treble clef
$clef = new Clef(array('sign' => 'G', 'line' => 4));
```

There are a set of shorthands defined for common clefs:

```php
$clef = new Clef('treble');
$clef = new Clef('bass');
$clef = new Clef('alto');
$clef = new Clef('tenor');
```

### Staves

A measure may contain multiple staves. Each of these has its own clef. The number of staves is controlled by the number of clefs. To override the number of staves for complex notation situations, put a number in the "staves" property. 

```php
// This measure will have two staves, without having to set the "staves" property
$measure->setProperty('clef', array(
	new Clef('treble'),
	new Clef('bass')
));

// explicitly use a different number of staves
$measure->setProperty('staves', 3);
```

### Barlines

The Barline class has four main properties: "location", "bar-style", "repeat", and "ending". "repeat" has sub-properties "direction" and "winged". "ending" has sub-properties "type" and "number".

```php
$barline = new Barline(
	array(
		'location' => 'right',
		'bar-style' => 'heavy-light',
		'repeat' => array(
			'direction' => 'backward',
			'winged' => 'none'
		),
		'ending' => array(
			'type' => 'stop',
			'number' => 2
		)
	)
);
```

Barlines are added to a Measure by setting to the "barline" property.
```php
$measure->setProperty('barline', $barline);
```


## Part

A part is a sequence of measures intended to be played by the same instrument. Parts have one requied property, "name". The name is required by the constructor.

```php
$part = new Part('Pianoforte');
$part->addMeasure($measure);
```

## Score

A score is the collection of all the parts being played by all the instruments. It is the highest-level element in MusicXML, and so it's usually where you will call the toXML() method.

```php
$score = new Score();
$score->addPart($part);

echo $score->toXML();
```


# Helper Classes

PHPMusicXML contains some interesting helper classes, that have no influence on typesetting scores, but which have application for music analysis or transformation.

## Scale
The scale class gives you access to a collection of common scales, by name. A Scale object has two properties; one is the "root" which must be a Pitch object, and the other is the "mode".
You can create a scale using shorthand, 

```php
$scale = new Scale('D# Phrygian');

$scale = new Scale(array(
	'root' => new Pitch('D#'),
	'mode' => 'phrygian'
));

$scale = new Scale(array(
	'root' => 'D#4',
	'mode' => 'Phrygian',
	'direction' => 1
));

```
Mode names are case-insensitive, but spelling counts. The class understands aliases for modes with more than one common name, e.g. "ionian" is the same as "major".

Note that the root can be a heightless pitch to describe a heightless Scale, or it may be a pitch with an octave to anchor the scale at a certain height.

Scales can also have an optional property of "direction", which is either 1 (ascending) or -1 (descending).
```php
$scale->setProperty('direction', -1);
```

Scale objects are used for autoTune(), can be returned by functions that do analysis, and can be used to render sequences of Notes.


