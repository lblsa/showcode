<?php $this->pageTitle=Yii::app()->name; ?>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.bxSlider.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function(){
      // assign the slider to a variable
      var slider = $('#slider').bxSlider({
            controls: false,
            auto: true,
            speed: 500,
            pause: 5000,
            pager: true
      });

      // assign a click event to the external thumbnails
      $('.thumbs a').click(function(){
       var thumbIndex = $('.thumbs a').index(this);
            // call the "goToSlide" public function
            slider.goToSlide(thumbIndex);

            // remove all active classes
            $('.thumbs a').removeClass('pager-active');
            // assisgn "pager-active" to clicked thumb
            $(this).addClass('pager-active');
            // very important! you must kill the links default behavior
            return false;
      });

      // assign "pager-active" class to the first thumb
      $('.thumbs a:first').addClass('pager-active');

      $("#slider li").mouseover(function(){
            slider.stopShow(true);
            
        });

        $("#slider li").mouseout(function(){
            slider.startShow(true);
        });
    });
</script>

<!--<h1>Добро пожаловать в <i><?php //echo CHtml::encode(Yii::app()->name); ?></i></h1>

<div class="info-user">
	<p><span>ID пользователя:</span> <?php //echo Yii::app()->user->id;?>.</p>
	<p><span>Имя пользователя:</span> <?php //echo Yii::app()->user->name; ?>.</p>
	<p><span>Телефон пользователя:</span> +<?php //echo Yii::app()->user->phone; ?>.</p>
	<p><span>E-mail пользователя:</span> <?php //echo Yii::app()->user->email; ?>.</p>
	<p><span>Роль пользователя:</span> <?php //echo Yii::app()->user->role; ?>.</p>
</div>
<br/>-->

<?php
$r='Opera/9.80 (J2ME/MIDP; Opera Mini/4.2.14912/812; U; ru) Presto/2.4.15';
//echo $_SERVER['HTTP_USER_AGENT'];
/*
 * Yii::app()->user->isGuest; вернёт true, если гость (не авторизован)
 * Yii::app()->user->isAdmin(); вернёт true, если админ
 * Yii::app()->user->isOrganizer(); вернёт true, если организатор
 * Yii::app()->user->isCreator(); вернёт true, если создатель мероприятия
 * Yii::app()->user->getAuthorName($user_id); выдаст имя пользователя по id
 * Статусы мероприятия: print_r(Events::$STATUS);
 * echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 */

 /*
$RSA = new RSA();
$keys = $RSA->generate_keys (Events::get_prime(), Events::get_prime(), 1);	//2 простых числа и коэф. для отладки
//$keys[0] - Общий ключ
//$keys[2] - закрытый. кодируем им
//$keys[1] - открытый. раскодируем им.
$message="title=Мероприятие с билетами от Дмитрия&datetime=2011-08-20 19:00:00&quantity=1&uniq=25306070";
$encoded = $RSA->encrypt ($message, $keys[2], $keys[0], 90);		// и коэф. сложности кодирования(настраивается в зависимости от величины входящих простых чисел)
$decoded = $RSA->decrypt ($encoded, $keys[1], $keys[0]);


echo "<b>Message:</b> $message<br />\n";
echo "<b>Encoded:</b> $encoded<br />\n";
echo "<b>Decoded:</b> $decoded<br />\n";
echo "Success: ".(($decoded == $message) ? "True" : "False")."<hr />\n";
*/
?>

