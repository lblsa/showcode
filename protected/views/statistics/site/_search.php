<link href="/js/theme-redmond/jquery-ui-1.8.13.custom.css" type="text/css" rel="stylesheet">
<script src="/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script src="/js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
<?php
    Yii::app()->clientScript->registerScript('search', "
        $('#TransactionLog_date_begin').datepicker();
        $('#TransactionLog_date_end').datepicker();

        $('#TransactionLog_date_begin').change(function(){
            if(this.value != ''){
                var inputValue = this.value.split('.');
                var inputDate = new Date(parseInt(inputValue[2]), inputValue[1]-1, parseInt(inputValue[0])+1);
                var otherInput = '#TransactionLog_date_end';
                $(otherInput).datepicker('option', {minDate:inputDate});
                $(otherInput).datepicker('option', {defaultDate:inputDate});
            }
        });
        $('#TransactionLog_date_begin').change();

        $('#TransactionLog_date_end').change(function(){
            if(this.value != ''){
                var inputValue = this.value.split('.');
                var inputDate = new Date(parseInt(inputValue[2]), inputValue[1]-1, parseInt(inputValue[0])+1);
                var otherInput = '#TransactionLog_date_begin';
                $(otherInput).datepicker('option', {maxDate:inputDate});
                $(otherInput).datepicker('option', {defaultDate:inputDate});
            }
        });

        $('#TransactionLog_date_end').change();
    ");
?>

<div class="form_main">

    <?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->id),
	'method'=>'post',
    )); ?>

        <?php if(!Yii::app()->user->isAdmin()): ?>
            <?php echo $form->hiddenField($model,'user_id'); ?>
            <?php echo $form->hiddenField($model,'event_id'); ?>
        <?php endif; ?>

        <table class="filter-statistics-grid">
            <tr class="title_table">
                <td colspan="2">Даты</td>
                <td>Группировка</td>
                <!--<?php //if(Yii::app()->user->isAdmin()): ?>
                    <td>Пользователь</td>
                    <td>Мероприятие</td>
                <?php //endif; ?>-->
            </tr>
            <tr>
                <td>
                    <?php echo $form->textField($model,'date_begin',array('size'=>9)); ?>
                </td>
                <td>
                    <?php echo $form->textField($model,'date_end',array('size'=>9)); ?>
                </td>
                <td>
                    <?php echo $form->dropDownList($model,'period',$sortDate); ?>
                </td>
                <!--
                <?php //if(Yii::app()->user->isAdmin()): ?>
                    <td>
                        <?php //echo $form->dropDownList($model,'user_id',CHtml::listData($users, 'user_id', 'name'), array('empty'=>'')); ?>
                    </td>
                    <td>
                        <?php //echo $form->dropDownList($model,'event_id',CHtml::listData($events, 'id', 'title'), array('empty'=>'')); ?>
                    </td>
                <?php //endif; ?>
                -->
            </tr>
        </table>
        <br/>
        <div>
            <?php echo $form->hiddenField($model,'user_id'); ?>
            <?php echo $form->hiddenField($model,'event_id'); ?>
            <?php echo CHtml::submitButton('Применить',array('id'=>'submit_save_button')); ?>
        </div>
    <?php $this->endWidget(); ?>

</div><!-- search-form -->