<?php
    $this->menu=array(
            array('label'=>'Вернуться', 'url'=>array('view','id'=>$id)),
    );
?>
<div class="main_form_wrapper">
    <h1>Письмо с билетами на мероприятие</h1>
    <div class="list-view">
        <div class="title_name_event_typical_page"><p>Haзвание мероприятия:</p><span><?php echo $event->title ?>.</span></div>
        <div class="form_main">
            <?php echo CHtml::beginForm(); ?>
                <div>
                    <?php echo CHtml::label('Адрес электроннной почты*', 'email') ?>
                    <?php echo CHtml::textField('email', Yii::app()->user->email, array('size'=>40,'maxlength'=>50)) ?>
                </div>
                <?php if($error): ?>                
                    <div><p style="color: red;">Поле не может быть пустым или должно содержать больше символов</p></div>
                <?php endif; ?>
                <div>
                    <p>Поле «Адрес электроннной почты» должно быть обязательно заполнено.</p>
                    <p>Если данное поле уже заполнено, вы можете оставить данный E-mail или исправить его на другой адрес электронной почты.</p>
                    <p>На данный адрес будет выслано письмо со списком билетов на данное мероприятие.</p>   
                </div>
                <div>
                    <?php echo CHtml::submitButton('Отправить',array('id'=>'submit_save_button')); ?>
                </div>            
            <?php echo CHtml::endForm(); ?>
        </div>
    </div>
</div>