<?php 
session_start();

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);



include_once("../src/lp_crm_sdk.php");




/*header('Content-Type: text/html; charset=utf-8');
*/
$crm = new Seal\lp_crm_sdk( '9ef4d26ea5e96179a98c8d8502cb4c34', "http://testcrm.lp-crm.top", '178.213.2.228'/*, array("5"=>"fd71")*/ );


//$crm->setRedirectURL("https://google.com");


$crm->setSiteName("https://verygoodsite.com");

$crm->setIP("0.0.0.0.1");

$crm->setMail("if1if2if3@yandex.ru");




$resp = $crm->addNewOrder( 
	array(
		'name' => 'test12',
		'phone' => '33331111211',
		's2' => '7',
/*		'email' => null,
		'country' => "KZ",
		'additional_1' => "bob",
		'additional_2' => "rrrr",
		'utm_term'=> '123',*/

		/*'comment'=> 1,*/
		/*'product_name'=> 123,*/
		/*'s3' => 'commmmmment',*/

		/*'s2' => '0',
		
		's1' => 0,
		'sub_id' => array(
			0,2,3
		),
		'count_sub_id'=>  array(
			0,2,3
		),
		'countProduct' => 0,*/

	/*	's2' => array(
			'0,132',2,3
		),*/
		
		// 's1' => array(
		// 	0,2,3
		// ),
/*		'subs' => array(
			0=>array(
				'sub_id' => array(
					111,'0000'
				),
				'count_sub_id'=>  array(
					'qwe',0.12,1231
				),
			),
			2=>array(
				'sub_id' => array(
					11,22,33
				),
				'count_sub_id'=>  array(
					1,0,0
				),
			),
		),*/

	
		/*'countProduct' => 0,*/


/*		'products_list' => array(
		    0 => array(
		        'product_id' => 1,   
		        'price'      => 10, 
		        'count'      => '11',  
		        'bob'=> 123123,
		        'subs' => array(
		        	array(
		        		'sub_id' => '123',
		        		'count' => '1'
		        	)

		        ),

		    )
		)*/
	)
);


echo "<pre>";


// Выводит на экран масивы: ошибок, request, response (для метода перед дебагом)
// выводит только в том случае если ваш ip === ip указаный в создании обьекта
$crm->displayDebug();





// возвращает масивы: ошибок, request, response (для метода перед дебагом)
$crm->debug();



var_dump( $crm->getError() );


var_dump($resp);
/*var_dump($crm->is_valid_URL( "http://testcrm.lp-crm.top" ));*/



$response = $crm->getStatuses();
var_dump($response);




$response = $crm->getOrdersByID( array('15123317970') );
var_dump($response);


$response = $crm->getCategories();
var_dump($response);






?>