<?php 

$data = $_REQUEST;

$order_folder = $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']); 

/*
$landing_folder = @$data['server_name'] . dirname(@$data['php_self']); 
*/
$isHttps = !empty( $_SERVER['HTTPS'] ) && 'off' !== strtolower($_SERVER['HTTPS']);

$protocol = !!$isHttps ? "https" : "http";

$site_name = "{$protocol}://{$order_folder}/";


$crm = new Seal\lp_crm_sdk( $CRM_key, $url_crm );
// $crm = new Seal\lp_crm_sdk( '234234234234234', "http://testcrm.lp-crm.biz");


// устанавливает email на который нужно отправить данные о заказе
$crm->setMail( $email );

// принудительно устанавливаем название сайта в црм
$crm->setSiteName($order_folder);

// Добавление заказа в CRM
$response = $crm->addNewOrder( $data );



// возвращает масив ошибок (для метода перед getError)
// или array();
$err = $crm->getError();


if( empty($err) || $err[0]['id'] == 5 ){
    // устанавливает редирект 
    $crm->setRedirectURL("{$site_name}form-ok.php", array('name'=> $data['name'], 'phone'=> $data['phone']));
    
    die();
}else{
/*    echo "<pre>";
    var_dump( $crm->debug() );*/
    echo '<h1 style="color:red;">Произошла ошибка!</h1>';
}


?>