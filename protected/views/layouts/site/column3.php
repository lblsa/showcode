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
    <div class="edit_event_buttons">
        <?php
            if(count($this->menu) > 0){
                foreach($this->menu as $k => $menu){
                    echo CHtml::link($menu['label'],$menu['url'],isset($menu['linkOptions']) ? $menu['linkOptions'] : array());
                }
            }
        ?>
    </div>
    <div class="main_form_wrapper">
        <?php echo $content; ?>
    </div>
    </div>
    </div>
<?php $this->endContent(); ?>