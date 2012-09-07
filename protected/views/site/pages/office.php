<?php
$this->pageTitle=Yii::app()->name . ' - Личный кабинет';

?>
<div class="main_form_wrapper">
    <?php echo PageContent::model()->getContentByUniq('pageOffice') ?>
</div>