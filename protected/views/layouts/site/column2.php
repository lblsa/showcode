<?php $this->beginContent('//layouts/site/main'); ?>
<div class="logotype">
            <?php echo CHtml::link('','/') ?>
        </div>
<div class="appstore_butn_type_page">
		<a href="http://itunes.apple.com/app/showcode/id498976996?mt=8" target="_blank" title="Приложение Showcode доступно в App Store"></a>
	</div>
<div id="wrapper_inner" class="template_without_search">
    <!-- CONTENT:begin -->
        <div id="content">

    <!-- buttons (edit and my events) -->
    <?php if(count($this->menu) > 0): ?>
    <div class="edit_event_buttons" style="right: 0">
        <?php
            foreach($this->menu as $k => $menu){
                echo CHtml::link($menu['label'],$menu['url'], isset($menu['linkOptions']) ? $menu['linkOptions'] : array());
            }
        ?>
        </div>
     <?php endif; ?>

    <!-- top content block:begin -->
    <?php echo $content; ?>

    <!-- top content block:end -->

    <!-- description block. tariff. calendar: begin -->
    <div class="tariff_and_calendar_wrapper">
        <!-- tariff -->
        <div class="tariff com_events_tariff">
            <!-- one tariff -->
                <?php echo PageContent::model()->getContentByUniq('tariffText') ?>
            <!-- show all link -->
            <div class="one_tariff"><a href="#" title="" class="all_tariffs">Посмотреть все тарифы</a></div>
        </div>

        <!-- description -->
        <div class="descr_t_and_c com_events_descr_t_and_c">
           <?php echo PageContent::model()->getContentByUniq('bottomText') ?>
        </div>

    </div>
    <!-- description block. tariff. calendar: end -->
    </div>
    </div>
<?php $this->endContent(); ?>