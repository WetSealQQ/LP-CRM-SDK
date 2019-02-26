<?php
session_start();

include_once( 'product_config.php' );

if( !empty($dir_path) ){
	$FOLDER = $dir_path;
}else{
	$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
	$RT = explode('/', $DOCUMENT_ROOT);
	array_pop($RT);
	$FOLDER = implode('/', $RT);
}

$FILE_ZAKAZ = $FOLDER . '/zakaz/form-ok.php';
include $FILE_ZAKAZ;
?>
