<?php 
session_start();

$period_cookie = 2592000; // 30 дней (2592000 секунд)

$utms = array('utm_source', 'utm_medium','utm_term', 'utm_content', 'utm_campaign');

for ($i=0; $i < count($utms); $i++) { 
    $curr_utm = $utms[$i];
    if(!empty($_GET[$curr_utm])){
      setcookie( $curr_utm ,$_GET[$curr_utm],time()+$period_cookie);
    }
} 
 
if( !isset($_SESSION['utms']) ) {
    $_SESSION['utms'] = array();
    $_SESSION['utms']['utm_source'] = '';
    $_SESSION['utms']['utm_medium'] = '';
    $_SESSION['utms']['utm_term'] = '';
    $_SESSION['utms']['utm_content'] = '';
    $_SESSION['utms']['utm_campaign'] = '';
}

$_SESSION['utms']['utm_source'] = !empty($_GET['utm_source']) ? $_GET['utm_source'] : ( !empty($_COOKIE['utm_source']) ? $_COOKIE['utm_source'] : '');

$_SESSION['utms']['utm_medium'] = !empty($_GET['utm_medium']) ? $_GET['utm_medium'] : (!empty($_COOKIE['utm_medium']) ? $_COOKIE['utm_medium'] : '');
$_SESSION['utms']['utm_term'] = !empty($_GET['utm_term']) ? $_GET['utm_term'] : (!empty($_COOKIE['utm_term']) ? $_COOKIE['utm_term'] : '');
$_SESSION['utms']['utm_content'] = !empty($_GET['utm_content']) ? $_GET['utm_content'] : (!empty($_COOKIE['utm_content']) ? $_COOKIE['utm_content'] : '');
$_SESSION['utms']['utm_campaign'] = !empty($_GET['utm_campaign']) ? $_GET['utm_campaign'] : (!empty($_COOKIE['utm_campaign']) ? $_COOKIE['utm_campaign'] : '');



include_once ("product_config.php");

$price_old = floor(($price_new/(100-$sale))*100);

?>
<!doctype html><HTML>
<html>
  <head>
    <title>Японский бинокль Bushnell</title>
    <meta name="description" content="Японский бинокль. Для освещения в хайкинге, кемпинге, на рыбалке и в хозяйстве">
    <meta name="keywords" content="Японский бинокль, купить Бинокль Bushnell, Бинокль Bushnell, Фонарь-лампа">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=480">
    
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,600i,700" tppabs="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,600i,700" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" tppabs="css/style.css">
    <link rel="stylesheet" href="css/slick.css" tppabs="css/slick.css">
<link rel="stylesheet" type="text/css" href="css/roboto.css" tppabs="https://static.best-gooods.ru/fonts/roboto.css">
        <script src="js/jquery.js" tppabs="https://static.best-gooods.ru/js/jquery.js" type="text/javascript"></script>
        <script src="js/plugins.js" tppabs="https://static.best-gooods.ru/js/plugins.js" type="text/javascript"></script>
        
        <script src="js/detect.js" tppabs="https://static.best-gooods.ru/js/detect.js" type="text/javascript"></script>


		</head>
  <body>      <div class="main-wrap">
      <!-- Header-->
      <header class="header">
        <div class="container">
          <div class="discount">
            <div>ТОЛЬКО СЕГОДНЯ<br><span>СКИДКА <?=$sale?>%</span></div>
          </div>          
          <div class="cost-wrap">
            <div class="old-cost">
              <div>Обычная цена<span><?=$price_old?> <?=$currency?>.</span></div>
            </div>
            <div class="new-cost"><!-- bizlife_inc -->
              <div><strong class="yellow"><?=$price_new?> <?=$currency?>.</strong></div>
            </div>
          </div><a href="#zakaz" class="btn-order">Заказать со скидкой</a>
        </div>
      </header>
      <!-- Block-2-->
      <section class="block-2">
        <div class="container">
          <h2 class="zag">ПОЧЕМУ <span class="blue">Bushnell?</span></h2>