<!-- SLIDER:begin -->
<div id="slider_wrapper">
    <ul id="slider">
    <!-- one item:begin -->
    <li>
            <img src="/images/slider_img/4.jpg" alt="" />
        <div class="slider_descr">
            <table>
                <tr>
                    <td class="uppercase">Концерт</td>
                    <td class="uppercase"><img src="/images/brace.png" alt="Showcode" id="brace" /></td>
                    <td class="slide_text"><?php echo PageContent::model()->getContentByUniq('imageConcertText') ?></td>
                </tr>
            </table>
        </div>
    </li>
    <!-- one item:end -->
    <!-- one item:begin -->
    <li>
            <img src="/images/slider_img/2.jpg" alt="" />
        <div class="slider_descr">
            <table>
                <tr>
                    <td class="uppercase">Выставка</td>
                    <td class="uppercase"><img src="/images/brace.png" alt="Showcode" id="brace" /></td>
                    <td class="slide_text"><?php echo PageContent::model()->getContentByUniq('imageExpositionText') ?></td>
                </tr>
            </table>
        </div>
    </li>
    <!-- one item:end -->
    <!-- one item:begin -->
    <li>
            <img src="/images/slider_img/3.jpg" alt="" />
        <div class="slider_descr">
            <table>
                    <tr>
                    <td class="uppercase">Ночной клуб</td>
                    <td class="uppercase"><img src="/images/brace.png" alt="Showcode" id="brace" /></td>
                    <td class="slide_text"><?php echo PageContent::model()->getContentByUniq('imageNightClubText') ?></td>
                </tr>
            </table>
        </div>
    </li>
    <!-- one item:end -->
    <!-- one item:begin -->
    <li>
            <img src="/images/slider_img/6.jpg" alt="" />
        <div class="slider_descr">
            <table>
                <tr>
                    <td class="uppercase">Музей</td>
                    <td class="uppercase"><img src="/images/brace.png" alt="Showcode" id="brace" /></td>
                    <td class="slide_text"><?php echo PageContent::model()->getContentByUniq('imageMuseumText') ?></td>
                </tr>
            </table>
        </div>
    </li>
    <!-- one item:end -->
    <!-- one item:begin -->
    <li>
            <img src="/images/slider_img/7.jpg" alt="" />
        <div class="slider_descr">
            <table>
                    <tr>
                    <td class="uppercase">Ресторан</td>
                    <td class="uppercase"><img src="/images/brace.png" alt="Showcode" id="brace" /></td>
                    <td class="slide_text"><?php echo PageContent::model()->getContentByUniq('imageRestaurantText') ?></td>
                </tr>
            </table>
        </div>
    </li>
    <!-- one item:end -->
    <!-- one item:begin -->
    <li>
            <img src="/images/slider_img/8.jpg" alt="" />
        <div class="slider_descr">
            <table>
                <tr>
                    <td class="uppercase">Театр</td>
                    <td class="uppercase"><img src="/images/brace.png" alt="Showcode" id="brace" /></td>
                    <td class="slide_text"><?php echo PageContent::model()->getContentByUniq('imageTheatertext') ?></td>
                </tr>
            </table>
        </div>
    </li>
    <!-- one item:end -->
    <!-- one item:begin -->
    <li>
            <img src="/images/slider_img/9.jpg" alt="" />
        <div class="slider_descr">
            <table>
                <tr>
                    <td class="uppercase">Школа танцев</td>
                    <td class="uppercase"><img src="/images/brace.png" alt="Showcode" id="brace" /></td>
                    <td class="slide_text"><?php echo PageContent::model()->getContentByUniq('imageDanceSchoolText') ?></td>
                </tr>
            </table>
        </div>
    </li>
    <!-- one item:end -->
    <!-- one item:begin -->
    <li>
            <img src="/images/slider_img/10.jpg" alt="" />
        <div class="slider_descr">
            <table>
                <tr>
                    <td class="uppercase">Экскурсии</td>
                    <td class="uppercase"><img src="/images/brace.png" alt="Showcode" id="brace" /></td>
                    <td class="slide_text"><?php echo PageContent::model()->getContentByUniq('imageExcursionsText') ?></td>
                </tr>
            </table>
        </div>
    </li>
    <!-- one item:end -->
    <!-- one item:begin -->
    <li>
        <img src="/images/slider_img/5.jpg" alt="" />
        <div class="slider_descr">
            <table>
                    <tr>
                    <td class="uppercase">Йога</td>
                    <td class="uppercase"><img src="/images/brace.png" alt="Showcode" id="brace" /></td>
                    <td class="slide_text"><?php echo PageContent::model()->getContentByUniq('imageYogaText') ?></td>
                </tr>
            </table>
        </div>
    </li>
    <!-- one item:end -->
    <!-- one item:begin -->
    <li>
            <img src="/images/slider_img/1.jpg" alt="" />
        <div class="slider_descr">
            <table>
                    <tr>
                    <td class="uppercase">Благотворительный вечер</td>
                    <td class="uppercase"><img src="/images/brace.png" alt="Showcode" id="brace" /></td>
                    <td class="slide_text"><?php echo PageContent::model()->getContentByUniq('imageEveningText') ?></td>
                </tr>
            </table>
        </div>
    </li>
    <!-- one item:end -->
    <!-- one item:begin -->
    <li>
            <img src="/images/slider_img/11.jpg" alt="" />
        <div class="slider_descr">
            <table>
                <tr>
                    <td class="uppercase">Бесплатные события</td>
                    <td class="uppercase"><img src="/images/brace.png" alt="Showcode" id="brace" /></td>
                    <td class="slide_text"><?php echo PageContent::model()->getContentByUniq('imageFreeЕventsText') ?></td>
                </tr>
            </table>
        </div>
    </li>
    <!-- one item:end -->
