<?php
	// requires php5
	define('UPLOAD_DIR', 'images/');

	if (!file_exists(UPLOAD_DIR)) {
		mkdir(UPLOAD_DIR);
	}

	$img = $_POST['canvas'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR . $_POST['scaleNumber'] . '.png';
	$success = file_put_contents($file, $data);
	print $success ? $file : 'Unable to save the file.';
