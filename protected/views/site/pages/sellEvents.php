<?php
$this->pageTitle=Yii::app()->name . ' - Продажа билетов';

?>
<div class="main_form_wrapper">
    <?php echo PageContent::model()->getContentByUniq('pageSellEvents') ?>
</div>