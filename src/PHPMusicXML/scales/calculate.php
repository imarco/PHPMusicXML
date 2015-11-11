<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

$notecount_stats = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0);
$imperfection_stats = array(
	1 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	2 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	3 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	4 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	5 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	6 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	7 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	8 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	9 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	10 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	11 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	12 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
);
$symmetry_stats = array(
	1 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	2 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	3 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	4 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	5 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	6 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	7 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	8 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	9 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	10 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	11 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
	12 => array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0, 12=>0),
);
$symmetry_sets = array(
	1 => array(),
	2 => array(),
	3 => array(),
	4 => array(),
	6 => array(),
);


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
	</style>
</head>
<body>
	<h1>Calculating Scales</h1>

	<p>This exploration of scales is based on work by William Zeitler, as published at <a href="http://allthescales.org/">http://allthescales.org/</a>.</p>

<p>The total number of all possible scales is the "power set" of the twelve-tone chromatic scale. The number of sets in a power set of size <em>n</em> is (2^n).</p>
<code>2 ^ 12 = 4096</code>
<p>so there are 4096 different possible subsets of 12 tones.</p>

<p>Thanks to the magic of binary math, we can represent these scales by a decimal number, from 0 to 4095. Converted to binary, 0 -> 000000000000 and 4095 -> 111111111111. When represented as bits it reads from right to left - the lowest bit is the root, and each bit going from right to left ascends by one semitone.</p>
<table class="table" border="1">
	<tr><th>decimal</th><th>binary</th><th></th></tr>
	<tr>
		<td>0</td>
		<td>000000000000</td>
		<td>no notes in the scale</td>
	</tr>
	<tr>
		<td>1</td>
		<td>000000000001</td>
		<td>just the root tone</td>
	</tr>
	<tr>
		<td>1365</td>
		<td>010101010101</td>
		<td>whole tone scale</td>
	</tr>
	<tr>
		<td>2741</td>
		<td>101010110101</td>
		<td>major scale</td>
	</tr>
	<tr>
		<td>4095</td>
		<td>111111111111</td>
		<td>chromatic scale</td>
	</tr>
</table>

<?php

// see how easy it is to create a power set?
$allscales = range(0, 4095);
?>

<p>Now we can whittle down the power set to exclude ones that we don't consider to be a legitimate "scale". We can do this easily with just two rules.</p>
<ul>
	<li>
		<p><b>A scale starts on the root tone.</b></p>
		<p>This means any set of notes that doesn't have that first bit turned on is not eligible. This cuts our power set in exactly half, leaving 2048 sets.</p>
		<?php
		// how about a little inline PHP to illustrate this. booyah!
		foreach ($allscales as $index => $set) {
			// remove if the root is not in it - ie the lowest bit is not turned on
			if ((1 & $index) == 0) {
				unset($allscales[$index]);
			}
		}
		echo '<p>scales remaining: '.count($allscales).'</p>';
		?>
	</li>

	<li>
		<p><b>A scale does not have any leaps greater than <em>n</em> semitones</b>.</p>
		<p>For the purposes of this exercise we are saying n = 4, a.k.a. a major third. Any collection of tones that has an interval greater than a major third is not considered a "scale". This configuration is consistent with Zeitler's constant used to generate his comprehensive list of scales.</p>

		<?php
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
		echo '<p>scales remaining: '.count($allscales).'</p>';
		?>
	</li>
</ul>

<?php
$howmany = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
foreach ($allscales as $scale) {
	$num = count($scale['tones']);
	$howmany[$num]++;
}
?>
<table class="table" border="1">
	<thead>
		<tr>
			<th>number of tones</th>
			<th>how many scales</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($howmany as $i => $n) {
			echo '<tr><td>' . $i . '</td><td>' . $n . '</td></tr>';
		}
		?>
	</tbody>
</table>



<hr/>



