<?php

// todo
// figure out a way to calculate the "edit distance" between two scales.
// https://en.wikipedia.org/wiki/Edit_distance
/*
e.g.

    110111010010 - 7 tones
    110010110010 - 6 tones
       ^ ^^     
    seems like three changes needed.
	however, we have a 0->1 change beside a 1->0 change. That's
	equivalent to "moving" one of the scale degrees by a semitone,
	so it counts as 1 operation, not two. 
	the distance here is 2

	101011000110 - 6 tones
	101011001001 - 6 tones
            ^^^^
            two moves

    110100101001 - 6 tones
    110101011001 - 7 tones
         ^^^ 
         one addition, one move

    110100101001 - 6 tones
    110101011001 - 7 tones
XOR
	000001110000 - an XOR shows which bits changed

	*/

$scale1 = '110111010010';
$scale2 = '110101010110';

function edit_distance($scale1, $scale2) {
	$changes = 0;
	// find changes that are opposite and adjacent
	$array1 = bits2array($scale1);
	$array2 = bits2array($scale2);

	for ($i=0; $i<count($array1); $i++) {
		if ($array2[$i] != $bit1) {
			// this is a changed bit
			if ($array1[$i+1] != $array2[$i+1] && $array1[$i] != $array1[$i+1]) {
				// there is a change adjacent and it is opposite
				// flip the bit so we don't count it again
				$array2[$i+1] = $array1[$i+1];
			}
			$changes++;
		}
	}
	return $changes;
}

function nbit($number, $n) {
	return ($number >> $n-1) & 1;
}

/**
 * converts a bitmask into an array
 * @param  [type] $value [description]
 * @return [type]        [description]
 */
function bits2array($value) {
	$array = array();
	$n = 0;
    while ($value) {
        $array[$n] += ($value & 1);
        $value = $value >> 1;
        $n++;
    }
    return $array;
}

/**
 * counts how many bits are on
 * @param  [type] $value [description]
 * @return [type]        [description]
 */
function getBitCount($value) {
    $count = 0;
    while($value) {
        $count += ($value & 1);
        $value = $value >> 1;
    }
    return $count;
}
