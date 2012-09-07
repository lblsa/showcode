<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<meta name="language" content="en" />

        <link rel="stylesheet" href="/js/jquery.mobile/jquery.mobile-1.0b3.min.css" />
        <script src="/js/jquery-1.6.4.js"></script>
	<script src="/js/jquery.mobile/jquery.mobile-1.0b3.min.js"></script>
	<link rel="stylesheet" href="/css/mobile/main.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
        <?php if(Yii::app()->controller->action->id=='index' && Yii::app()->controller->id=='site'):?>
		<div data-role="page">
			<div data-role="header">
				<h1><?php echo Yii::app()->name; ?></h1>
			</div>
			<div id="mainmenu" data-role="content">
                                <?php
                                    if(Yii::app()->user->isGuest){
                                        echo '<div data-role="controlgroup" data-type="horizontal">';
                                        echo CHtml::link('Авторизация', array('/site/login'),array('data-role'=>'button', 'data-icon'=>"gear", 'data-theme'=>"b", 'rel'=>'external', 'style'=>'font-size: 14px;'));
                                        echo CHtml::link('Регистрация', array('/user/create'),array('data-role'=>'button','data-icon'=>"star", 'data-theme'=>"b",'rel'=>'external','data-iconpos'=>"right", 'style'=>'font-size: 14px;'));
                                        echo '</div>';
                                    }
				?>
				<nav>
                                    <?php $this->widget('zii.widgets.CMenu',array(
                                            'items'=>array(
                                                    array('label'=>Yii::app()->name,'url'=>'/', 'itemOptions'=>array('data-icon'=>'home', 'data-theme'=>"a"), 'linkOptions'=>array('rel'=>'external')),
                                                    array('label'=>'Мероприятия',	'url'=>Yii::app()->user->isAdmin() ? array('/events/admin') : array('/events'), 'linkOptions'=>array('rel'=>'external') ),
                                                    array('label'=>'Организаторам',	'url'=>array('/events?view=organizer'),	'visible'=>Yii::app()->user->isAdmin() or Yii::app()->user->isOrganizer(), 'itemOptions'=>array('data-icon'=>'star'), 'linkOptions'=>array('rel'=>'external')),
                                                    array('label'=>'Мои билеты',	'url'=>array('/transactionLog'), 'linkOptions'=>array('rel'=>'external'), 		'visible'=>!Yii::app()->user->isGuest),
                                                    array('label'=>'Пользователи',	'url'=>array('/user'), 'linkOptions'=>array('rel'=>'external'),					'visible'=>Yii::app()->user->isAdmin()),
                                                    array('label'=>'Профиль',		'url'=>array('/user/view/'. Yii::app()->user->id), 'linkOptions'=>array('rel'=>'external'),	'visible'=>!Yii::app()->user->isAdmin() && !Yii::app()->user->isGuest),
                                                    array('label'=>'Отзывы',		'url'=>array('/contacts'), 'linkOptions'=>array('rel'=>'external'),				'visible'=>Yii::app()->user->isAdmin()),
                                                    array('label'=>'Оставить отзыв','url'=>array('/contacts/create'), 'linkOptions'=>array('rel'=>'external'),		'visible'=>!Yii::app()->user->isAdmin()),
                                                    array('label'=>'Настройки',		'url'=>array('/control'), 'linkOptions'=>array('rel'=>'external'),				'visible'=>Yii::app()->user->isAdmin(), 'itemOptions'=>array('data-icon'=>'gear'),),
                                                    array('label'=>'Восстановление пароля',	'url'=>array('site/recovery'), 'linkOptions'=>array('rel'=>'external'),			'visible'=>Yii::app()->user->isGuest),
                                                    //array('label'=>'Основная версия сайта', 'url'=>array('?type=site'), 'itemOptions'=>array('data-icon'=>'info'), 'linkOptions'=>array('rel'=>'external')),
                                                    array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest, 'itemOptions'=>array('data-icon'=>'minus'), 'linkOptions'=>array('rel'=>'external')),
                                            ),
                                            'htmlOptions'=>array('data-role'=>'listview','data-inset'=>'true','data-dividertheme'=>'f', 'data-theme'=>'c'),
                                    )); ?>
                                    <?php echo CHtml::link('Основная версия сайта', '?type=site', array('data-role'=>'button', 'data-icon'=>'info', 'rel'=>'external')) ?>

				</nav>
			</div>
		</div>


	<?php endif; ?>


	<?php echo $content; ?>
	<!-- page -->

</body>
</html>