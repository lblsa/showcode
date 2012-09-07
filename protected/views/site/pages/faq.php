<?php
$this->pageTitle=Yii::app()->name . ' - FAQ';

?>
<div class="main_form_wrapper">
    <?php echo PageContent::model()->getContentByUniq('pageFAQ') ?>
</div>