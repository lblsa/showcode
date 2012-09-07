<?php
$this->pageTitle=Yii::app()->name . ' - О нас';

?>
<div class="main_form_wrapper">
    <?php echo PageContent::model()->getContentByUniq('pageAbout') ?>
</div>