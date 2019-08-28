<?php 

// подключаем класс
include_once("lp_crm_sdk.php");


// создаем обьект для работы с апи црм
$crm = new Seal\lp_crm_sdk( '1234567890abcdef', "http://testcrm.lp-crm.top", '178.213.6.37', array("5"=>"fd71") );
/*
1й арг - api key crm ( ОБЯЗАТЕЛЬНО )
2й арг - Вашe доменное имя ( ОБЯЗАТЕЛЬНО )

3й арг - ip для дебага ( это будет срабатывать ТОЛЬКО если ip пришедший на 
		 сервер === ip указаный Вами:
		  - для этого ip отключаеться редирект 
		  - при заказе (если указали меил) титул в письме будет "ТЕСТ"
		  - возможность использовать метод $crm->displayDebug();
		)
4й арг - масив для дешифрации ключа		 
 */


// =========================
//          МЕТОДЫ 
// =========================

 /*
 	СЕТТЕРЫ УСТАНАВЛИВАЮТСЯ ПЕРЕД МЕТОДАМИ ОТПАРВКИ (addNewOrder)
 */
	
	 	//----------------------------
	 	//setRedirectURL
	 	// устанавливает редирект после завершения обработки всех методов
	 	$crm->setRedirectURL("https://google.com", array( 'a'=>"b" ));
	 	/*
	 		1й арг - url для переадресации ( ОБЯЗАТЕЛЬНО )
	 		2й арг - масив данных который нужно передать
	 	*/
	 	

	 	//----------------------------
	 	//setSiteName
	 	// принудительно устанавливает источник с которого пришел заказ в ЦРМ
		$crm->setSiteName("https://some-site.com/asd/asd/asd");
		/*
	 		1й арг - url который будет добавлен в поле САЙТ в ЦРМ ( ОБЯЗАТЕЛЬНО )
	 	*/
	 
	 	//----------------------------
	 	//setIP
	 	// принудительно устанавливает ip с которого пришел заказ в ЦРМ
		$crm->setIP("111.111.1.1");
		/*
	 		1й арг - ip который будет добавлен в поле IP в ЦРМ ( ОБЯЗАТЕЛЬНО )
	 	*/
	 

		//----------------------------
	 	//setMail
	 	
		// устанавливает email на который нужно отправить данные о заказе
			$mailer_settings = array(
			  'host'=>'', //ваш сервер smtp
			  'login'=>'support@lp-crm.biz', // логин почтовика
			  'pass'=>'', // пароль почтовика
			  'mail'=>'support@lp-crm.biz', // ваш меил
			  'port'=>587, // порт
			  'auth'=>true, // нужна ли авторизация 
			  'secure'=>'tls', // режим
			);

		$crm->setMail( "if1if2if3@yandex.ru", $mailer_settings ); 

		// ЕСЛИ БЕЗ НАСТРОЕК $mailer_settings - БУДЕТ ОТПРАВЛЕНА ОБЫЧНЫМИ СРЕДСТВАМИ PHP - mail();

			$crm->setMail("if1if2if3@yandex.ru");
		/*
	 		1й арг - email на который нужно отправить данные о заказе ( ОБЯЗАТЕЛЬНО )
	 		2й арг - настройки smtp
	 	*/


	 	//----------------------------
	 	//setProxy
	 	//  // устанавливает настройки для отправки запроса чераз прокси 
    	$crm->setProxy( $proxy_ip, $proxyauth );
		
		/*
	 		1й арг - ip прокси сервера через который пройдет запрос
	 		2й арг - данные авторизации для прокси login:pass
	 	*/











 /*
 	ОСНОВНЫЕ МЕТОДЫ API CRM
	
	ДАННЫЕ ПРИ УСПЕШНОЙ ОТПРАВКЕ:

	$response = array(
		'response'=> array( ТУТ ДАННЫЕ С ЦРМ В СООТВЕТСТВИИ С ДОКУМЕНТАЦИЕЙ )
		'header'=> 'заголовок ответа'
	)
	





 */
//=====================================================
//                    addNewOrder
//=====================================================
	 	
// Добавление заказа в CRM
	 	

	 	$response = $crm->addNewOrder( $data );
	 	/*
	 		$data - масив с ключами :

		    'country'            // Географическое направление заказа
		    'office'             // Офис (id в CRM)
			


			//---------- передача продуктов ------------
	    		
	    		'products_list'  -    // масив с продуктами ( Если передаете сформированый масив как описано в api crm ) БУДЕТ ИСКАТЬ В ПЕРВУЮ ОЧЕРЕДЬ
	    		
	    		ИЛИ

				продукты по отдельности 
				( s1, price, product_price ) - возможные ключи для цены
				( s2, product_id, p_id ) - возможные ключи для id

				( count, countProduct ) - возможные ключи для количества

				ключи могут быть масивами 
				
					передача subs если ключ для id - не массив:
						sub_id | sub_id[]
						count_sub_id | count_sub_id[]

					передача subs если ключ для id - массив:
						subs[0][sub_id][]
						где 0 - принадлежность к элементу масива product_id

	    	//----------------------
			



			('name' | 'buyer_name')    // покупатель (Ф.И.О)

		    'phone'              // телефон
		    'email'              // электронка


			//----------------------
			// комментарий

    			('comment' | 'product_name' | s3)      
				string | array

			//----------------------


		    'delivery'        	 // способ доставки (id в CRM)
		    'delivery_adress' 	 // адрес доставки
		    'payment'         	 // вариант оплаты (id в CRM)
		           		                        
		    'utm_source'      	 // utm_source
		    'utm_medium'         // utm_medium
		    'utm_term'           // utm_term
		    'utm_content'        // utm_content
		    'utm_campaign'    	 // utm_campaign 

		    'additional_1'       // Дополнительное поле 1
		    'additional_2'       // Дополнительное поле 2
		    'additional_3'       // Дополнительное поле 3
		    'additional_4'       // Дополнительное поле 4

	 	*/
	 


