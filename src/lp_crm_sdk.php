<?php 
namespace Seal;

/**
 * 
 */
class lp_crm_sdk
{
	
	private $key; // ключ црм
	private $crm_path; // доменное имя полностю (http(s)://test.lp-crm.biz)

	private $ip_to_show_response = null; // ip для вывода на экран response
    private $ip_address_client; // ip адрес получаем автоматом $_SERVER["REMOTE_ADDR"];

	private $decode_key; // масив для декодировки ключа црм

	private $proxy_ip = null; // прокси сервер (5.135.20.71:8080)
	private $proxyauth = null; // (user:password)

	private $redirect_url; // ссылка для редиректа

	private $mail_to; // email для отправки заказа на него

	private $dirCSV; // директория для добавления backup CSV

	private $server_info;

	private $current_method_name;

    /* =========== DATA ============ */
        private $available_utm = array(
            "utm_source",
            "utm_medium",
            "utm_term",
            "utm_content",
            "utm_campaign",
        );

    /* =========== DATA ============ */

    const AUTH_FAIL_ERR = 1;
    const VALUE_EXIST_ERR = 2;
    const METHOD_CALL_ERR = 3;
    const VALIDATE_ERR = 4;
    const CRM_RESPONSE_ERR = 5;
    const MAIL_ERR = 6;
    const VALIDATE_URL_ERR = 7;

    private $available_error = array(

    	self::AUTH_FAIL_ERR => "Не указаны данные авторизации",
    	self::VALUE_EXIST_ERR => "Не передано обязательное значение",
    	self::METHOD_CALL_ERR => "Метод не существует",
    	self::VALIDATE_ERR => "Значение не прошло валидацию",
    	self::CRM_RESPONSE_ERR => "Ошибка ответа CRM",
    	self::MAIL_ERR => "Ошибка отправки email",
    	self::VALIDATE_URL_ERR => "Не корректно введен URL адресс",
    );

    private $errors_list_local = array();
    private $errors_list_global = array();
    private $errors_list = array();

    private $request;
    private $response;



	public function __construct( $key, $crm_path, $ip_to_show_response='', $decode_key = null ){
		if(!$key || !$crm_path) $this->errLog('global', self::AUTH_FAIL_ERR);


		$key = !!$decode_key ? $this->simple_decrypt_key( $key, $decode_key ) : $key;

		$this->key = $this->validateValue($key);

		// проверка юрл на валидность 
		if( $this->is_valid_URL($crm_path) ){
			$this->crm_path = $this->validateValue(rtrim($crm_path, '/'));
		}else{
			$this->errLog('global', self::VALIDATE_URL_ERR, $crm_path);
		}

		$this->ip_to_show_response = $this->validateValue($ip_to_show_response);

		$this->decode_key = $decode_key;

		$this->ip_address_client = (string) $_SERVER["REMOTE_ADDR"];

		$this->server_info = $_SERVER;

	}

	// возвращаем масив ошибок, request, response
	public function debug(){

		$result_arr = array();

		$result_arr["errors"] = $this->errors_list;
		$result_arr["request"] = $this->request;
		$result_arr["response"] = $this->response;

		return $result_arr;
	}


    // выводим ответ сразу в браузер если указали ip
	public function displayDebug(){
		if( !empty($this->ip_to_show_response) && $this->checkIP() ){
			$arr_err = $this->debug();

			echo "<pre>";
			var_dump($arr_err);
			echo "</pre>";
		}
	}



	// устанавливает ссылку на редирект
	public function setRedirectURL( $url, array $query = array() ){
		
		// проверка юрл на валидность 
		if( !$this->is_valid_URL($url) ){
			$this->errLog('global', self::VALIDATE_URL_ERR, $url);
			return;
		}

		$url = $this->validateValue($url);

		if( !empty($query) ){
			$bq = http_build_query($query);
			$url = $url.'?'.$bq;
		}
		
        $this->redirect_url = $url;

        $this->redirect($url);
    }


    // перенаправляет на указаную страницу
    private function redirect($url){
        if( !empty($this->redirect_url) && !( $this->checkIP() ) ){
            header( "location: {$url}" );
	     	//echo "<script>window.location.href = '{$url}'</script>";
	    }
    }


    public function setSiteName($val){
    	// HTTP_REFERER
    	//$this->server_info["HTTP_REFERER"] = $this->validateValue( $val );
    	$this->server_info["SERVER_NAME"] = $this->validateValue( $val );
    }

