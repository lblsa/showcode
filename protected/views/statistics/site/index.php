<?php $this->pageTitle=Yii::app()->name.' - Управление мероприятиями' ?>

<div class="main_form_wrapper list_buy_events">

    <h1>
        Статистика
        <?php if($tickets->event_id): ?>
             по мероприятию «<?php echo Events::model()->getEventTitle($tickets->event_id); ?>»
        <?php endif; ?>
    </h1>

    <div id="list_tickets">
        <div class="search-form">
            <?php $this->renderPartial(Yii::app()->mf->siteType(). '/_search',array(
                    'model'=>$tickets,
                    'sortDate'=>$sortDate,
                    'users'=>$usersDropList,
                    'events'=>$eventsDropList,
            )); ?>
        </div>

        <br/>

        <div>
            <?php if($tickets->user_id): ?>
                <?php $this->renderPartial(Yii::app()->mf->siteType(). '/_table',array(
                        'model'=>$tickets,
                        'sortDate'=>$sortDate,
                        'users'=>$users,
                        'events'=>$events,
                        'daysPeriod'=>$daysPeriod,
                )); ?>
            <?php endif; ?>
        </div>
    </div>
</div>