/*

	ОТВЕТ 
	{

		"response":{
		    "status":"ok",
		    "data": {
		                "order_id":{order_id},
		                "country":{country},
		                "site":{site},
		                "ip":{ip}
		            },
		    "message":"Заказ успешно добавлен"
		},


		"header": ""

	}

	

 */


//=====================================================
//                    getStatuses
//=====================================================
	
// Получение статусов заказов
		 	
 	$response = $crm->getStatuses();



/*

	ОТВЕТ 
	{

		"response":{
		    {
			    'status' => ok
			    'data' => Array
			        (
			            [3] => Новый
			            [11] => Принято
			            [14] => Отправлено
			            [18] => Завершено
			            [13] => Отказ
			            [32] => Обмен
			            [27] => Самовывоз
			        )
			}
		},


		"header": ""

	}

	

 */





//=====================================================
//                    getOrdersIdByStatus
//=====================================================
	
// Получение идентификаторов заказов по статусу
	
	/* 
	$data = array(
	    'status'     => '3', //Новый
	    'date_start' => '2019-02-18', //не обязятельно
	    'date_end'   => '2019-02-25', //не обязятельно
	);
	*/

 	$response = $crm->getOrdersIdByStatus( $data );

 	
/*

	ОТВЕТ 
	{

		"response":{
			{
			    'status'             => ok
			    'date_start'         => 2019-02-18  
			    'date_end'           => 2019-02-25 
			    'status_orders_id'   => 3
			    'status_orders_name' => Новый
			    'data' => Array
			        (
			            [0] => 14454963519
			            [1] => 14454973562
			            [2] => 14454973563
			            [3] => 14454973564
			        )
			}
		},


		"header": ""

	}

	

 */



//=====================================================
//                    getOrdersByID
//=====================================================
	
// Получение информации о заказе (по идентификатору)
	
	/* 
		$data = array(
		    'order_id'     => 'id заказа', 
		);
	*/

 	$response = $crm->getOrdersByID( $data );

 	





//=====================================================
//                    getCategories
//=====================================================
	
// Получение категорий товаров из CRM.
	
 	$response = $crm->getCategories();

 	




//=====================================================
//                    getProductsByCategory
//=====================================================
	
// Получение товаров из CRM по указанной категории.
	
	/* 
		$data = array(
		    'category_id'     => 'ID категории товаров', 
		);
	*/

 	$response = $crm->getProductsByCategory( $data );

 	






//=====================================================
//                    getProduct
//=====================================================
	
// Получение информации о товаре из CRM по указанному ID.
	
	/* 
		$data = array(
		    'product_id'     => 'ID товара в CRM', 
		);
	*/

 	$response = $crm->getProduct( $data );

 	







//=====================================================
//                    getProductsBySite
//=====================================================
	
// Получение товаров из CRM по лендингу
	
	/* 
		$data = array(
		    'site_url'     => 'ID товара в CRM', 
		);
	*/

 	$response = $crm->getProductsBySite( $data );

 	








/*

МЕТОДЫ ПОЛУЧЕНИЯ ОШИБОК И ДЕБАГ

*/

// Выводит на экран масивы: ошибок, request, response (для метода перед дебагом)
// выводит только в том случае если ваш ip === ip указаный в создании обьекта
$crm->displayDebug();



// возвращает масивы: ошибок, request, response (для метода перед дебагом)
$crm->debug();


// возвращает масив ошибок (для метода перед getError)
// или array();
$crm->getError();

/*
пример 

array(1) {
  [0]=>
  array(3) {
    ["id"]=>
    int(5) // id ошибки можна отслеживать для дальнейших действий
    ["name"]=>
    string(29) "Ошибка ответа CRM"
    ["info"]=>
    string(35) "Дублирующая заявка"
  }
}


все возможные id ошибки - 

    	1 => "Не указаны данные авторизации",
    	2 => "Не передано обязательное значение",
    	3 => "Метод не существует",
    	4 => "Значение не прошло валидацию",
    	5 => "Ошибка ответа CRM",
    	6 => "Ошибка отправки email",
    	7 => "Не корректно введен URL адресс",



 */

?>