<div class="slider-wrap">
          <!-- Slider-->
          <div class="slider-top">
            <!-- slide block-->
                <div class="slide-block"><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/slide1.jpg.png.jpg" tppabs="images/slide1.jpg.png"></div>
                <div class="slide-block"><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/slide2.jpg.png.jpg" tppabs="images/slide2.jpg.png"></div>
                
                <div class="slide-block"><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/slide4.jpg.png" tppabs="images/slide4.jpg.png"></div>
                <!--<div class="slide-block"><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img\slide5.jpg"></div>-->
                <div class="slide-block"><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/slide6.jpg.png" tppabs="images/slide6.jpg.png"></div>

          
            </div><span class="arrow-left"></span><span class="arrow-right"></span>


            <p><b>Японский бинокль Bushnell</b> – лидер на мировом рынке по производству оптики!</p>
            <p>От продукции компании Bushnell следует ожидать не меньше, чем от произведения искусства и чуда оптической техники в упаковке. Это сплав работы, творчества и опыта, не имеющий аналогов по долговечности.</p>
            <p>Оптика на основе <b>Порро-призм с многослойным просветлением</b> обеспечивает резкое и насыщенное изображение по всему полю зрения.</p>

            		<p><b>Обрезиненный корпус</b> защитит прибор от лёгких ударов, царапин и потёртостей, а также не позволит выронить бинокль из рук даже в сырую погоду, что особенно важно при ведении наблюдений на воде.</p>
          
          <div class="why">
            <h3>Призмы PORRO</h3>
            <img src="img/p2.jpg" tppabs="images/p2.jpg" alt="Бинокль Bushnell" title="Бинокль Bushnell">
            <h3>Технология MULTICOATED</h3>
            <img src="img/p1.jpg" tppabs="images/p1.jpg" alt="Бинокль Bushnell" title="Бинокль Bushnell">
            <h3>Увеличение 60 крат</h3>
            <img src="img/p3.jpg" tppabs="images/p3.jpg" width=420 alt="Бинокль Bushnell" title="Бинокль Bushnell">
          </div>
        </div>
      </div></section>

      <!-- Block-3-->
      <section class="block-3" style="background: #263e58">
        <div class="container">
          <h2 class="zag white">ВОЗМОЖНОСТИ<br><span class="yellow">ПРИМЕНЕНИЯ</span></h2>
            <ul class="clearfix">
                <li><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/li1.jpg" tppabs="images/li1.jpg"></li>
                <li><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/li2.jpg" tppabs="images/li2.jpg"></li>
                <li><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/li3.jpg" tppabs="images/li3.jpg"></li>
                <li><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/li4.jpg" tppabs="images/li4.jpg"></li>
                
            </ul>

          <a href="#zakaz" class="btn-order">Заказать со скидкой</a>
        </div><!-- after_qbici -->
      </section>
      <!-- Block-4-->
      <section class="block-4">
        <div class="container">
          <h2 class="zag"><span class="yellow">ХАРАКТЕРИСТИКИ</span></h2>
          <div class="ullist clearfix">
 

 
 
 
 
 



            <ul class="item left">
                <li><b>Производитель:</b> Bushnell</li>
                <li><b>Габариты:</b> 200 х 175 х 60 мм</li>
                <li><b>Крышки объектива:</b> 2 шт.</li>
                <li><b>Корпус:</b> Металлический, обрезиненный</li>
				<li><b>Тип призмы:</b> Porro</li>
				<li><b>Тип стекла:</b> Porro</li>
            </ul>      
            <ul class="item right">    
                <li><b>Фокусировка:</b> Центральная</li>
                <li><b>Заполнение азотом:</b> есть</li>
                <li><b>Ширина поля зрения:</b> 73/1000 м</li>
                <li><b>Повышение кратности:</b> 60 крат</li>
				<li><b>Комплектация:</b> Бинокль, ремешок,
 2 крышки объектива,<br>
 2 крышки окуляра</li>
            </ul>
          </div>
          
        </div>
      </section>
      <!-- Block-6-->
      <section class="block-6" style="background: #263e58">
        <div class="container">
          <h2 class="zag">КАК <span class="yellow">ЗАКАЗАТЬ?</span></h2>
          <ul>
            <li>
              <img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/d1-min.png" tppabs="images/d1-min.png">
              <p>Оставляете заявку на сайте</p></li>
            <li>
            <img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/d2-min.png" tppabs="images/d2-min.png">
              <p>Наш менеджер уточняет детали заказа</p>
            </li>
            <li>
              <img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/d3-min.png" tppabs="images/d3-min.png">
              <p>Служба доставки доставляет ваш товар</p>
            </li>
            <li>
              <img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/d4-min.png" tppabs="images/d4-min.png">
              <p>Оплачиваете при получении</p>
            <li>
          </li></li></ul>
        </div>
      </section>
      <!-- Block-5-->
      <section class="block-5"><!-- owmen17 -->
        <h2 class="zag">ОТЗЫВЫ ПОКУПАТЕЛЕЙ</h2>
        <div class="slider-wrap">
          <!-- Slider-->
          <div class="slider-rev">
            <!-- slide block-->
                 <div class="slide-block"><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/rev1.jpg" tppabs="images/rev1.jpg">
            <h3>Лобанов Олег, 30 лет</h3>

            <p>Неделю назад сбылась моя детская мечта – получил свой бинокль. Что могу сказать, биноклем очень доволен, не ожидал, что будет такое чёткое изображение, не желтит, в отличие от советских биноклей. В общем, очень доволен. Кто думает брать или нет, берите – не пожалеете!</p>
            </div>
            <!-- slide block-->
            <div class="slide-block"><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/rev2.jpg" tppabs="images/rev2.jpg">
              <h3>Анатолий </h3>
            <p>Заказал Bushnell. Качество, начиная от упаковки и заканчивая изделием, на высоте. Упаковано очень надёжно и добросовестно. Бинокль очень понравился, видимость на 5+! Посылка пришла быстро. Спасибо ребятам за труд.</p>
            </div>
            <!-- slide block--><!-- endzeit_____ -->
            <div class="slide-block"><img alt="Бинокль Bushnell" title="Бинокль Bushnell" src="img/rev3.jpg" tppabs="images/rev3.jpg">
              <h3>Юрий Петров, 45 лет</h3>
            <p>Большое спасибо Вам за отличную работу! Очень качественная оптика, пользуюсь уже двумя биноклями с вашего сайта – полностью доволен, отличная коллимация и качество изготовления. Удивлён, признаться, не ожидал. Буду рекомендовать к приобретению!</p>
            </div>
          </div><span class="arrow-left1"></span><span class="arrow-right1"></span>
      </div></section>
      <!-- Footer-->
      <footer class="footer">
        <div class="container">
          <div class="discount">
            <div>ТОЛЬКО СЕГОДНЯ<br><span>СКИДКА <?=$sale?>%</span></div>
          </div> 
          <div class="timer">
            <p>До конца акции осталось:</p>
            <div class="countbox"></div>
          </div>
          <div class="cost-wrap">
           <div class="old-cost">
              <div>Обычная цена<span><?=$price_old?> <?=$currency?>.</span></div>
            </div>
            <div class="new-cost"><!-- bizlife_inc -->
              <div><strong class="yellow"><?=$price_new?> <?=$currency?>.</strong></div>
            </div>
            </div>
          
          <div class="form">
            <form id="zakaz" action="zakaz.php" method="post" >
              <input type="text" name="name" placeholder="Введите ваше имя" required="required" class="name input"><!-- michael-k90 -->
              <input type="text" name="phone" placeholder="Введите ваш телефон" required="required" class="phone input">
                <input type="hidden" name="s1" class="price_field_s1" value="<?= $price_new ?>" />
                <input type="hidden" name="s2" class="price_field_s2" value="<?= $product_id ?>" />
                <input type="hidden" name="s3" class="price_field_s3" value="<?= $comment ?>" />
                <input type="hidden" name="utm_source" value="<?= $_GET['utm_source'] ?>" />
                <input type="hidden" name="utm_medium"  value="<?= $_GET['utm_medium'] ?>" />
                <input type="hidden" name="utm_term"  value="<?= $_GET['utm_term'] ?>" />
                <input type="hidden" name="utm_content"  value="<?= $_GET['utm_content'] ?>" />
                <input type="hidden" name="utm_campaign" value="<?= $_GET['utm_campaign'] ?>" />
                <input type="hidden" name="server_name" value="<?= $_SERVER['SERVER_NAME'] ?>" />
                <input type="hidden" name="php_self" value="<?= $_SERVER['PHP_SELF'] ?>" />
                
              <button class="btn-order">Заказать со скидкой</button>
            </form>
          </div>
        </div>

      </footer>





      <script src="js/slick.min.js" tppabs="js/slick.min.js"></script>
    <script src="js/count.js" tppabs="js/count.js"></script>
    <script src="js/init.js" tppabs="js/init.js"></script>
    <script src="js/jquery.inputmask.min.js" tppabs="js/init.js"></script>

  </div>

  <script type="text/javascript">


      $(document).ready(function() {

          Inputmask.extendDefinitions({

              '~': {
                  validator: "[1245679]"
              }

          });
          $("input[name='name']").on("keypress", function (e) {
              return (/[A-Za-zА-Яа-яЁё\s]/.test(String.fromCharCode(e.charCode)));
          })
          $("input[name='phone']").inputmask({
              mask: "+38 (0~9) 999-99-99",
              greedy: false,
              clearIncomplete: true,
              placeholder: "_",
              rightAlign: false,
              showMaskOnHover: false,
              showMaskOnFocus: true
          });
          $("input[name='phone']").on("keydown", function (e) {

              if(e.keyCode == 37 || e.keyCode == 38 || e.keyCode == 39 || e.keyCode == 40){
                  e.preventDefault();
                  return false;
              }

          });




      });

  </script>

  </body>
</html>