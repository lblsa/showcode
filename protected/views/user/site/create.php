<?php $this->pageTitle=Yii::app()->name.' - Регистрация пользователя' ?>

<?php
$this->breadcrumbs=array(
	'Регистрация',
);
if (yii::app()->user->isAdmin() || yii::app()->user->isOrganizer())
	$this->menu=array(
		array('label'=>'Список пользователей', 'url'=>array('index')),
		array('label'=>'Управление пользователями', 'url'=>array('admin')),
	);
?>
<?php if ($user_created): ?>
	<p><b>Поздравляем!</b></p>
	<p><b>Вы успешно зарегистрировались в системе!</b></p>
	<p><b>Через несколько минут вам на указанный номер телефона придет сообщение с паролем, по которому вы можете зайти на сайт, используя указанные логин и пароль.</b></p>
<?php else: ?>

		<h1>Регистрация</h1>
<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array(
								'model'=>$model,
								'roles'=>$roles,
								));
endif; ?>