    public function setIP($val){
    	// REMOTE_ADDR
    	$this->server_info["REMOTE_ADDR"] = $this->validateValue( $val );
    }


	// устанавливает email для отправки заказа на почту
	public function setMail( $email ){

        $this->mail_to = $this->validateValue($email);
    }


    // устанавливаем нужно ли добавлять заказ в csv
    // 1 - указываем нужно ли активировать
    // 2 - указываем директорию
    public function setOrderCSV( $is_active, $dir = 'orders_backup' ){
    	if( !$is_active ) return;
    	$this->dirCSV = $dir;
    }


    // создаем CSV
    private function createOrderCSV( $order_arr ){
    	// если не установили сетер - пропускаем
    	if( !$this->dirCSV ) return;

    	$dir_local = $this->dirCSV;
    	$file_name = 'orders_'.date("d-m-Y").'.csv';
    	$full_path = $dir_local.'/'.$file_name;

    	//проверка директории
    	if( !file_exists($dir_local) ) mkdir(dirname($full_path), 0777, true);

    	$sep = ';'; // separator

    	$tmp_head_field = "order_id{$sep}country{$sep}office{$sep}products{$sep}bayer_name{$sep}phone{$sep}email{$sep}comment{$sep}delivery{$sep}delivery_adress{$sep}payment{$sep}sender{$sep}utm_source{$sep}utm_medium{$sep}utm_term{$sep}utm_content{$sep}utm_campaign{$sep}additional_1{$sep}additional_2{$sep}additional_3{$sep}additional_4";

    	// проверка файла ( если нет создаем с полями )
    	if( !file_exists($full_path) ){
	    	//открываем файл
	    	$handle = fopen($full_path, "a+");
		    fwrite($handle, $tmp_head_field."\r\n");
		    fclose($handle);
    	}



		//шаблон для данных заказа
    	$tmp_orders = "{$order_arr['order_id']}{$sep}{$order_arr['country']}{$sep}{$order_arr['office']}{$sep}'{json_encode($order_arr['products'])}'{$sep}{$order_arr['bayer_name']}{$sep}{$order_arr['phone']}{$sep}{$order_arr['email']}{$sep}{$order_arr['comment']}{$sep}{$order_arr['delivery']}{$sep}{$order_arr['delivery_adress']}{$sep}{$order_arr['sender']}{$sep}{$order_arr['utm_source']}{$sep}{$order_arr['utm_medium']}{$sep}{$order_arr['utm_term']}{$sep}{$order_arr['utm_content']}{$sep}{$order_arr['utm_campaign']}{$sep}{$order_arr['additional_1']}{$sep}{$order_arr['additional_2']}{$sep}{$order_arr['additional_3']}{$sep}{$order_arr['additional_4']}";

    	//открываем файл
    	$handle = fopen($full_path, "a+");
	    fwrite($handle, $tmp_orders);
	    fclose($handle);

    }



    // устанавливает настройки для отправки запроса чераз прокси 
    public function setProxy( $proxy_ip, $proxyauth = null ){
        $this->proxy_ip = $this->validateValue($proxy_ip);
        $this->proxyauth = $this->validateValue($proxyauth);
    }

    // возвращает все ошибки ( КАЖДЫЙ ВЫЗОВ МЕТОДА АНУЛИРУЕТ ОШИБКИ ПРЕДЫДУЩЕГО МЕТОДА )
    public function getError(){
    	$this->errors_list = array_merge($this->errors_list_local, $this->errors_list_global);
    	$err = $this->errors_list;
    	return $err;
    }


    // если вызвали не существующий метод
    public function __call( $methodName, $args ) {
	    return $this->errLog("local", self::METHOD_CALL_ERR, $methodName );
    }


	// вывод ошибок
    private function errLog( $scope_err, $id, $name = '') {
    	$curr_method = $this->current_method_name;
    	$mess = $this->available_error[$id];

    	$err_list = "errors_list_".$scope_err;
		$err = &$this->{$err_list}[];

    	$err = array(
    		"id" => $id,
    		"name" => $mess,
    		'info' => $name
    	);



		/*    	$arr = array();
    	$arr["errors"] = array( $message );

   		echo json_encode( $arr );
   	
        die();*/
    }


    // проверяем на валидность url
    private function is_valid_URL($url){

    	return !!( @get_headers( $url ) );
    }


    private function setReqResp($req, $resp){
    	$this->request = $req;
    	$this->response = $resp;
    }


