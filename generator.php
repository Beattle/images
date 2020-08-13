<?php

require 'vendor/autoload.php';
use Gregwar\Image\Image;
use RedBeanPHP\R;
R::setup( 'mysql:host=localhost;dbname=test',
	'vagrant', 'vagrant' ); //for both mysql or mariaDB


if(!empty($_GET['name']) && !empty($_GET['size'])){

	$db_size  = R::findOne( 'images', ' size = ? ', [ $_GET['size']] );
	$file = current(glob("gallery/$_GET[name].*"));

	header('Content-Type: image/jpeg');
	echo file_get_contents( Image::open($file)->resize($db_size['width'],$db_size['height'])->jpeg(100));
	die();
}

if(!empty($_POST['name']) && !empty($_POST['size']) ){
	$except_size = $_POST['size'] === 'min'?'mic':'big';
	$db_sizes = R::getAll('SELECT * FROM images WHERE size <> :size',
		[':size' =>$except_size]);
	foreach ($db_sizes as &$pic){
		$pic['link'] = 'generator.php?name='.$_POST['name'].'&size='.$pic['size'];
	}
	echo json_encode($db_sizes);
	die();
}

die(json_encode(['Некорректный запрос']));




