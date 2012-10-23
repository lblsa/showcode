<?php
$this->pageTitle = 'Авторизация - '.Yii::app()->name;
$this->headering = 'Авторизация - '.Yii::app()->name;
/*$this->breadcrumbs=array(
	'Login',
);*/

/*
Yii::app()->clientScript->registerScriptFile('https://userapi.com/js/api/openapi.js?34', CClientScript::POS_HEAD);*/
/*Yii::app()->clientScript->registerScript('loginPhone','
	$("#loginPhone").change(function(){
            console.log(this.value);
        });
');
*/
?>

<!--
<div id="vk_auth"></div><br />
<a id="b1" href="https://www.facebook.com/dialog/oauth?client_id=<?php //echo Yii::app()->params['face_id']; ?>&redirect_uri=http://<?php //echo $_SERVER['HTTP_HOST']; ?>/site/login&scope=offline_access,email&display=page"><img src="/images/facebook.png" title="Войти через Facebook" alt="Войти через Facebook" /></a>
-->
	<h3>Авторизация</h3>
	<p>Пожалуйста, авторизуйтесь, используя форму ниже:</p>

	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
                            'id'=>'login-form',
							'enableAjaxValidation'=>false,
		)); ?>

			<div class="row" data-role="fieldcontain">
				 <?php echo $form->labelEx($model,'phone'); ?>
				<span style="position: relative;top:0px;left:0px;font-size: 16px;">+7&nbsp;</span><?php echo $form->textField($model,'phone',array('style'=>'padding-left: 30px;width: 87%;')); ?>
				<?php echo $form->error($model,'phone'); ?>
			</div>
		    <br/>

			<div class="row" data-role="fieldcontain">
				<?php echo $form->labelEx($model,'password'); ?>
				<?php echo $form->passwordField($model,'password'); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
			<br/>



			<div class="row buttons">
				<?php echo CHtml::submitButton('Войти', array('data-role'=>"button",'data-theme'=>"a", 'data-iconpos'=>"right")); ?>
			</div>

		<?php $this->endWidget(); ?>

	</div><!-- form -->	