<h2>Modes</h2>
<p>To compute a mode of the current scale, we shift all the notes down one semitone. If the result starts on the root tone (meaning, it is a scale), then it is a mode of the original scale.</p>
<pre>
<span class="red">1</span>01010110101 - major scale, "ionian" mode
1<span class="red">1</span>0101011010 - rotated down 1 semitone - not a scale
01<span class="red">1</span>010101101 - rotated down 2 semitones - "dorian"
101<span class="red">1</span>01010110 - rotated down 3 semitones - not a scale
0101<span class="red">1</span>0101011 - rotated down 4 semitones - "phrygian"
10101<span class="red">1</span>010101 - rotated down 5 semitones - "lydian"
110101<span class="red">1</span>01010 - rotated down 6 semitones - not a scale
0110101<span class="red">1</span>0101 - rotated down 7 semitones - "mixolydian"
10110101<span class="red">1</span>010 - rotated down 8 semitones - not a scale
010110101<span class="red">1</span>01 - rotated down 9 semitones - "aeolian"
1010110101<span class="red">1</span>0 - rotated down 10 semitones - not a scale
01010110101<span class="red">1</span> - rotated down 11 semitones - "locrian"
</pre>
<p>When we do this to every scale, we see modal relationships between scales, and we also discover symmetries when a scale is a mode of itself on another degree.</p>

<?php
// aggregate stats

foreach ($allscales as $index => $set) {
	$note_count = count($set['tones']);
	$m = find_modes_and_symmetries($index);

	$allscales[$index]['modes'] = $m['modes'];
	$allscales[$index]['symmetries'] = $m['symmetries'];
	$imperfections = find_imperfections($set['tones']);
	$allscales[$index]['imperfections'] = $imperfections;

	$notecount_stats[$note_count]++;
	$imperfection_stats[$note_count][count($imperfections)]++;
	if (!empty($m['symmetries'])) {
		$initial_symmetry = $m['symmetries'][0];
		$symmetry_sets[$initial_symmetry][] = $index;
		foreach ($m['symmetries'] as $symmetry) {
			$symmetry_stats[$note_count][$symmetry]++;
		}
	}
}

$modelist = array(1=>array(),2=>array(),3=>array(),4=>array(),5=>array(),6=>array(),7=>array(),8=>array(),9=>array(),10=>array(),11=>array(),12=>array());
$all = $allscales;
while (count($all) > 0) {
	$set = array_pop($all);
	$modes = $set['modes'];
	$notecount = count($set['tones']);
	$modelist[$notecount][] = array_unique($modes);
	foreach ($modes as $mode) {
		unset($all[$mode]);
	}
}
?>
<h3>Modal Siblings</h3>
<p>We can present the list of all modes grouped in sets of modal siblings</p>

