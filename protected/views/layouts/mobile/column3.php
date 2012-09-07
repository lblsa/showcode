<?php $this->beginContent('//layouts/mobile/main'); ?>
    <div data-role="page" id="login" class="container">
            <div data-role="header">
                    <h1><?php echo $this->headering; ?></h1>
                    <a href="/" data-icon="home" data-iconpos="notext" data-direction="reverse" class="ui-btn-left jqm-home" rel="external">На главную</a>
            </div>
            <div data-role="content">
                    <div id="content">
                            <?php echo $content; ?>
                    </div><!-- content -->
            </div>
            <div data-role="footer" class="ui-bar" data-theme="c">	
                    <?php
                            if ($this->menu){
                                    //echo '<h2>Операции</h2>';
                            /*array_unshift($this->menu, array('label'=>'Операции', 'itemOptions'=>array('data-role'=>'navbar')));*/
                            echo '<div data-role="navbar" data-iconpos="top" data-theme="a">';
                            $this->widget('zii.widgets.CMenu', array(
                                    'items'=>$this->menu,
                                    'htmlOptions'=>array(),
                            ));
                            echo '</div>';
                            }
                    ?>
            </div>
    </div>
<?php $this->endContent(); ?>