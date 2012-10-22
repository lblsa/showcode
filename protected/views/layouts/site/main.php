<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<!--meta name="language" content="en" /-->

	<!-- blueprint CSS framework -->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />-->
	<!--<link rel="stylesheet" type="text/css" href="<?php //echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />-->
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<!--link rel="stylesheet" type="text/css" href="<?php //echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php //echo Yii::app()->request->baseUrl; ?>/css/form.css" /-->

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.8.2.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/feedback.js"></script>
	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap/bootstrap.min.js"></script>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <!-- Meta viewport for iOS systems -->
        <meta name="viewport" content="width=1012" />
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/base.css" />
		
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap/bootstrapmy.css" />
        <!--<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" />-->
        <!-- JS content slider (bxSlider) -->
        <!-- <script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script> -->
        <!--<script src="<?php //echo Yii::app()->request->baseUrl; ?>/js/jquery-latest.js" type="text/javascript"></script>-->

        <!-- Cufon -->
       <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/cufon/cufon-yui.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/cufon/MetaPro-Black_900.font.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/cufon/MetaPro-Bold_700.font.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/cufon/MetaPro-Book_500.font.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/cufon/MetaPro-Medium_500.font.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/cufon/MetaPro-Normal_400.font.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/cufon/Book_Antiqua_700-Book_Antiqua_italic_400.font.js"></script>

<script type="text/javascript">
	Cufon.replace('#tickets', {fontFamily: "MetaPro-Medium" });
	Cufon.replace('#tickets span', {fontFamily: "MetaPro-Normal" });
	Cufon.replace('.text_logo_descr', {
		fontFamily: "MetaPro-Book",
		textShadow: "1px 1px rgba(255, 255, 255, 0.8)",
		lineHeght: '20px',
		fontSize: '16px'
	});
	Cufon.replace('.new_message_title a', {
		fontFamily: "MetaPro-Normal",
		color: '#fb6006',
		lineHeght: '18px',
		fontSize: '40px'
	});
	Cufon.replace('.new_message_title a span', {
		fontFamily: "MetaPro-Normal",
		color: '#fb6006',
		lineHeght: '28px',
		fontSize: '24px'
	});
	Cufon.replace('.reg_text_title a', {
		fontFamily: "MetaPro-Normal",
		color: '#fb6006',
		lineHeght: '18px',
		fontSize: '24px'
	});
	Cufon.replace('.how_to_title p a', {
		fontFamily: "MetaPro-Normal",
		color: '#fb6006',
		lineHeght: '18px',
		fontSize: '52px',
		textShadow: "1px 1px rgba(255, 255, 255, 0.6)",
		letterSpacing: '-1px'
	});
	Cufon.replace('.brace p', {
		fontFamily:'MetaPro-Normal',
		fontSize:'72px',
		color:'#666',
		lineHeght:'18px',
		letterSpacing:'0px',
		textShadow: "1px 1px rgba(255, 255, 255, 0.6)"
	});
	Cufon.replace('.title_t_and_c', {
		fontFamily: "MetaPro-Normal",
		fontSize:'24px',
		color:'#fb6006',
		lineHeght:'18px'
	});
	Cufon.replace('.edit_event_buttons a', {
		fontFamily: "MetaPro-Normal",
		fontSize:'18px',
		color:'#fb6006'
	});
	Cufon.replace('.main_form_wrapper h1', {
		fontFamily: "MetaPro-Normal",
		fontSize:'36px',
		lineHeght:'24px',
		color:'#fb6006',
		textShadow: "-1px -1px rgba(0,0,0, 0.5)"
	});
	Cufon.replace('.main_form_wrapper h2', {
		fontFamily: "MetaPro-Normal",
		fontSize:'24px',
		lineHeght:'18px',
		color:'#fb6006',
		textShadow: "-1px -1px rgba(0,0,0, 0.5)"
	});
	Cufon.replace('#list_events h1', {
		fontFamily: "MetaPro-Normal",
		fontSize:'36px',
		lineHeght:'24px',
		color:'#fb6006',
		textShadow: "-1px -1px rgba(0,0,0, 0.5)"
	});
        Cufon.replace('#event-title-zoo h2', {
            fontFamily: "Book Antiqua",
            fontSize:'23px',
            lineHeight:'17px',
            color:'#ffffff'
        });
        Cufon.replace('#event-title-zoo h2 span', {
            fontFamily: "Book Antiqua",
            fontSize:'13px',
            lineHeight:'10px',
            color:'#ffffff'
        });
		
	//для отзывов
	function send_feedback()
	{
		//alert('lol');
		mess = $('textarea.feedback_message').val();
		url = document.location.href;
		
		if(!mess)
			alert('Сообщение не может быть пустым');
		else
			$.get("<?php echo CHtml::normalizeUrl(array('site/ajaxFeed'))?>", { 'mess' : mess, 'url' : url }, onAjaxSuccessFeed);
	}
	
	function onAjaxSuccessFeed(data)
	{
		alert('Сообщение отправлено');
	}