<table border="1">
	<thead>
		<tr>
			<th>Number of tones in the scale</th>
			<th>Number of modal families</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($modelist as $num => $list) {
			echo '<tr>';
			echo '<td>'.$num.'</td>';
			echo '<td>'.count($list).'</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>


<?php
foreach ($modelist as $num => $list) {
	if (count($list) == 0) {
		continue;
	}
	echo '<h4>'.$num.' tones</h4>';
	echo '<table class="table" border="1">';
	foreach ($list as $group) {
		echo '<tr>';
		echo '<td>';
		foreach ($group as $mode) {
			echo $mode;
			$name = name($mode);
			if (!empty($name)) {
				echo ' (<b>'.$name.'</b>)';
			}
			echo ', ';
		}
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
}
?>




<hr/>




<h2>Symmetry</h2>

<p>The set of 12 tones has 5 axes of symmetry. The twelve can be divided by 1, 2, 3, 4, and 6.</p>
<p>Any scale containing symmetry can reproduce its own tones by transposition, and is also called a "mode of limited transposition" (Messaien)</p>
<table class="table" border="1">
	<tr><th>axes of symmetry</th><th>interval of repetition</th><th>scales</th></tr>
	<tr>
		<td>1,2,3,4,5,6,7,8,9,10,11</td>
		<td>semitone</td>
		<td><?php echo implode(', ', $symmetry_sets[1]); ?></td>

<!-- 		<td>only the chromatic scale has this symmetry</td>
 -->	</tr>
	<tr>
		<td>2,4,6,8,10</td>
		<td>whole tone</td>
		<td><?php echo implode(', ', $symmetry_sets[2]); ?></td>

<!-- 		<td>1365 (only the whole tone scale has this symmetry)</td> -->
	</tr>
	<tr>
		<td>3,6,9</td>
		<td>minor thirds</td>
		<td><?php echo implode(', ', $symmetry_sets[3]); ?></td>
	</tr>
	<tr>
		<td>4,8</td>
		<td>major thirds</td>
		<td><?php echo implode(', ', $symmetry_sets[4]); ?></td>
		<!-- <td>0273, 0819, 1911, 2457, 3003</td> -->
	</tr>
	<tr>
		<td>6</td>
		<td>tritones</td>
		<td><?php echo implode(', ', $symmetry_sets[6]); ?></td>
		<!-- <td>0325, 0455, 0715, 0845, 0975, 1105, 1235, 1495, 1625, 1885, 2015, 2275, 2405, 2535, 2665, 2795, 3055, 3185, 3315, 3445, 3549, 3575, 3705, 3835, 3965</td> -->
	</tr>
</table>
<br/>
<table class="table" border="1"><thead><tr><th>number of notes in scale</th><th align="center" colspan="12"> Placement of Symmetries </th></tr>
  <tr>
 <?php
  	echo '<th></th>';
 	for ($i = 0; $i < 12; $i++) {
 		echo '<th>'.$i.'</th>';
 	}
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';

 for ($r = 1; $r <= 12; $r++) {
 	echo '<tr>';
 	echo '<td';
 	echo '>'.$r.'</td>';
 	for ($i = 0; $i < 12; $i++) {
 		echo '<td';
	 	if ($symmetry_stats[$r][$i] == 0) {
	 		echo ' style="color:#ccc;"';
	 	}
 		echo '>'.$symmetry_stats[$r][$i].'</td>';
 	}
 	echo '</tr>';
 }
?>
</tbody>
</table>



<hr/>




<h2>Imperfection</h2>
<p>Imperfection is a concept invented (so far as I can tell) by William Zeitler, to describe the presence or absense of perfect fifths in the scale tones. Any tone in the scale that does not have the perfect fifth above it represented in the scale is an "imperfect" tone. The number of imperfections is a metric that plausibly correlates with the perception of dissonance in a sonority.</p>
<p>The only scale that has no imperfections is the 12-tone chromatic scale.</p>

<p>This table differs from the one at <a href="allthescales.org">allthescales.org</a>, because this script does not de-duplicate modes. If an imperfection exists in a scale, it will also exist in all the sibling modes of that scale. Hence the single imperfect tone in the 11-tone scale is found 11 times (in 11 scales that are all modally related), whereas Zeitler only counts it as one.</p>
<table class="table" border="1">
  <thead>
  <tr>
	<th>number of notes in scale</th>
    <th align="center" colspan="11"> # of Imperfections </th>
  </tr>
  <tr>
<?php
  	echo '<th></th>';
 	for ($i = 0; $i < 7; $i++) {
 		echo '<th>'.$i.'</th>';
 	}
	echo '</tr>';

	for ($r = 1; $r <= 12; $r++) {
		echo '<tr>';
		echo '<td>'.$r.'</td>';
		for ($i = 0; $i < 7; $i++) {
			echo '<td';
			if ($imperfection_stats[$r][$i] == 0) {
				echo ' style="color:#ccc;"';
			}
			echo '>'.$imperfection_stats[$r][$i].'</td>';
		}
		echo '</tr>';
	}
?>
</table>




<hr/>



<h3>Truncation</h3>
<p>A subset of a scale produced by removing notes is known as a "truncation". The term is usually used in the context of Messaien's Modes of Limited Transposition, where a truncation involves removing notes in a way that preserves its symmetry.</p>
<p>(todo: find truncated relationships between modes)</p>


<hr/>



<h3>Intrascale Distance</h3>
<p>Another interesting relationship between two scales is similarity. You can measure how similar two scales are by using an algorithm similar to the Levenshtein Distance; determining how many additions, subtractions, or alterations would be needed to transform one into the other.</p>
<p>The operations that constitute a unit of distance would be:
	<ol>
		<li>Removing one tone from the scale</li>
		<li>Adding one tone to the scale</li>
		<li>Transposing one tone up or down a single semitone</li>
	</ol>
</p>
<p>We might guess that two scales that are similar will have a close feeling of sonority, but that's perceptually untrue; the "perceptual colour" of a scale depends very much upon which degrees of the scale are being altered, added, or omitted. Our ears are very sensitive to the difference between major and minor modes, which involve only a semitone shift of the third interval. Similarly, the consonance of a perfect fifth is very different from the dissonance of a diminished fifth, just one semitone below. The same power is weilded by the seventh interval of a scale, which can create quite a different sonority when flattened, because it puts the scale into a Dominant position awaiting resolution.</p>
<p>By contrast, we can alter the second, fourth and sixth degrees of a scale (often voiced as the 9th, 11th and 13th) with less effect on the listener's expectations.</p>

<p>The L() distance between every scale and every other scale, omitting itself, will produce a graph with (((1490)^2) / 2 - 1490) values... that's 1108560 distances.</p>
<p>Given a scale, we can generate all the scales with Levenshtein distance <i>n</i> by performing three operations on it: remove a note, add a note, or modify a note by one semitone, done to all the 12 tones, omitting any that don't apply or that produce duplicates or non-scales. If n > 1, then we recurse. The number of scales with L()=1 for any scale will be different depending on the number of tones in the scale and their placement - and whether operations produce duplicates. Precomputing the graph of L() for distance <i>n</i> will be easier than scanning the scales and measuring the L() distance between every scale and every other scale, though since it's a deterministic operation we only need to do that computation once and the results will be static forever.</p>
<p>So, let's do that <a href="levenshtein.php">here</a>.</p>
<hr/>



<?php
echo '<h3>All Scales</h3>';
echo '<table class="table" border="1">';
echo '<tr>';
echo '<th>Count</th>';
echo '<th>Index</th>';
echo '<th>Name</th>';
echo '<th>Tones</th>';
echo '<th>Bitmask</th>';
echo '<th>Note Count</th>';
echo '<th>Modes</th>';
echo '<th>Symmetry Axes</th>';
echo '<th>Imperfections</th>';
echo '</tr>';

$num = 1;
foreach ($allscales as $index => $set) {
	echo '<tr>';
	echo '<td>'.$num.'</td>';
	echo '<td>' . str_pad($index, 4, '0', STR_PAD_LEFT) . '</td>';
	echo '<td>' . name($index) . '</td>';

	echo '<td>';
	echo implode(',', $set['tones']);
	echo '</td>';

	echo '<td>' . str_pad(decbin($index), 12, '0', STR_PAD_LEFT).'</td>';
	echo '<td style="text-align:center;">'. count($set['tones']) .'</td>';
	echo '<td>' . implode(',', $set['modes']) . '</td>';
	echo '<td>' . implode(',', $set['symmetries']) . '</td>';
	echo '<td>' . implode(',', $set['imperfections']) . '</td>';
	echo '</tr>';
	$num++;
}
echo '</table>';







echo '<br/><br/>';
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
	$symmetries = array();
	for ($i = 0; $i < 12; $i++) {
		$rotateme = rotate_bitmask($rotateme);
		if (($rotateme & 1) == 0) {
			continue;
		} else {
			$modes[$i] = $rotateme;
			if ($rotateme == $index) {
				if ($i != 11) {
					$symmetries[] = $i+1;
				}
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
		1365 => 'whole tone',
		1371 => 'altered',
		1387 => 'locrian',
		1389 => 'half diminished',
		1451 => 'phrygian',
		1453 => 'aeolian',
		1709 => 'dorian',
		1717 => 'mixolydian',
		2741 => 'major,ionian',
		2773 => 'lydian',
		2509 => 'hungarian minor',
		2901 => 'lydian augmented',
		2731 => 'major neapolitan',
		2475 => 'minor neapolitan',
		3669 => 'prometheus',
		1235 => 'tritone scale',
		1755 => 'octatonic, second mode of limited transposition',
		3549 => 'third mode of limited transposition',
		2535 => 'fourth mode of limited transposition',
		2275 => 'fifth mode of limited transposition',
		3445 => 'sixth mode of limited transposition',
		3055 => 'seventh mode of limited transposition',
		3501 => 'natural minor',
		3765 => 'bebop dominant',
		4095 => 'chromatic 12-tone',
	);
	if (isset($names[$index])) {
		return $names[$index];
	}
	return '';
}
