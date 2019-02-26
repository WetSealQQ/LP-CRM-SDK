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


$SDK = $FOLDER.'/zakaz/LP_CRM_SDK/lp_crm_sdk.php';
include $SDK;

$CONFIG = $FOLDER.'/zakaz/crm_config.php';
include $CONFIG;

$FILE_ZAKAZ = $FOLDER.'/zakaz/zakaz.php';
include $FILE_ZAKAZ;

?>
