<?php 
	$url = $_SERVER['REQUEST_URI'];
	$data = explode('thumb',$url);
	$data = explode('/', $data[1]);

	$thumb = strip_tags(htmlspecialchars($data[5]));
	$tmp = explode('/',$thumb);

	$thumbPath = $data[1].'/'.$data[2].'/'.$data[3].'/'.$data[4];
	$thumbImage = '../'.$thumbPath.'/'.$thumb;

	list($width,$height) = explode('x',$data[4]);

	$image = realpath('../wp-content/uploads/'.$data[2].'/'.$data[3].'/'.$thumb);

	require('phpThumb/phpthumb.class.php');

	$phpThumb = new phpThumb();
	$phpThumb->setSourceFilename($image);
	$phpThumb->setParameter('w',$width);
	$phpThumb->setParameter('h',$height);
	$phpThumb->setParameter('f', 'jpg');
	$phpThumb->setParameter('q', 80);
	$phpThumb->setParameter('bg', 'FFFFFF');
	$phpThumb->setParameter('far', '1');
	$phpThumb->setParameter('zc','1');

	if (!$phpThumb->GenerateThumbnail()) {
		error('1 - cannot generate thumbnail');
	}

	if (!mkpath($thumbPath, true)) {
		error('2 - cannot create directory');
	}

	if (!$phpThumb->RenderToFile($thumbImage)) {
		// print_r($phpThumb->debugmessages);
		error('3 - cannot save thumbnail');

	}

	header('Location:'.$url);

	function error($error) {
		header("HTTP/1.0 404 Not Found");
		echo '<h1>Not Found</h1>';
		echo 'The requested URL /tonaislwebsite was not found on this server'.$error;
		exit();
	}

	function mkpath($path, $mode){
	    is_dir(dirname($path)) || mkpath(dirname($path), $mode);
	    return is_dir($path) || @mkdir($path,0777,$mode);
	}
?>