</script>
</head>
<body>
    <!-- WRAPPER:begin -->
    <div id="wrapper">

            <!-- HEADER:begin -->
        <div id="header">
            <div id="header_wrapper">
                    <!-- e-tickets -->
                    <?php if($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php'): ?>
                        <?php  echo CHtml::link('<span>100%</span>&nbsp;электронные билеты','/',array('id'=>'tickets')) ?>
                    <?php else: ?>
                        <div id="analog_tickets"></div>
                    <?php endif; ?>
                <!-- end -->
                <!-- navigation -->
                <div id="nav">
                    <ul id="top_menu">
                        <li><?php echo CHtml::link('Организаторам', array('/page/organizers'), array('title'=>'Организаторам'))?></li>
                        <li><?php echo CHtml::link('Посетителям', array('/page/visitors'), array('title'=>'Посетителям'))?></li>
                        <li><?php echo CHtml::link('Все мероприятия', array('/events'), array('title'=>'Все мероприятия'))?></li>
                    </ul>
                    <ul id="bottom_menu">
                        <!--<li><?php //echo CHtml::link('О нас', array('/page/about'), array('title'=>'О нас'))?></li>
                        <li><?php //echo CHtml::link('Помощь', array('/page/hepl'), array('title'=>'Помощь'))?></li>
                        <li><?php //echo CHtml::link('Личный кабинет', array('/page/office'), array('title'=>'Личный кабинет'))?></li>
                        <li><?php //echo CHtml::link('Контакты', array('/page/contacts'), array('title'=>'Контакты'))?></li>-->
                        <?php if(!Yii::app()->user->isGuest): ?>
                            <li><?php echo CHtml::link('Мои билеты', array('/transactionLog'), array('title'=>'Мои билеты'))?></li>
                        <?php endif; ?>
                        <?php if(Yii::app()->user->isAdmin()): ?>
                            <li><?php echo CHtml::link('Пользователи', array('/user'), array('title'=>'Пользователи'))?></li>
                        <?php endif; ?>
                        <?php //if(!Yii::app()->user->isAdmin() && !Yii::app()->user->isGuest): ?>
                            <!--<li><?php //echo CHtml::link('Профиль', array('/user/view/'. Yii::app()->user->id), array('title'=>'Профиль'))?></li>-->
                        <?php //endif; ?>
                        <?php if(Yii::app()->user->isAdmin()): ?>
                            <li><?php echo CHtml::link('Отзывы', array('/feedback'), array('title'=>'Отзывы'))?></li>
                        <?php endif; ?>
                        <?php if(!Yii::app()->user->isAdmin()): ?>
                            <li><?php echo CHtml::link('Оставить отзыв', array('/feedback/create'), array('title'=>'Оставить отзыв'))?></li>
                        <?php endif; ?>
                        <?php if(Yii::app()->user->isAdmin()): ?>
                            <li><?php echo CHtml::link('Настройки', array('/control'), array('title'=>'Настройки'))?></li>
                        <?php endif; ?>
                        <!--<li><?php //echo CHtml::link('DO', array('/site/replaceFildsTickets'), array('title'=>'DO'))?></li>-->
                    </ul>
                </div>
                <!-- end -->
                <!-- authorization form -->
                <?php if (Yii::app()->user->isGuest): ?>
                    <?php $this->renderPartial('/site/' .Yii::app()->mf->siteType(). '/_login'); ?>
                <?php else:?>
                    <div id="user_logged_on">
                        <p class="top_line">
                            <span class="user_name"><?php echo Yii::app()->user->name ?></span> (<?php echo CHtml::link('Выход', array('/site/logout'),array('title'=>"Выход"))?>)
                        </p>
                        <p class="bottom_line">
                            <?php if(Yii::app()->user->isAdmin() or Yii::app()->user->isOrganizer()): ?>
                                <?php echo CHtml::link('Мероприятия', array('/events?view=organizer'),array('title'=>"Мои мероприятия")) ?> (<?php echo Yii::app()->user->countEvents; ?>)
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                            <?php endif; ?>
                            <?php echo CHtml::link('Профиль', array('/user/view/'.Yii::app()->user->id.''),array('title'=>"Редактировать профиль"))?>
                        </p>
                    </div>
                <?php endif;?>
                <!-- end -->
            </div>
        </div>
        <!-- header bottom border background -->
        <div id="header_bottom_border"></div>
        <!-- HEADER:end -->

        <?php echo $content; ?>

        <!-- FOOTER:begin -->
        <div id="footer">
                <div id="footer_wrapper_repeat_bg">
                    <div id="footer_wrapper">
                        <table>
                            <tr>
                              <!-- first column -->
                                <td id="first_column">
                                    <div class="title_footer"><?php echo CHtml::link('Организаторам', array('/page/organizers'), array('title'=>'Организаторам'))?></div>
                                    <div class="first_list">
                                        <ul>
                                            <li><?php echo CHtml::link('Регистрация нового события', array('/page/organizers#newevent'), array('title'=>'Регистрация нового события'))?></li>
                                            <li><?php echo CHtml::link('Маркетинг мероприятий', array('/page/organizers#marketing'), array('title'=>'Маркетинг мероприятий'))?></li>
                                            <li><?php echo CHtml::link('Продвижение событий онлайн', array('/page/organizers#onlinepromotion'), array('title'=>'Продвижение событий онлайн'))?></li>
                                            <li><?php echo CHtml::link('Продажа билетов', array('/page/organizers#tickets'), array('title'=>'Продажа билетов'))?></li>
                                            <li><?php echo CHtml::link('Отчеты', array('/page/organizers#reports'), array('title'=>'Отчеты'))?></li>
                                            <li><?php echo CHtml::link('Зачисление денег', array('/page/organizers#payments'), array('title'=>'Зачисление денег'))?></li>
                                            <li><?php echo CHtml::link('Сбор пожертвований', array('/page/organizers#donations'), array('title'=>'Сбор пожертвований'))?></li>
                                            <li><?php echo CHtml::link('Создание дизайна мероприятия', array('/page/organizers#design'), array('title'=>'Создание дизайна мероприятия'))?></li>
                                        </ul>
                                    </div>
                                </td>
                                <!-- second column -->
                                <td id="second_column">
                                    <div class="title_footer"><?php echo CHtml::link('Посетителям', array('page/visitors'), array('title'=>'Посетителям'))?></div>
                                    <div class="second_list">
                                        <ul>
                                            <li><?php echo CHtml::link('Как покупать', array('/page/visitors#howtobuy'), array('title'=>'Как покупать'))?></li>
                                            <li><?php echo CHtml::link('Как получить', array('/page/visitors#howtoget'), array('title'=>'Как получить'))?></li>
                                            <li><?php echo CHtml::link('Как восстановить, если потерял', array('/page/visitors#howtoreget'), array('title'=>'Как восстановить, если потерял'))?></li>
                                            <li><?php echo CHtml::link('FAQ', array('/page/visitors#faq'), array('title'=>'FAQ'))?></li>
                                        </ul>
                                    </div>
                                </td>
                                <!-- third column -->
                                <td id="third_column">
                                  <!-- navigation -->
                                    <ul id="bottom_menu">
                                        <li><?php echo CHtml::link('О нас', array('/page/about'), array('title'=>'О нас'))?></li>
                                        <li><?php echo CHtml::link('Помощь', array('/page/help'), array('title'=>'Помощь'))?></li>
                                        <li><?php echo CHtml::link('Личный кабинет', array('/page/office'), array('title'=>'Личный кабинет'))?></li>
                                        <li><?php echo CHtml::link('Контакты', array('/page/contacts'), array('title'=>'Контакты'))?></li>
                                        <li><?php echo CHtml::link('Мобильная версия', array('/?type=mobile'), array('title'=>'Мобильная версия'))?></li>
                                    </ul>
                                    <!-- we are in web -->
                                    <div class="we_are_in_web">
                                        <span>Ищите нас в интернете:</span>
                                        <?php echo CHtml::link('<img src="'.Yii::app()->request->baseUrl.'/images/icons/rss_icon.png" alt="" />', array('#'), array('title'=>'RSS'))?>
                                        <?php echo CHtml::link('<img src="'.Yii::app()->request->baseUrl.'/images/icons/vk_icon.png" alt="" />', array('#'), array('title'=>'ВКонтакте'))?>
                                        <?php echo CHtml::link('<img src="'.Yii::app()->request->baseUrl.'/images/icons/facebook_icon.png" alt="" />', array('#'), array('title'=>'Facebook'))?>
                                        <?php //echo CHtml::link('<img src="'.Yii::app()->request->baseUrl.'/images/icons/lj_icon.png" alt="" />', array('#'), array('title'=>'LiveJournal'))?>
                                        <?php //echo CHtml::link('<img src="'.Yii::app()->request->baseUrl.'/images/icons/flickr_icon.png" alt="" />', array('#'), array('title'=>'Flickr'))?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <!-- copyright -->
                        <div class="copyright">Сервис «Show Code» © 2011. Все права защищены.</div>
                    </div>
                </div>
        </div>
        <!-- FOOTER:end -->
    </div>
    <!-- Additional call Cufon for fuckin EI-->
    <script type="text/javascript">Cufon.now(); </script>
    <!-- Additional call Google Analitics-->
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-32225755-1']);
      _gaq.push(['_trackPageview']);

      (function() {
         var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
         ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
         var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
</body>
</html>