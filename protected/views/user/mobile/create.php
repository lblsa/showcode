<?php
	$this->headering='Регистрация';
if (yii::app()->user->isAdmin() || yii::app()->user->isOrganizer())
	$this->menu=array(
		array('label'=>'Список пользователей', 'url'=>array('index')),
		//array('label'=>'Управление пользователями', 'url'=>array('admin')),
	);
?>
<?php if ($user_created): ?>

	<b>Поздравляем! Вы успешно зарегистрировались в системе! <br /> Теперь вы можете зайти на сайт использую свой логин и пароль.</b>

<?php else: ?>

<?php echo $this->renderPartial(Yii::app()->mf->siteType(). '/_form', array(
							'model'=>$model,
							'roles'=>$roles
							));
endif; ?>