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




<!DOCTYPE html>
<html lang="ru-RU">
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>

    <title>НОСКИ BURMATISH</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=480">

    <meta name="description" content="НОСКИ BURMATISH">

    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">


</head>
<body>

<div class="main_wrapper">

    <!-- fixed block -->

    <div class="fixed_section">
        <div class="fixed_block clearfix">
            <h3>НОСКИ BURMATISH</h3>
            <a class="button" href="#order">Заказать сейчас</a>
        </div>
    </div>

    <!-- /fixed block -->

    <!-- offer -->

    <div class="offer_section">
        <div class="offer_title">
            <h1>Носки для мужчин на зиму-весну по скидке <?= $sale; ?>% от производителя</h1>
            <p></p>
        </div>
        <img src="img/offer_top.jpg" alt="НОСКИ BURMATISH">
    </div>

    <!-- /offer -->

    <!-- description -->

    <section class="description_section">
        <div class="title_block">
            <h2>В ЛЮБОЙ ДЕНЬ, ДЛЯ ЛЮБОГО СТИЛЯ ВЫБИРАЙТЕ BURMATISH.</h2>
        </div>
        <div class="content_block">

            <div class="in-stats_block">
                <div class="icons clearfix">
                    <div class="icon like_icon"></div>
                    <div class="icon comment_icon"></div>
                    <div class="icon send_icon"></div>
                    <div class="icon share_icon"></div>
                </div>
                <div class="likes_count">Нравится: 8 267</div>
            </div>
            <div class="text_block">
                <h1>

                </h1>
                <p>Наши носки являются универсальными потому что они подходят как молодым парням, так и взрослым мужчинам. Кейс носков - это отличная покупка для людей, которые ценят свое время и деньги. Ведь носки это одна из самых покупаемых деталей гардероба. Неважно сколько Вам лет 20, 30 или 45. Не важно кто Вы, молодой спортсмен или взрослый бизнесмен. Носки — это неотъемлемая часть любого стиля и образа жизни</p>
            </div>
            <div class="vk-stats_block clearfix">
                <div class="count likes_count">Нравится 8 267</div>
                <div class="count share_count">391</div>
                <div class="count view_count">288К</div>
            </div>
            <div class="ok-stats_block clearfix">
                <div class="counts">
                    <div class="count likes_count">8 267</div>
                    <div class="count share_count">Поделились 391</div>
                </div>
                <div class="buttons">
                    <div class="button share_button">Поделиться</div>
                    <div class="button like_button">Класс!</div>
                </div>
            </div>
        </div>
    </section>

    <!-- /description -->

    <!-- order 1.1 -->

    <section class="order_section in-blue_title">
        <div class="title_block">
            <h2>Заказать <i>НОСКИ BURMATISH</i></h2>
        </div>
        <div class="order_block">
            <div class="price_block clearfix">
                <div class="price_item old">
                    <div class="text">Обычная цена:</div>
                    <div class="value"><?= $price_old ?><?= $currency ?></div>
                </div>
                <div class="price_item new">
                    <div class="text">Цена по акции:</div>
                    <div class="value"><?= $price_new ?><?= $currency ?></div>
                </div>
            </div>
            <form class="order_form"
                  action="zakaz.php" method="post">
                <input class="field" type="text" name="name" placeholder="Введите Ваше имя" required>
                <input class="field" type="tel" name="phone" placeholder="Введите Ваш телефон" required>
                <button class="button">Заказать сейчас</button>

                <input type="hidden" name="s1" class="price_field_s1" value="<?= $price_new ?>"/>
                <input type="hidden" name="s2" class="price_field_s2" value="<?= $product_id ?>"/>
                <input type="hidden" name="s3" class="price_field_s3" value="<?= $comment ?>"/>
                <input type="hidden" name="utm_source" value="<?= $_GET['utm_source'] ?>"/>
                <input type="hidden" name="utm_medium" value="<?= $_GET['utm_medium'] ?>"/>
                <input type="hidden" name="utm_term" value="<?= $_GET['utm_term'] ?>"/>
                <input type="hidden" name="utm_content" value="<?= $_GET['utm_content'] ?>"/>
                <input type="hidden" name="utm_campaign" value="<?= $_GET['utm_campaign'] ?>"/>
                <input type="hidden" name="server_name" value="<?= $_SERVER['SERVER_NAME'] ?>"/>
                <input type="hidden" name="php_self" value="<?= $_SERVER['PHP_SELF'] ?>"/>
                <input type="hidden" name="uid" value="<?= $uid ?>"/>
            </form>
            <p class="deadline_text">Количество кейсов по специальной цене ограничено</p>
        </div>
    </section>

    <!-- /order 1.1 -->

    <!-- benefits 4.2 -->

    <section class="list_section benefits4 dark_theme">
        <div class="title_block">
            <h2>ОСОБЕННОСТИ BURMATISH</h2>
        </div>
        <div class="list2 two_column">
            <div class="list_item">
                <img src="img/benefits4_image1.jpg" alt="НОСКИ BURMATISH">
                <p>Устранение неприятного запаха</p>
            </div>
            <div class="list_item">
                <img src="img/benefits4_image2.jpg" alt="НОСКИ BURMATISH">
                <p>Антибактериальное действие</p>
            </div>
            <div class="list_item">
                <img src="img/benefits4_image3.jpg" alt="НОСКИ BURMATISH">
                <p>Многочисленная стирка</p>
            </div>
            <div class="list_item">
                <img src="img/benefits4_image4.jpg" alt="НОСКИ BURMATISH">
              <p>Влагоотталкивающие свойства</p>
            </div>
            <div class="list_item">
                <img src="img/benefits4_image5.jpg" alt="НОСКИ BURMATISH">
              <p>Высокая плотность плетения</p>
            </div>
            <div class="list_item">
                <img src="img/benefits4_image6.jpg" alt="НОСКИ BURMATISH">
              <p>Усиленные пятка и носок</p>
            </div>
        </div>
    </section>

    <!-- /benefits 4.2 -->

    <!-- benefits 3.1 -->

    <section class="list_section benefits3">
        <div class="title_block">
            <h2>ПРЕИМУЩЕСТВА BURMATISH</h2>
        </div>
        <div class="list2">
            <div class="list_item">
                <img src="img/benefits3_image1.jpg" alt="НОСКИ BURMATISH">
                <h4>Не содержат полиамид </h4>
                <p>Носки Burmatish содержат 90% натурального бамбукового волокна. </p>
            </div>
            <div class="list_item">
                <img src="img/benefits3_image2.jpg" alt="НОСКИ BURMATISH">
                <h4>Устроняет отечность</h4>
                <p>Нормализуют внутривенное давление на протяжении дня. </p>
            </div>
            <div class="list_item">
                <img src="img/benefits3_image3.jpg" alt="НОСКИ BURMATISH">
                <h4>Комфортная температура</h4>
                <p>Носки поддерживают оптимальный уровень температуры, для Вашего комфорта.</p>
            </div>
        </div>
    </section>

    <!-- /benefits 3.1 -->

    <!-- description -->


    <!-- /description -->

    <!-- description -->

    <section class="description_section">
        <div class="title_block">
            <h2>ХАРАКТЕРИСТИКИ</h2>
        </div>
        <div class="content_block">
            <div class="list3 marker1">
                <div class="list_item">10 пар натуральных хлопковых носков
                </div>
                <div class="list_item">Презентабельный подарочный кейс
                </div>
                <div class="list_item">Австрийское качество
                </div>
                <div class="list_item">Содержат ионы серебра
                </div>
                <div class="list_item">Бесшовное плетение
                </div>
            </div>
        </div>
    </section>

   
