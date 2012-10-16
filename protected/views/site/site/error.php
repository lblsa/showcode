<?php
    $this->pageTitle=Yii::app()->name . ' - Error '.$error['code'];
?>

<div class="main_form_wrapper list_buy_events">
    
    <h1>Ошибка <?php echo $error['code']; ?></h1>

    <div class="error">
        <p><?php  echo nl2br(CHtml::encode($error['message'])); ?></p>
        <p>При обработке веб-сервером вашего запроса произошла указанная выше ошибка.</p>
        <p>Если вы считаете, что это ошибка настройки сервера, напишите  <?php echo CHtml::link('жалобу администратору', '/feedback/create') ?>.</p>
        <p>Спасибо.</p>
    </div>
    <div><?php echo CHtml::link('Вернуться на главную страницу', '/') ?></div>
</div>