</ul>
</div>
<!-- SLIDER:end -->

<!-- NEW MESSAGE AND REGISTRATION BLOCKS (right column):begin -->
<div id="wrapper_new_mess_and_reg_block">
        <!-- new message -->
    <!-- registration -->
    <?php if(Yii::app()->user->isGuest): ?>
        <div class="reg_text">
            <!-- title -->
            <div class="reg_text_title"><?php echo CHtml::link('Регистрация', array('user/create')); ?></div>                        
        </div>
    <?php endif; ?>
    <div class="new_message">
        <!-- title -->
        <div class="new_message_title">
            <?php if(Yii::app()->user->isAdmin() || Yii::app()->user->isOrganizer()): ?>
                <?php echo CHtml::link('СОЗДАТЬ<span>новое событие</span>', array('/events/create')); ?>
            <?php else: ?>
                <?php echo CHtml::link('Смотреть<span>мероприятия</span>', array('/events')); ?>
            <?php endif; ?>
        </div>
        <!-- description -->
        <div class="new_message_descr">
            <?php echo PageContent::model()->getContentByUniq('mainCreateEventText') ?>
            <script type="text/javascript">
                function openMenu(){
                    document.getElementById("payment").style.display = "block";
                };
                function closeMenu(){
                    document.getElementById("payment").style.display = "none";
                    return false;
                };
            </script>
            <a style="background: url(http://showcode.ru/images/button_buy_ticket.png) no-repeat scroll left top transparent; color: #FFFFFF; cursor: pointer; display: block; font-size: 13px; height: 41px; line-height: 20px; margin: 4px auto; padding-bottom: 2px; position: relative; text-align: center; text-decoration: none; text-shadow: 0 1px 1px #565656; vertical-align: middle; width: 180px;" id="button_bye" href="#" onClick="openMenu();return false;"></a>
            <div id="payment" style="border: medium none;box-shadow: 0 0 30px -5px #000000;display: none;height: 522px;left: 50%;margin-left: -408px;margin-top: -267px;padding: 3px;position: fixed;top: 50%;width: 784px; z-index: 65010;">
                <input type="button" value="" onclick="javascript:closeMenu();return false;" id="buy_close" style="width: 17px;border: none; background:url(http://showcode.ru/images/close_button.png) left top no-repeat; display: block; left: 740px; top: 20px; position: absolute; cursor: pointer;" />
                <iframe src="http://showcode.ru/events/iframe/9d58b520" width="100%" height="100%" style="border:none;"></iframe>
            </div>                  
            
        </div>
    </div>
</div>
<!-- NEW MESSAGE AND REGISTRATION BLOCK (right column):end -->