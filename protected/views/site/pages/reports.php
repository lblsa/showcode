<?php
$this->pageTitle=Yii::app()->name . ' - Отчеты';

?>
<div class="main_form_wrapper">
    <?php echo PageContent::model()->getContentByUniq('pageReports') ?>
</div>