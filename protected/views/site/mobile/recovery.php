<?php
$this->pageTitle=Yii::app()->name . ' - Восстановление пароля';
$this->headering=Yii::app()->name . ' - Восстановление пароля';
$this->breadcrumbs=array(
	'Восстановление пароля',
);

?>
<?php if (isset($answer)):?>
	<b> <?php echo CHtml::encode('Ваш новый пароль отправлен вам смс-сообщением на номер +'.$phone.'.'); ?> </b> <br /><br />
	<?php if ($email):?>
		<b> <?php echo CHtml::encode('Так же новый пароль будет отправлен на указанный адрес электронной почты'); ?> </b> <br /><br />
	<?php else:e;t ?>
		<b> <?php echo CHtml::encode('На адрес электронной почты копия сообщения отправлена не будет, так как вы не запонили соответствующее поле или он оказался не корректным'); ?> </b> <br /><br />
	<?php endif; ?>
<?php endif; ?>

<?php if (isset($error_phone)):?>
	<b> <?php echo CHtml::encode('Не корректный номер телефона'); ?> </b> <br /><br />
<?php endif; ?>
	
<?php if (isset($error_user)):?>
	<b> <?php echo CHtml::encode('Такой пользователь не зарегистрирован в системе'); ?> </b> <br /><br />
<?php endif; ?>
	
<?php if (!isset($answer)):?>
<h3>Восстановление пароля</h3>
<?php echo CHtml::encode('Для востановления пароля, вам нужно ввести номер телефона, под которым вы были зарегистрированы в системе.'); ?> <br />
<?php echo CHtml::encode('На данный номер будет выслано смс-сообщение с НОВЫМ ПАРОЛЕМ.'); ?> <br />
<?php echo CHtml::encode('Вы так же можете указать адрес электронной почты. На него будет выслана копия сообщения.'); ?> <br />
<?php echo CHtml::encode('Адрес электронной почты должен совпадать с адресом пользователя, зарегистрированном в системе.'); ?> <br /><br />
	
<?php echo CHtml::form(); ?>
<?php echo CHtml::encode('Мобильный телефон: *'); ?> <br />
<?php echo CHtml::encode('+7 '); ?><?php echo CHtml::textField('phone',$phone, array('style'=>'width: 85%;','maxlength'=>10)); ?> <br /><br />
<?php echo CHtml::encode('Адрес электронной почты:'); ?> <br />
<?php echo CHtml::textField('email',$email, array('size'=>40,'maxlength'=>50)); ?> <br /><br />

<div class="row buttons">
	<?php echo CHtml::submitButton('Послать',array('data-theme'=>'a')); ?>
</div>
<?php echo CHtml::endForm(); ?>
<?php endif; ?>