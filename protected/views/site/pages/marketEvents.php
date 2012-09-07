<?php
$this->pageTitle=Yii::app()->name . ' - Маркетинг мероприятий';

?>
<div class="main_form_wrapper">
    <?php echo PageContent::model()->getContentByUniq('pageMarketEvents') ?>
</div>