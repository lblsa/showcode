<?php if($message_send): ?>

	<div class="row">
		<h4> <?php echo CHtml::encode('Ваш отзыв успешно отправлен!'); ?> </h4>
	</div>

<?php else: ?>

	<div class="form">
		
		<p class="note">Поля, отмеченные <span class="required">*</span> обязательны для заполнения.</p>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'contacts-form',
		'enableAjaxValidation'=>false,
	)); ?>

		<?php echo $form->errorSummary($model); ?>

		<?php if(!isset(Yii::app()->user->email)): ?>
			<div class="row" data-role="fieldcontain">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email',array('size'=>30,'maxlength'=>30)); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
		<?php endif; ?>
			
		<div data-role="fieldcontain">
			<p><?php echo CHtml::encode($model->getAttributeLabel('type')); ?></p>
		    <div data-role="controlgroup">
		    	<?php echo $form->radioButtonList($model,'type',Contacts::$type,array('separator'=>'')); ?>
		    </div>
		    <?php echo $form->error($model,'type'); ?>
		</div>

		<div class="row" data-role="fieldcontain">
			<?php echo $form->labelEx($model,'message'); ?>
			<?php echo $form->textArea($model,'message',array('style'=>'height: 100px;', 'maxlength'=>255)); ?>
			<?php echo $form->error($model,'message'); ?>
		</div>
		
		<?php if(CCaptcha::checkRequirements()): ?>
		<div class="row" data-role="fieldcontain">
			<?php echo $form->labelEx($model,'verifyCode'); ?>
			<div>
				<?php echo CHtml::encode('Чтобы обновить картинку, щелкните по ней'); ?>
				<?php $this->widget('CCaptcha', array('clickableImage' => true,'showRefreshButton'=>false, 'imageOptions'=>array('rel'=>'external'))); ?>
				<br/>
				<?php echo $form->textField($model,'verifyCode',array('style'=>'width: 20%;')); ?>
			</div>
			<?php echo $form->error($model,'verifyCode'); ?>
		</div>
		<?php endif; ?>
		

		<div class="row buttons">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Отправить' : 'Сохранить', array('data-role'=>"button",'data-theme'=>"a", 'data-iconpos'=>"right")); ?>
		</div>

	<?php $this->endWidget(); ?>
	</div><!-- form -->
<?php endif; ?>