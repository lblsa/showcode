<?php $this->beginContent('//layouts/mobile/main'); ?>
    <div data-role="page">
            <div data-role="header">
                    <h1><?php echo $this->headering ?></h1>
                    <a href="/" data-icon="home" data-iconpos="notext" data-direction="reverse" class="ui-btn-right jqm-home" rel="external">На главную</a>
            </div>
            <div data-role="content">
                    <nav>
                    <?php
                            if ($this->menu)
                                    array_unshift($this->menu, array('label'=>'Операции', 'itemOptions'=>array('data-role'=>'list-divider')));
                            $this->widget('zii.widgets.CMenu', array(
                                    'items'=>$this->menu,
                                    'htmlOptions'=>array('data-role'=>'listview','data-inset'=>'true','data-divider-theme'=>'c'),
                            ));
                    ?>
                    </nav>
            <br/>
                    <div id="content">
                            <?php echo $content; ?>
                    </div><!-- content -->
            </div>
    </div>
<?php $this->endContent(); ?>