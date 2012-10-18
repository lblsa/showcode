<?php echo CHtml::link(CHtml::image($data->logo,$data->title,array('width'=>'173','heigth'=>'123')), array('/events/view/'.$data->id),array('class'=>'small_img')) ?>
<div class="one_event_info_wrapper">
    <h2><!--<span>Мероприятие:</span>--><?php echo CHtml::link($data->title, array('/events/view/'.$data->id)) ?></h2>
    <?php if(!$data->uniqium->infinity_time): ?>
        <div class="info_about_event_preview date"><?php echo CHtml::encode($data->getEventDate($data->id)); ?></div>
        <div class="info_about_event_preview time"><?php echo CHtml::encode($data->getEventTime($data->id)); ?></div>
    <?php else: ?>
        <?php if($data->uniqium->time_work): ?>
            <div class="info_about_event_preview time"><?php echo $data->uniqium->time_work; ?></div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="info_about_event_preview map"><?php echo $data->address; ?></div>
    <?php if(!$data->uniqium): ?>
        <div class="autor_event_preview"><span>Автор:</span> <?php echo CHtml::encode(Yii::app()->user->getAuthorName($data->author)); ?></div>
    <?php endif; ?>
</div>
<?php echo CHtml::link(CHtml::encode('Перейти'), array('/events/view/'.$data->id),array('class'=>'click_on_link')) ?>
<?/*<a href="<?php echo CHtml::normalizeUrl(array('/events/view/'.$data->id));?>">1</a>*/?>