<?php $this->beginContent('//layouts/site/main'); ?>
        <!-- WRAPPER INNER:begin -->
        <div id="wrapper_inner" class="main_template">

            <!-- LOGOTYPE&DESCRIPTION ZONE:begin -->
            <div id="logotype_description">
                    <?php echo CHtml::link('','/') ?>
                <div class="text_logo_descr"><?php echo CHtml::encode(PageContent::model()->getContentByUniq('mainLogoText')); ?></div>
                <div id="available_in_appstore_button"><a href="http://itunes.apple.com/app/showcode/id498976996?mt=8" target="_blank" title="Приложение Showcode доступно в App Store"></a></div>
            </div>
            <!-- LOGOTYPE&DESCRIPTION ZONE:end -->

            <!-- CONTENT:begin -->
            <div id="content">
    <!-- top content block:begin -->
    <div class="top_content_block">
        <?php echo $content; ?>
    </div>
    <!-- top content block:end -->

    <div class="clear"></div>

    <!-- bottom content block:begin -->
    <div class="bottom_content_block">
        <!-- how to:begin -->
        <div class="how_to">
            <table>
                <tr>
                    <td class="how_to_title"><p><a href="#" title="">Как это работает?</a></p></td>
                    <td class="brace"><p>{</p></td>
                    <td class="how_to_text"><?php echo PageContent::model()->getContentByUniq('howItWorksText') ?></td>
                </tr>
            </table>
        </div>
        <!-- how to:end -->
        <!-- description block. tariff. calendar: begin -->
        <div class="tariff_and_calendar_wrapper">
                <!-- description -->
                <div class="descr_t_and_c">
                <?php echo PageContent::model()->getContentByUniq('mainBottomText') ?>
            </div>
            <!-- tariff -->
            <div class="tariff">
                <!-- one tariff -->
                <?php echo PageContent::model()->getContentByUniq('tariffText') ?>
                <!-- show all link -->
                <a href="#" title="" class="all_tariffs">Посмотреть все тарифы</a>
            </div>
            <!-- calendar -->
            <div class="calendar_wrapper">
                <div class="calendar">
                        <div class="title_t_and_c">Календарь</div>
                    <table>
                        <tr class="days_of_week">
                            <td>пн</td>
                            <td>вт</td>
                            <td>ср</td>
                            <td>чт</td>
                            <td>пт</td>
                            <td class="orange_text">сб</td>
                            <td class="orange_text">вс</td>
                        </tr>
                        <tr class="figures">
                            <td><a href="#" title="">1</a></td>
                            <td><a href="#" title="">2</a></td>
                            <td>3</td>
                            <td><a href="#" title="">4</a></td>
                            <td>5</td>
                            <td class="orange_text">6</td>
                            <td class="orange_text">7</td>
                        </tr>
                        <tr class="figures">
                            <td>8</td>
                            <td>9</td>
                            <td>10</td>
                            <td>11</td>
                            <td>12</td>
                            <td class="orange_text"><a href="">13</a></td>
                            <td class="orange_text">14</td>
                        </tr>
                        <tr class="figures">
                            <td>15</td>
                            <td>16</td>
                            <td>17</td>
                            <td>18</td>
                            <td>19</td>
                            <td class="orange_text">20</td>
                            <td class="orange_text">21</td>
                        </tr>
                        <tr class="figures">
                            <td>22</td>
                            <td><a href="">23</a></td>
                            <td>24</td>
                            <td>25</td>
                            <td>26</td>
                            <td class="orange_text">27</td>
                            <td class="orange_text">28</td>
                        </tr>
                        <tr class="figures">
                            <td>29</td>
                            <td><a href="">30</a></td>
                            <td>31</td>
                            <td></td>
                            <td></td>
                            <td class="orange_text"></td>
                            <td class="orange_text"></td>
                        </tr>
                    </table>
                    <a href="#" title="" class="archive_events">Архив событий</a>
                </div>
            </div>
        </div>
        <!-- description block. tariff. calendar: end -->

        <div class="clear"></div>

    </div>
    <!-- bottom content block:end -->
                </div>
            <!-- CONTENT:end -->

        </div>
        <!-- WRAPPERS INNER:end -->
<?php $this->endContent(); ?>