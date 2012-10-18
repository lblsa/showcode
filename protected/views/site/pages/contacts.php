<?php
$this->pageTitle=Yii::app()->name . ' - Контакты';

?>
<div class="main_form_wrapper">
    <?php echo PageContent::model()->getContentByUniq('pageContacts') ?>
</div>