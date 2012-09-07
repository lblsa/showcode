<?php if($message_send): ?>
	<div>
		<h1> <?php echo CHtml::encode('Ваш отзыв успешно отправлен!'); ?> </h1>
	</div>
<?php else: ?>

	<div class="form_main">
            <div><p class="note">Поля, отмеченные <span class="required">*</span> обязательны для заполнения.</p></div>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'enableAjaxValidation'=>false,
	)); ?>
		<?php //echo $form->errorSummary($model); ?>

		<?php if(!isset(Yii::app()->user->email)): ?>
			<div>
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email',array('size'=>30,'maxlength'=>30, 'id'=>"title_input")); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
		<?php endif; ?>

		<div>
			<?php echo $form->labelEx($model,'type'); ?>
			<?php echo $form->dropDownList($model,'type',Contacts::$type,array('id'=>"type_ticket")); ?>
			<?php echo $form->error($model,'type'); ?>
		</div>

		<div>
			<?php echo $form->labelEx($model,'message'); ?>
			<?php echo $form->textArea($model,'message',array('rows'=>6, 'cols'=>50, 'id'=>"description_textarea")); ?>
			<?php echo $form->error($model,'message'); ?>
		</div>

		<?php if(CCaptcha::checkRequirements()): ?>
		<div style="margin-left: 105px;">
			<div style="margin: 0pt;">
                            <div style="float: left; margin-right: 15px;margin-bottom: 0pt;"><?php $this->widget('CCaptcha', array('clickableImage' => true,'showRefreshButton'=>false, 'imageOptions'=>array('rel'=>'external'))); ?></div>
                            <div style="margin: 0pt; line-height: 14px;">
                                <?php echo CHtml::encode('Введите код с картинки'); ?><br/>
                                <?php echo $form->textField($model,'verifyCode',array('size'=>14,'style'=>'margin-top: 9px;')); ?>
                            </div>
                            <div style="clear: both; margin: 0pt;"></div>
			</div>
                        <?php echo CHtml::encode('Чтобы обновить картинку, щелкните по ней'); ?>
			<?php echo $form->error($model,'verifyCode'); ?>
		</div>
		<?php endif; ?>


		<div style="margin-left: 105px;">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Отправить' : 'Сохранить',array('id'=>'submit_save_button')); ?>
		</div>

	<?php $this->endWidget(); ?>
	</div><!-- form -->
<?php endif; ?>