<!-- 
    <section class="list_section benefits4 dark_theme">
        <div class="title_block">
            <h2>ВОЗМОЖНОСТИ GPS-ТРЕКЕРА</h2>
        </div>
        <div class="list2 two_column">
            <div class="list_item">
                <img src="img/benefits4_image_2_1.jpg" alt="GPS-ТРЕКЕР">
                <p>Уведомления об угоне авто</p>
            </div>

            <div class="list_item">
                <img src="img/benefits4_image_2_3.jpg" alt="GPS-ТРЕКЕР">
                <p>Определение координат</p>
            </div>
            <div class="list_item">
                <img src="img/benefits4_image_2_4.jpg" alt="GPS-ТРЕКЕР">
                <p>Установка 5 минут</p>
            </div>
            <div class="list_item">
                <img src="img/benefits4_image_2_2.jpg" alt="GPS-ТРЕКЕР">
                <p>Маленький размер</p>
            </div>

        </div>
    </section>
 -->
    <!-- /benefits 4.2 -->

    <!-- reviews 1 -->

    <section class="reviews1_section">
        <div class="title_block">
            <h2>Основатель компании BURMATISH</i></h2>

        </div>
        <div class="content_block">

            <img class="post_image" src="img/description_image.jpg" alt="НОСКИ BURMATISH">

            <div class="text_block">
                <p>
                    “Если Вам кажется, что одежда - главная часть Вашего костюма, то Вы обречены на провал. На своем опыте, я убедился, что аксессуары, обувь и носки - настоящее доказательство мужской солидности.” - HERMANN JOHANNES BURMATISH </p>
            </div>

        </div>

    </section>


    <section class="reviews1_section">
        <div class="title_block">
            <h2>Отзывы покупателей</h2>
        </div>

        <div class="content_block">
            <div class="author_block">
                <div class="avatar">
                    <img src="img/review2_avatar.jpg" alt="НОСКИ BURMATISH">
                </div>
                <div class="author_info">
                    <div class="name">Николай Самойлов</div>
                    <div class="text">г. Белая Церковь</div>
                </div>
            </div>
            <img class="post_image" src="img/review2_photo.jpg" alt="НОСКИ BURMATISH">
            <div class="in-stats_block">
                <div class="icons clearfix">
                    <div class="icon like_icon"></div>
                    <div class="icon comment_icon"></div>
                    <div class="icon send_icon"></div>
                    <div class="icon share_icon"></div>
                </div>
                <div class="likes_count">Нравится: 169</div>
            </div>
            <div class="text_block">
                <p>Я вообще не очень требовательный, но проблема с носками, как и у всех мужчин, у меня присутствовала тоже. То цвет не подходит, то рвутся быстро. А эти носки очень износостойкие. После стирки не меняют свой цвет и размер. Так что советую вам мужики.</p>
            </div>
            <div class="vk-stats_block clearfix">
                <div class="count likes_count">Нравится 169</div>
                <div class="count share_count">18</div>
                <div class="count view_count">1.8К</div>
            </div>
            <div class="ok-stats_block clearfix">
                <div class="counts">
                    <div class="count likes_count">169</div>
                    <div class="count share_count">Поделились 18</div>
                </div>
                <div class="buttons">
                    <div class="button share_button">Поделиться</div>
                    <div class="button like_button">Класс!</div>
                </div>
            </div>
            <div class="in-date">5 дней назад</div>
        </div>
        <div class="content_block">
            <div class="author_block">
                <div class="avatar">
                    <img src="img/review4_avatar.jpg" alt="НОСКИ BURMATISH">
                </div>
                <div class="author_info">
                    <div class="name">Юлия Кравчук</div>
                    <div class="text">г. Запорожье</div>
                </div>
            </div>
            <img class="post_image" src="img/review3_photo.jpg" alt="НОСКИ BURMATISH">

            <div class="in-stats_block">
                <div class="icons clearfix">
                    <div class="icon like_icon"></div>
                    <div class="icon comment_icon"></div>
                    <div class="icon send_icon"></div>
                    <div class="icon share_icon"></div>
                </div>
                <div class="likes_count">Нравится: 336</div>
            </div>
            <div class="text_block">
                <p>Сделала подарок папе на день рождения. Купила эти носки первый раз, по совету знакомых. Папе очень понравились тем, что они универсальные. Подходят и под официальный стиль, и для повседневной жизни. А главное, что для своего качества цена низкая. Доставка 2 дня, как раз получила вовремя. И уж очень мне понравилась девушка, которая принимала мой заказ, очень вежливая и все подробно рассказала.</p>
            </div>
            <div class="vk-stats_block clearfix">
                <div class="count likes_count">Нравится 336</div>
                <div class="count share_count">84</div>
                <div class="count view_count">2.9К</div>
            </div>
            <div class="ok-stats_block clearfix">
                <div class="counts">
                    <div class="count likes_count">336</div>
                    <div class="count share_count">Поделились 84</div>
                </div>
                <div class="buttons">
                    <div class="button share_button">Поделиться</div>
                    <div class="button like_button">Класс!</div>
                </div>
            </div>
            <div class="in-date">6 дней назад</div>
        </div>
        <div class="content_block">
            <div class="author_block">
                <div class="avatar">
                    <img src="img/review3_avatar.jpg" alt="НОСКИ BURMATISH">
                </div>
                <div class="author_info">
                    <div class="name">Федор Пономаренко</div>
                    <div class="text">г. Харьков</div>
                </div>
            </div>
            <img class="post_image" src="img/review1_photo.jpg" alt="НОСКИ BURMATISH">
            <div class="in-stats_block">
                <div class="icons clearfix">
                    <div class="icon like_icon"></div>
                    <div class="icon comment_icon"></div>
                    <div class="icon send_icon"></div>
                    <div class="icon share_icon"></div>
                </div>
                <div class="likes_count">Нравится: 118</div>
            </div>
            <div class="text_block">
                <p>Для меня, как для любого спортсмена, носки — это неотъемлемая часть жизни, так как при занятии спорта они доставляют очень много неудобства. Но Бурматиш полностью искоренил эту проблему. Теперь я могу заниматься спортом полноценно, ведь мои ноги абсолютно не потеют и не пахнут. А также при нагрузках, не пережимают ноги.</p>
            </div>
            <div class="vk-stats_block clearfix">
                <div class="count likes_count">Нравится 118</div>
                <div class="count share_count">27</div>
                <div class="count view_count">1.6К</div>
            </div>
            <div class="ok-stats_block clearfix">
                <div class="counts">
                    <div class="count likes_count">118</div>
                    <div class="count share_count">Поделились 27</div>
                </div>
                <div class="buttons">
                    <div class="button share_button">Поделиться</div>
                    <div class="button like_button">Класс!</div>
                </div>
            </div>
            <div class="in-date">8 дней назад</div>
        </div>

    </section>

    <!-- /reviews1 -->

    <!-- order info 1 -->

    <section class="list_section order_info1">
        <div class="title_block">
            <h2>Доставка и оплата</h2>
        </div>
        <div class="list1 image90x90 dark_icon ok-square_icon">
            <div class="list_item clearfix">
                <div class="image_block">
                    <div class="image_wrapper">
                        <img src="img/order_info1__icon1.png" alt="НОСКИ BURMATISH">
                    </div>
                </div>
                <div class="text_block">
                    <h4>Быстрая доставка</h4>
                    <p>Почтой в течение 1-3 дней. Оставьте заявку и менеджер свяжется с
                        Вами в ближайшее время для оформления заказа.</p>
                </div>
            </div>
            <div class="list_item clearfix">
                <div class="image_block">
                    <div class="image_wrapper">
                        <img src="img/order_info1__icon2.png" alt="НОСКИ BURMATISH">
                    </div>
                </div>
                <div class="text_block">
                    <h4>Оплата при получении</h4>
                    <p>Оплата производится только при получении заказа на руки.</p>
                </div>
            </div>
            <div class="list_item clearfix">
                <div class="image_block">
                    <div class="image_wrapper">
                        <img src="img/order_info1__icon3.png" alt="НОСКИ BURMATISH">
                    </div>
                </div>
                <div class="text_block">
                    <h4>Гарантии</h4>
                    <p>Вся продукция прошла проверку и полностью сертифицирована!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- /order info 1 -->

    <!-- order 1.1 -->

    <section id="order" class="order_section in-blue_title dark_theme">
        <div class="title_block">
            <h2>Заказать <i>НОСКИ BURMATISH</i></h2>
        </div>
        <div class="order_block">
            <div class="price_block clearfix">
                <div class="price_item old">
                    <div class="text">Обычная цена:</div>
                    <div class="value"><?= $price_old ?><?= $currency ?></div>
                </div>
                <div class="price_item new">
                    <div class="text">Цена по акции:</div>
                    <div class="value"><?= $price_new ?><?= $currency ?></div>
                </div>
            </div>
            <form class="order_form"
                  action="zakaz.php" method="post">
                <input class="field" type="text" name="name" placeholder="Введите Ваше имя" required>
                <input class="field" type="tel" name="phone" placeholder="Введите Ваш телефон" required>
                <button class="button">Заказать сейчас</button>


                <input type="hidden" name="s1" class="price_field_s1" value="<?= $price_new ?>"/>
                <input type="hidden" name="s2" class="price_field_s2" value="<?= $product_id ?>"/>
                <input type="hidden" name="s3" class="price_field_s3" value="<?= $comment ?>"/>
                <input type="hidden" name="utm_source" value="<?= $_GET['utm_source'] ?>"/>
                <input type="hidden" name="utm_medium" value="<?= $_GET['utm_medium'] ?>"/>
                <input type="hidden" name="utm_term" value="<?= $_GET['utm_term'] ?>"/>
                <input type="hidden" name="utm_content" value="<?= $_GET['utm_content'] ?>"/>
                <input type="hidden" name="utm_campaign" value="<?= $_GET['utm_campaign'] ?>"/>
                <input type="hidden" name="server_name" value="<?= $_SERVER['SERVER_NAME'] ?>"/>
                <input type="hidden" name="php_self" value="<?= $_SERVER['PHP_SELF'] ?>"/>
                <input type="hidden" name="uid" value="<?= $uid ?>"/>
            </form>
            <p class="deadline_text">Количество кейсов по специальной цене ограничено</p>
        </div>
    </section>

    <!-- /order 1.1 -->

    <!-- footer -->

    <footer>
    </footer>

    <!-- /footer -->

</div>

<!-- scripts -->


<link rel="stylesheet" type="text/css" href="css/reset.css">
<link rel="stylesheet" type="text/css" href="css/styles.css">


<link rel="stylesheet" type="text/css" href="css/roboto.css">
<script type="text/javascript" src="js/html5shiv.js"></script>


<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/pluginsf7cd.js?v=1548839062" type="text/javascript"></script>
<script src="js/scripts.js" type="text/javascript"></script>
<script src="js/mask_input.js" type="text/javascript"></script>

<script type="text/javascript">


    $(document).ready(function () {
        $('form').submit(function (event) {

            var phon = $(this).find("input[name='phone']").val();

            if (phon.indexOf('_') == -1 && phon != null && typeof phon !== "undefined" && phon != '') {

            } else {
                alert('Введите Ваш номер телефона!');
                return false;
            }
        });
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

            if (e.keyCode == 37 || e.keyCode == 38 || e.keyCode == 39 || e.keyCode == 40) {
                e.preventDefault();
                return false;
            }

        });


    });

</script>

</body>
</html>