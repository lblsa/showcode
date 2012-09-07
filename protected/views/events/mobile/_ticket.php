<div>
    <ul data-role="listview" data-theme="c" data-inset="true">
    	<?php foreach($ticket as $n=>$value): ?>
        <li data-role="list-divider" role="heading">Билет&nbsp;<?php echo $n+1; ?></li>
        <li>
            <div class="ui-grid-a">
                <div class="ui-block-a">Количество</div>
                <div class="ui-block-b"><?php echo CHtml::encode($value->quantity) ?></div>
            </div>
        </li>
        <li>
            <div class="ui-grid-a">
                <div class="ui-block-a">Цена</div>
                <div class="ui-block-b">
                    <?php if ($model->type!='free'): ?>
                        <?php echo CHtml::encode($value->price) ?> руб.
                    <?php else: ?>
                        0 руб.
                    <?php endif; ?>
                </div>
            </div>
        </li>
        <?php if ($ticket->description): ?>
            <li>
                <div class="ui-grid-a">
                    <div class="ui-block-a">Описание</div>
                    <div class="ui-block-b"><?php echo CHtml::encode($value->description) ?></div>
                </div>
            </li>
        <?php endif; ?>
        <?php if ($ticket->time_begin): ?>
            <li>
                <div class="ui-grid-a">
                    <div class="ui-block-a">Время начала</div>
                    <div class="ui-block-b"><?php echo CHtml::encode($value->time_begin) ?></div>
                </div>
            </li>
        <?php endif; ?>
        <?php if ($ticket->time_end): ?>
            <li>
                <div class="ui-grid-a">
                    <div class="ui-block-a">Время окончания</div>
                    <div class="ui-block-b"><?php echo CHtml::encode($value->time_end) ?></div>
                </div>
            </li>
        <?php endif; ?>
    	<?php endforeach; ?>
    </ul>
</div>