    // провераем ip клиента и ip для дебага
    private function checkIP(){
    	return $this->ip_to_show_response === $this->ip_address_client;
    }




    // проверка ответа ЦРМ на ошибки
    private function checkResponse($resp){
    	
    	if(!empty( $resp['response']["status"] ) && $resp['response']["status"] == "error" ){
    		$mess = '';

    		//если сообщение ответа это масив
    		if( !!$resp['response']["message"] && is_array($resp['response']["message"]) ){

    			$mess = $resp['response']["message"][0];

    		//если сообщение ответа это строка	
    		}elseif( !!$resp['response']["message"] && is_string($resp['response']["message"]) ){

    			$mess = $resp['response']["message"];
    		}

    		$this->errLog( "local", self::CRM_RESPONSE_ERR, $mess );
    	}
    }




    // получаем масив с ютм метками с сесии или с данных пользователя $data["utm...."]
    private function getUTM( $data ){
        $result_utms = array();
        $utm = $this->available_utm;
        foreach ($utm as $key => $value) {
            $val = !empty($data[$value]) ? $this->validateValue($data[$value]) : null;
            $result_utms[$value] = !empty($_SESSION['utms'][$value]) ?
                                   $this->validateValue($_SESSION['utms'][$value]) :
                                   $val;
        }
        return $result_utms;
    }


    // посылает email c заказом
    private function sendMail( $message, $theme = "Заказа товара" ){

    	// если не установили меил для отправки
    	if( !$this->mail_to ) return false;
    	$email = $this->mail_to;

    	// если ip === ip для дебага
    	if( $this->checkIP() ) $theme = "TEST";

    	$send_mail = mail($email, $theme, $message, "Content-type:text/plain;charset=utf-8\r\n");

    	if( !$send_mail ){
    		$this->errLog( "local", self::MAIL_ERR);
    	}

    	return $send_mail;
    }







    //===========================================================
 	// дешифрует ключ црм
    // $key - строка для дешифровки
    // $decrypt_key - масив
    // 		ключ: 0-9 место откуда будут вырезаны символы длиной length значения
    //      Значение: символы которые нада извлечь
    // 
    public function simple_decrypt_key( $key, array $decrypt_key ){

    	$decode_pos = (string) key($decrypt_key);
        $decode_key = (string) $decrypt_key[$decode_pos];
        
        $key = base64_decode($key);

        $length = strlen($decode_key);
        $key = substr_replace($key, '', $decode_pos, $length);
      

    	return $key;
    }

    // шифрует ключ црм
    // $key - строка для шифровки
    // $encrypt_key - масив
    // 		ключ: 0-9 место куда будет вставлено значение в строку
    //      Значение: символы для вставки HEX
    // 
    public function simple_encrypt_key( $key, array $encrypt_key ){

    	$decode_pos = (string) key($encrypt_key);
        $decode_key = (string) $encrypt_key[$decode_pos];

        
        $key = substr_replace($key, $decode_key, $decode_pos, 0);
        $key = base64_encode($key);

    	return $key;
    }

    //===========================================================




