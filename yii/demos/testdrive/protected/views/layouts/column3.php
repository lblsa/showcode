<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="span-19">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-5 last">
		<div id="sidebar">
			<?php if(!Yii::app()->user->isGuest) $this->widget('UserMenuCatalog'); ?>
		</div>
	</div>
</div>
<?php $this->endContent(); ?>