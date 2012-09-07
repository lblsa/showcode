<?php
$this->pageTitle=Yii::app()->name . ' - Помощь';

?>
<div class="main_form_wrapper">
    <?php echo PageContent::model()->getContentByUniq('pageHelp') ?>
</div>