    // отправка данных курлом
    // 
    private function sendRequest( $method_name, $url, $data = null ){

    	$curl = curl_init();

	    curl_setopt($curl, CURLOPT_URL, $url);
	   // curl_setopt($curl, CURLOPT_HTTPHEADER, array( "ContentType: application/json; charset=utf-8" ));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    //curl_setopt($curl, CURLOPT_HEADER, 1);


	    // если передаем данные постом
	    if( mb_strtolower($method_name) == "post"){
		    curl_setopt($curl, CURLOPT_POST, true);
		    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
 		

	    // если указали отправку через прокси
	    // ----------------------------------------
		    curl_setopt( $curl, CURLOPT_PROXY, $this->proxy_ip );

		    if( !empty($this->proxy_ip) ){
		    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		    }

		    if( !empty($this->proxyauth) ){
		        curl_setopt($curl, CURLOPT_PROXYUSERPWD, $this->proxyauth);
		    }
	    // ----------------------------------------
	    
	    

	    $out = curl_exec($curl);
	    $content_type = curl_getinfo( $curl, CURLINFO_CONTENT_TYPE );	
	    
	    curl_close($curl);
	   

	    $response = json_decode($out, true);
		
        $response_arr = array("response" => $response, "header" =>$content_type);

        $this->checkResponse($response_arr);


	    return $response_arr;
    } 



    // обработка строковых значений
    private function validateValue( $val ){
    	$result = '';
		try {
		    $result = stripslashes(htmlspecialchars(trim($val)));
		} catch (Exception $e) {
			//var_dump( $e);
		  // $this->errLog( "local", self::VALIDATE_ERR, $val );
		}

		return $result;
		
    }



    // генерируем id для заказа
    private function generateOrderId(){
    	return number_format(round(microtime(true)*10),0,'.','');
    }



    // проверяем обязательные значения, валидируем все остальные, записываем дефолтные значения
    private function checkData( $user_data, $method_data ){
    	$tmp_arr = array();

    	// находим обязательные значения
    	$req_data = !empty($method_data["require"]) ? $method_data["require"] : null;
    	
    	// ПРОВЕРКА ОБЯЗАТЕЛЬНЫХ ЗНАЧЕНИЙ
    	if( !!$req_data ){
	    	foreach ($req_data as $key => $value) {
	    		
    			$curr_val_name =  $value["name"];
    			$curr_validate = !empty($value["validate"]) ? $value["validate"] : null;

    			// находим в пришедших данных нужное нам значение
    			$check_data_exist = !empty( $user_data[$curr_val_name] ) ? $user_data[$curr_val_name] : null;

    			if( !$check_data_exist ) $this->errLog( "local", self::VALUE_EXIST_ERR, $curr_val_name );

    			//если нужно провалидировать
    			if( !!$curr_validate ){
    				// вызываем функцию для валидации
    				$valid_value = $curr_validate($check_data_exist);
    				if(!$valid_value){
    					$this->errLog( "local", self::VALIDATE_ERR, $curr_val_name );
    				}
    				$tmp_arr[$curr_val_name] = $valid_value;

    			}else{
    				$tmp_arr[$curr_val_name] = $check_data_exist;
    			}

    		}
			
    	}


    	// ПРОВЕРКА ДОПОЛНИТЕЛЬНЫХ ЗНАЧЕНИЙ И ИХ ВАЛИДАЦИЯ
    	// находим доп значения
    	$default_data = !empty($method_data["default"]) ? $method_data["default"] : null;

    	if( !!$default_data ){
    		$count_def_data = count($default_data);

    		for ( $i=0; $i < $count_def_data; $i++ ) { 

    			$curr_arr = $default_data[$i];
    			$curr_def_name = $curr_arr["name"];
    			$curr_def_value = $curr_arr["value"];


    			$check_data_exist = !empty($user_data[$curr_def_name]) ? $user_data[$curr_def_name] : null;

    			// если значение передали то устанвалиываем его
    			if( !!$check_data_exist ){

    				$tmp_arr[$curr_def_name] = $check_data_exist;

    			}else{ 
    				//устанавливаем значение по умолчанию
    				$tmp_arr[$curr_def_name] = $curr_def_value;
    			}

    		}
    	}


    	return $tmp_arr;
    }




    // проверяет наличие одного из значений $keys в data и возвращает его
    private function checkMultipleValue (array $keys, array $data){

    	$count_keys = count($keys);
    	$value = null;

    	for ( $i=0; $i < $count_keys; $i++ ) { 
    		$curr_key = $keys[$i];
    		//echo $curr_key."\r\n";
    		$value = (isset($data[$curr_key])) ? $data[$curr_key] : null;
    		if( isset($value) ) return $value;
    	}

    	return $value;
    }




	private function validateRequaireValue( $key, $array ){

 		if( isset($array[$key]) && ( is_string($array[$key]) || is_numeric($array[$key]) ) ){
 			$cur_val = $this->validateValue( $array[$key] );
 			return $cur_val;
 		}else{
 			$this->errLog("local", self::VALUE_EXIST_ERR, $key);
 			return false;
 		}

	}







    /* ================= ОСНОВНЫЕ МЕТОДЫ ================ */




    // Добавление заказа в CRM
    public function addNewOrder( array $data ){

        $send_url = $this->crm_path."/api/addNewOrder.html";

        $this->current_method_name = __FUNCTION__;

        // очищаем предыдущие ошибки ...............
        $this->errors_list_local = array();

        // масив с собраными данными для отправки в црм
	        $request_arr = array();
	        
	        $request_arr["key"] = $this->key;
	        $request_arr["order_id"] = $this->generateOrderId();

	        $sender = urlencode(serialize( $this->server_info ));
	        $request_arr["sender"] = $sender;
			  
        // данные которые должны быть в масиве для отправки
	  		$available_data = array(

	  			"require" => array(
	  				array(
	  					'name' => 'phone',
	  					'validate' => function ($val){
	  						return preg_replace('/[^0-9]/', '', $val);
	  					}
	  				),
	  			),

	  			"default" => array(
	  				array(
	  					'name' => 'email',
	  					'value' => ''
	  				),

	  				array(
	  					'name' => 'delivery',
	  					'value' => ''
	  				),
	  				array(
	  					'name' => 'delivery_adress',
	  					'value' => ''
	  				),
	  				array(
	  					'name' => 'payment',
	  					'value' => ''
	  				),
	  				array(
	  					'name' => 'country',
	  					'value' => 'UA'
	  				),
	  				array(
	  					'name' => 'office',
	  					'value' => ''
	  				),
	  				array(
	  					'name' => 'additional_1',
	  					'value' => ''
	  				),
	  				array(
	  					'name' => 'additional_2',
	  					'value' => ''
	  				),
	  				array(
	  					'name' => 'additional_3',
	  					'value' => ''
	  				),
	  				array(
	  					'name' => 'additional_4',
	  					'value' => ''
	  				),

	  		
	  			),
	            
	        );



        // добавляем ютм метки и валидированые значения для отправки
	        $utms = $this->getUTM( $data );
	        $checked_data = $this->checkData( $data, $available_data );
	        $request_arr = array_merge( $request_arr, $utms, $checked_data );

	    // добавляем имя
	    $curr_name = $this->checkMultipleValue ( array( "name", "bayer_name" ), $data );

	    $request_arr["bayer_name"] = $this->validateValue( $curr_name );

  		//обрабатываем продукты	
        // 1) если прислали масив с продуктами $data['products_list'] берем его проверяем????
        // 
        // 2) проверяем значения (comment, product_name s3) ((s1, product_price, price) (s2, product_id, p_id) ( count, countProduct) )
        // 		(count_sub_id) (sub_id)
	    // 	2.1) проверяем эти значения, если product_id - масив кол-во продуктов будет равно его Length. 
	    // 	
	    // 3) 


     
	    $result_products_arr = array();


	    // проверка на наличие "s2","product_id" или "p_id" в data, возвращает значение или false
	    $p_id = $this->checkMultipleValue ( array( "s2", "product_id", "p_id" ), $data );

	    //var_dump($p_id);


	    // если прислали готовый масив с продуктыми валидируем его
	     if ( !empty($data["products_list"]) && is_array($data["products_list"]) ){


	     	$p_arr = $data["products_list"];
	     	$count_product = count($p_arr);

	     	for ($i=0; $i < $count_product; $i++) { 
	     		$curr_product = $p_arr[$i];

	     		$curr_result_arr = &$result_products_arr[];

	     		//проверка на существование нужных данных

	     		$curr_result_arr['product_id'] = $this->validateRequaireValue( 'product_id',  $curr_product );

	     		$curr_result_arr['price'] = $this->validateRequaireValue( 'price',  $curr_product );

	     		$curr_result_arr['count'] = $this->validateRequaireValue( 'count',  $curr_product );

	     		if( !empty( $curr_product['subs'] ) && is_array( $curr_product['subs'] ) ){

	     			$curr_subs = $curr_product['subs'];
	     			$count_subs = count($curr_subs);

		     		for ( $n=0; $n < $count_subs; $n++ ) { 

		     			$sub_arr = $curr_subs[$n];

	     				$curr_result_arr['subs']['count'] = $this->validateRequaireValue( 'count',  $sub_arr);

	     				$curr_result_arr['subs']['sub_id'] = $this->validateRequaireValue( 'sub_id',  $sub_arr);
	     			}

	     		}
	     	}


	     // если прислали данные с формы 
	     }elseif( !!$p_id || is_numeric($p_id) ){

	     	// для 1го товара (если значения являются строками)
	     	if( is_string($p_id) || is_numeric($p_id) ){

	     		

	     		$p_list = &$result_products_arr[];

	     		//id
     			$p_list["product_id"] = $this->validateValue( $p_id );


	     		//price
	     		$price = $this->checkMultipleValue ( array( "s1", "price", "product_price" ), $data );
	     		$p_list["price"] = isset($price) ? $this->validateValue( $price ) : '';
	     		
	     		//count
	     		//если не передали то default val = 1
	     		$count = $this->checkMultipleValue ( array( "count", "countProduct" ), $data );
	     		$p_list["count"] = isset($count) ? $this->validateValue( $count ) : '1';
	     		

	     		//если прислали subs
	     		if( isset($data["sub_id"]) ){

	     			$sub_id = $data["sub_id"];
	     			$count_sub_id = @$data['count_sub_id'];

	     			//если только 1н саб (тоесть саб id это строка иил num)
	     			if( isset($sub_id) && (is_string($sub_id) || is_numeric($sub_id)) ){

		     			$count_sub_id = isset($data['count_sub_id']) ? $this->validateValue( $data['count_sub_id'] ) : '1';

		     			$sub_id_val = $this->validateValue( $sub_id );

		     			$p_list['subs'] = array();
		     			$p_list_subs = &$p_list['subs'][];

		     			$p_list_subs['sub_id'] = $sub_id_val;
		     			$p_list_subs['count'] = $count_sub_id;

	     			}elseif( !empty($sub_id) && is_array($sub_id) ){

	     				$count_sub = count($sub_id);

	     				for ($i=0; $i < $count_sub; $i++) { 

	     					$p_list_subs = &$p_list['subs'][$i];

	     					$curr_count_sub_id = isset($count_sub_id[$i]) ? $this->validateValue( $count_sub_id[$i] ) : '1';

	     					$curr_sub = $this->validateValue( $sub_id[$i] );

	     					$p_list_subs['sub_id'] = $curr_sub;
	     					$p_list_subs['count'] = $curr_count_sub_id;

	     				}	     				

	     			}
	     		}



	     	// для нескольких товаров (если прислали по кривому...)
	     	}elseif( is_array($p_id) ){

	     		$count_p_id = count($p_id);

	     		//price
	     		$price = $this->checkMultipleValue ( array( "s1", "price", "product_price" ), $data );
     		
	     		//count
	     		$count = $this->checkMultipleValue ( array( "count", "countProduct" ), $data );
	     		

	     		for ($i=0; $i < $count_p_id; $i++) { 

	     			$p_list = &$result_products_arr[];

		     		//id
	     			$p_list["product_id"] = $this->validateValue( $p_id[$i] );
	     			//price
	     			$check_price = isset( $price[$i] ) ?  $this->validateValue($price[$i]) : '';
	     			$p_list["price"] = $check_price;
	     			//count
	     			$check_count = isset( $count[$i] ) ?  $this->validateValue($count[$i]) : '1';
	     			$p_list["count"] = $check_count;


		     		//если прислали subs
		     		if( isset($data["subs"][$i]) && is_array($data["subs"]) ){

		     			$curr_subs = (!empty($data["subs"][$i]['sub_id']) && is_array($data["subs"][$i]['sub_id'])) ? $data["subs"][$i] : array();

		     			$curr_subs_id = (!empty($data["subs"][$i]['sub_id']) && is_array($data["subs"][$i]['sub_id'])) ? $data["subs"][$i]['sub_id'] : array();

		     			$count_curr_subs = count($curr_subs_id);

		     			$tmp_sub = array();
		     			

		     			for ($n=0; $n < $count_curr_subs; $n++) { 
		     				
		     				$curr_tmp_sub = &$tmp_sub[];

		     				$curr_tmp_sub['sub_id'] = $this->validateValue( $curr_subs_id[$n] );

		     				$curr_tmp_sub['count'] = isset($curr_subs['count_sub_id'][$n]) ? $this->validateValue( $curr_subs['count_sub_id'][$n] ) : '1';
		     				
		     			}
		
		     			if( !!$tmp_sub ){
							$p_list['subs'] = $tmp_sub;
		     			}
		     			

		     		}
		     		
	     		}


	     	}else{
	     		$this->errLog("local", self::VALIDATE_ERR, "product_id");
	     	}

	     }else{
	     	$this->errLog("local", self::VALUE_EXIST_ERR, "product_id");
	     }



	     $products_arr = $result_products_arr;

	     $request_arr_dubug = $request_arr;
	     $request_arr_dubug["products"] =  $products_arr ;

	     $request_arr["products"] = urlencode(serialize( $products_arr ));






	    // обработка коментария (склеиваем коммент и p_name)
	    if( !empty($data['comment']) && !empty($data['product_name']) ){

	    	if( is_array($data['comment']) ){

	    		$comm = $data['comment'];
	    		$count_comm = count($data['comment']);

	    		$p_name = is_array($data['product_name']) ? $data['product_name'] : $this->validateValue($data['product_name'].'_') ;
	    		$count_p_name = is_array($data['product_name']) ? count($data['product_name']) : 0;

	    		//находим большее значение count
	    		$max_count = max( $count_comm, $count_p_name );

	    		$tmp_comment = '';

	    		for ( $i=0; $i < $max_count; $i++ ) {
	    			
	    			$curr_comm = $data['comment'][$i];
	    			$curr_p_name = !empty($p_name[$i]) ? $this->validateValue($p_name[$i].'_') : '';
	    			$curr_p_name = !!$count_p_name ? $curr_p_name : $p_name;

	    			$tmp_comment .= $curr_p_name.$curr_comm.' | ';
	    		}

	    		$request_arr["comment"] = $this->validateValue( $tmp_comment );

	    	}else{
	    		$request_arr["comment"] = $this->validateValue( $data['product_name']."_".$data['comment'] );
	    	}
	    }else{

	     	$available_comment_keys = array(
	  			'comment',
	  			's3',
	  			'product_name'
	  		);

	  		// выбираем и валидируем значние
	        $request_arr["comment"] = $this->validateValue(
	        	$this->checkMultipleValue ( $available_comment_keys, $data )
	        );

	    }

	    $request_arr_dubug["comment"] = $request_arr["comment"];


	     $response = array(  );
	     



	     // добавляем CSV с заказом
	     $this->createOrderCSV( $request_arr_dubug );

	     die();

	     // посылаем запрос если нет ошибок
	     if( !$this->getError() ){
	     	$response = $this->sendRequest( "POST", $send_url, $request_arr );
	     }

	     
	     // записываем реквест и респонс в глобал
	     $this->setReqResp( $request_arr_dubug, $response );


	     $utms_str = http_build_query($utms);
	     // отправка мейла 
	     $mail_message = "ФИО: {$request_arr['bayer_name']}\r\nКонтактный телефон: {$request_arr['phone']}\r\nСайт: {$this->server_info['SERVER_NAME']}\r\nUTM: {$utms_str}";
	     $this->sendMail( $mail_message, "Заказа товара" );

	     // Устанавливаем редирект
	     //$this->redirect();


	     return $response;

       /* echo "<pre>";
        var_dump($result_arr);
        die();*/
    

    }





    // Получение статусов заказов
    public function getStatuses( ){

        $send_url = $this->crm_path."/api/getStatuses.html";

        $this->current_method_name = __FUNCTION__;

        // очищаем предыдущие ошибки ...............
        $this->errors_list_local = array();

        // масив с собраными данными для отправки в црм
            $request_arr = array();
            
            $request_arr["key"] = $this->key;
                          
         $response = array();
         // посылаем запрос если нет ошибок
         if( !$this->getError() ){
            $response = $this->sendRequest( "POST", $send_url, $request_arr );
         }

         
         // записываем реквест и респонс в глобал
         $this->setReqResp( $request_arr, $response );


         return $response;


    }








    // Получение категорий товаров из CRM.
    public function getCategories( ){

        $send_url = $this->crm_path."/api/getCategories.html";

        $this->current_method_name = __FUNCTION__;

        // очищаем предыдущие ошибки ...............
        $this->errors_list_local = array();

        // масив с собраными данными для отправки в црм
            $request_arr = array();
            
            $request_arr["key"] = $this->key;
                          
         $response = array();
         // посылаем запрос если нет ошибок
         if( !$this->getError() ){
            $response = $this->sendRequest( "POST", $send_url, $request_arr );
         }

         
         // записываем реквест и респонс в глобал
         $this->setReqResp( $request_arr, $response );


         return $response;


    }






    // Получение идентификаторов заказов по статусу
    public function getOrdersIdByStatus( array $data ){

        $send_url = $this->crm_path."/api/getOrdersIdByStatus.html";

        $this->current_method_name = __FUNCTION__;

        // очищаем предыдущие ошибки ...............
        $this->errors_list_local = array();


        // масив с собраными данными для отправки в црм
        $request_arr = array();
        
        $request_arr["key"] = $this->key;

        // данные которые должны быть в масиве для отправки
        $available_data = array(

            "require" => array(
                array(
                    'name' => 'status'                        
                ),
            ),

            "default" => array(
                array(
                    'name' => 'date_start',
                    'value' => ''
                ),

                array(
                    'name' => 'date_end',
                    'value' => ''
                ),
            ),
            
        );

        // добавляем валидированые значения для отправки
            
        $checked_data = $this->checkData( $data, $available_data );
        $request_arr = array_merge( $request_arr, $checked_data );

           
         $response = array();
         // посылаем запрос если нет ошибок
         if( !$this->getError() ){
            $response = $this->sendRequest( "POST", $send_url, $request_arr );
         }

         
         // записываем реквест и респонс в глобал
         $this->setReqResp( $request_arr, $response );


         return $response;


    }



    // Получение информации о заказе (по идентификатору)
    public function getOrdersByID( array $data ){

        $send_url = $this->crm_path."/api/getOrdersByID.html";

        $this->current_method_name = __FUNCTION__;

        // очищаем предыдущие ошибки ...............
        $this->errors_list_local = array();


        // масив с собраными данными для отправки в црм
        $request_arr = array();
        
        $request_arr["key"] = $this->key;

        // данные которые должны быть в масиве для отправки
        $available_data = array(

            "require" => array(
                array(
                    'name' => 'order_id'                        
                ),

            ),
   
        );

        // добавляем валидированые значения для отправки
            
        $checked_data = $this->checkData( $data, $available_data );
        $request_arr = array_merge( $request_arr, $checked_data );

           
         $response = array();
         // посылаем запрос если нет ошибок
         if( !$this->getError() ){
            $response = $this->sendRequest( "POST", $send_url, $request_arr );
         }
         
         // записываем реквест и респонс в глобал
         $this->setReqResp( $request_arr, $response );


         return $response;
    }





    // Получение товаров из CRM по указанной категории.
    public function getProductsByCategory( array $data ){

        $send_url = $this->crm_path."/api/getProductsByCategory.html";

        $this->current_method_name = __FUNCTION__;

        // очищаем предыдущие ошибки ...............
        $this->errors_list_local = array();


        // масив с собраными данными для отправки в црм
        $request_arr = array();
        
        $request_arr["key"] = $this->key;

        // данные которые должны быть в масиве для отправки
        $available_data = array(

            "require" => array(
                array(
                    'name' => 'category_id'                        
                ),

            ),
   
        );

        // добавляем валидированые значения для отправки
            
        $checked_data = $this->checkData( $data, $available_data );
        $request_arr = array_merge( $request_arr, $checked_data );

           
         $response = array();
         // посылаем запрос если нет ошибок
         if( !$this->getError() ){
            $response = $this->sendRequest( "POST", $send_url, $request_arr );
         }
         
         // записываем реквест и респонс в глобал
         $this->setReqResp( $request_arr, $response );


         return $response;
    }





    // Получение информации о товаре из CRM по указанному ID.
    public function getProduct( array $data ){

        $send_url = $this->crm_path."/api/getProduct.html";

        $this->current_method_name = __FUNCTION__;

        // очищаем предыдущие ошибки ...............
        $this->errors_list_local = array();


        // масив с собраными данными для отправки в црм
        $request_arr = array();
        
        $request_arr["key"] = $this->key;

        // данные которые должны быть в масиве для отправки
        $available_data = array(

            "require" => array(
                array(
                    'name' => 'product_id'                        
                ),

            ),
   
        );

        // добавляем валидированые значения для отправки
            
        $checked_data = $this->checkData( $data, $available_data );
        $request_arr = array_merge( $request_arr, $checked_data );

           
         $response = array();
         // посылаем запрос если нет ошибок
         if( !$this->getError() ){
            $response = $this->sendRequest( "POST", $send_url, $request_arr );
         }
         
         // записываем реквест и респонс в глобал
         $this->setReqResp( $request_arr, $response );


         return $response;
    }





    // Получение товаров из CRM по лендингу.
    public function getProductsBySite( array $data ){

        $send_url = $this->crm_path."/api/getProductsBySite.html";

        $this->current_method_name = __FUNCTION__;

        // очищаем предыдущие ошибки ...............
        $this->errors_list_local = array();


        // масив с собраными данными для отправки в црм
        $request_arr = array();
        
        $request_arr["key"] = $this->key;

        // данные которые должны быть в масиве для отправки
        $available_data = array(

            "require" => array(
                array(
                    'name' => 'site_url'                        
                ),

            ),
   
        );

        // добавляем валидированые значения для отправки
            
        $checked_data = $this->checkData( $data, $available_data );
        $request_arr = array_merge( $request_arr, $checked_data );

           
         $response = array();
         // посылаем запрос если нет ошибок
         if( !$this->getError() ){
            $response = $this->sendRequest( "POST", $send_url, $request_arr );
         }
         
         // записываем реквест и респонс в глобал
         $this->setReqResp( $request_arr, $response );


         return $response;
    }








}





?>