<?php
//$this->pageTitle=Yii::app()->name . ' - Авторизация';
/*$this->breadcrumbs=array(
	'Login',
);*/
/*Yii::app()->clientScript->registerScriptFile('https://userapi.com/js/api/openapi.js?34', CClientScript::POS_HEAD);
$id_api_vk = intval(Yii::app()->params["vk_id"]);
Yii::app()->clientScript->registerScript('vkontakte_auth','
	VK.init({apiId:'. $id_api_vk .'});
	VK.Widgets.Auth("vk_auth", {width: "200px", authUrl: "/site/login"});
	
	$("#b2").click(function(){
		wind = window.open("/site/login", "mywin", "width=600,height=400,top=0");
	});
	
');*/
?>
<!-- 
<div id="fb-root"></div>
<script src="http://connect.facebook.net/ru_RU/all.js"></script>
<script>
	FB.init({ 
		appId:'281547825204430', cookie:true,
		status:true, xfbml:true 
		});
</script>
<fb:login-button perms="email,user_checkins">Login with Facebook</fb:login-button>
-->

<!-- 
     <fb:registration
            fields="[{'name':'name'}, {'name':'email'}]" redirect-uri="http://yii.sctb.ru/site/login">
    </fb:registration>
-->
<!-- 
<table>
	<tr>
		<td> <div id="vk_auth"></div> </td>
		<td> <a id="b1" href="https://www.facebook.com/dialog/oauth?client_id=<?php //echo Yii::app()->params['face_id']; ?>&redirect_uri=http://<?php //echo $_SERVER['HTTP_HOST']; ?>/site/login&scope=offline_access,email&display=page"><img src="/images/facebook.png" title="Войти через Facebook" alt="Войти через Facebook" /></a> </td>
	
	https://www.facebook.com/dialog/oauth?response_type=token&display=page&client_id=281547825204430&redirect_uri=http://yii.my/site/login&scope=rsvp_event,offline_access,email
	return http://yii.my/site/login#access_token=AAAEAEPYbzM4BAFDupX9TB4gWRfOHL7lsgnSChVH1ghC7kXCuQZB6QQHS9ZBRID5aAug56VZCKIJoCQJTZAATZAqZACoJG1uUMiDRsCqxGPqgZDZD&expires_in=0
	
	</tr>
</table>


<br />-->
<h1>Авторизация</h1>
<!--
<?php //if ($_GET['ticket']): ?>
    <p>Вы забронировали билет. Чтобы просмотреть данный билет и оплатить его, нужно авторизоваться. Если вы правильно ввели номер телефона, то вам по СМС придет сообщение, содержащее пароль, для авторизации на данном сервисе. Логином будет служить номер телефона. После авторизации идите в пункт меню Мои билеты, найдите свои забронированный билет и переходите к оплате.</p>    
<?php //endif; ?>
-->
<div><p>Пожалуйста, авторизуйтесь, используя форму ниже:</p></div>

<div class="form_main">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'enableClientValidation'=>true,
            'clientOptions'=>array(
                    'validateOnSubmit'=>true,
            ),
    )); ?>
        <table>
            <tr>
                <td><?php echo $form->labelEx($model,'phone'); ?></td>
                <td>+7 <?php echo $form->textField($model,'phone',array('size'=>22, 'maxlength'=>10)); ?>
                <?php echo $form->error($model,'phone'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model,'password'); ?></td>
                <td><?php echo $form->passwordField($model,'password',array('size'=>25)); ?>
                <?php echo $form->error($model,'password'); ?></td>
            </tr>

            <tr>
                <td><?php echo CHtml::submitButton('Войти',array('id'=>'submit_save_button')); ?></td>
                <td><?php echo CHtml::link('Забыли пароль?', '/site/recovery') ?></td>
            </tr>
        </table>
    <?php $this->endWidget(); ?>
</div><!-- form -->