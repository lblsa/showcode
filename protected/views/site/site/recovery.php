<?php
$this->pageTitle=Yii::app()->name . ' - Восстановление пароля';
$this->breadcrumbs=array(
	'Восстановление пароля',
);
?>
<h1>Восстановление пароля</h1>
<p>
    <?php if (isset($answer)):?>
        <p><b> <?php echo CHtml::encode('Ваш новый пароль отправлен вам смс-сообщением на номер +'.$phone.'.'); ?> </b></p>
    <?php endif; ?>
</p>

<p>
    <?php if (isset($error_phone)):?>
            <p class="errorMessage"><?php echo CHtml::encode('Не корректный номер телефона'); ?> </p> <br />
    <?php endif; ?>

    <?php if (isset($error_user)):?>
             <p class="errorMessage"> <?php echo CHtml::encode('Такой пользователь не зарегистрирован в системе'); ?> </p> <br />
    <?php endif; ?>
</p>

<?php if (!isset($answer)):?>
    <p>Для востановления пароля, вам нужно ввести номер телефона, под которым вы были зарегистрированы в системе.</p>
    <p>На данный номер будет выслано смс-сообщение с НОВЫМ ПАРОЛЕМ.</p>
    <br />

    <div class="form_main">

        <?php echo CHtml::beginForm('/site/recovery'); ?>
            <div>
                <?php echo CHtml::label('Мобильный телефон: *','phone'); ?>
                <?php echo CHtml::encode('+7 '); ?><?php echo CHtml::textField('phone',$phone, array('size'=>37,'maxlength'=>10)); ?> <br /><br />
            </div>

            <div>
                <?php echo CHtml::submitButton('Отправить',array('id'=>'submit_save_button')); ?>
            </div>

        <?php echo CHtml::endForm(); ?>
    </div>
<?php endif; ?>