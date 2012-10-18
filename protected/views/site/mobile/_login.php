<div id="authorization_form">
    <?php echo CHtml::beginForm(array('site/login'),'POST', array('id'=>'login-form')); ?>
        <div id="login">
            <p>
                <?php //echo CHtml::label('Номер телефона','LoginForm_phone'); ?>
                +7<?php echo CHtml::textField('LoginForm[phone]','',array('size'=>22, 'maxlength'=>10)); ?>
                <?php echo CHtml::submitButton('Войти', array('id'=>"submit")); ?>
                <?php //echo CHtml::error(LoginForm,'phone'); ?>
            </p>
        </div>

        <div id="pass">
            <p>
                <?php //echo CHtml::label('Пароль','LoginForm_password'); ?>
                <?php echo CHtml::passwordField('LoginForm[password]','',array('size'=>25)); ?>
                <?php echo CHtml::link('Забыли пароль?', '/site/recovery', array('title'=>"Восстановление пароля", 'id'=>"repear_pass")) ?>                
                <?php //echo CHtml::error($model,'password'); ?>
            </p>
        </div>        
    <?php echo CHtml::endForm(); ?>
</div>