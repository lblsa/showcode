<style>
	.form_main label
	{
		display: inline;
	}
	.form_main table tr td
	{
		padding-bottom: 10px;
	}

</style>
<?php if (isset($vk)): ?>

<?php echo $vk; ?>

<?php else: ?>
<div><p>Пожалуйста, заполните следующие поля:</p></div>

<div class="form_main">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'enableClientValidation'=>true,
            'clientOptions'=>array(
                    'validateOnSubmit'=>true,
            ),
    )); ?>
	
        <table>
            <tr>
                <td style="width: 40%"><?php echo $form->labelEx($model,'phone'); ?></td>
                <td style="width: 60%">
					+7 <?php echo $form->textField($model,'phone',array('size'=>22, 'maxlength'=>10)); ?>
					<?php echo $form->error($model,'phone'); ?>
				</td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model,'email'); ?></td>
                <td>
					<?php echo $form->textField($model,'email',array('size'=>40,'maxlength'=>128)); ?>
					<?php echo $form->error($model,'email'); ?>
				</td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model,'role'); ?></td>
                <td>
					<?php echo $form->dropDownList($model,'role',$roles); ?>
					<?php echo $form->error($model,'role'); ?>
				</td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model,'organization'); ?></td>
                <td>
					<?php echo $form->textField($model,'organization',array('size'=>40,'maxlength'=>128)); ?>
					<?php echo $form->error($model,'organization'); ?>
				</td>
            </tr>
			<tr>
                <td><?php echo CHtml::submitButton('Продолжить',array('id'=>'submit_save_button')); ?></td>
            </tr>
        </table>
    <?php $this->endWidget(); ?>
</div><!-- form -->
<?php endif; ?>