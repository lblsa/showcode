<?php
echo '<div id="list_tickets'.$uniqEvent->prefix_class.'">';
echo '<h2>Билеты:</h2>';

echo '<table style="width: 94%!important;" class="grid-view">';
echo '<tr class="title_table'.$uniqEvent->prefix_class.'">';
if($buy)
	echo '<td></td>';
if(!$uniqEvent->infinity_qantitty){
    if($log)
        echo '<td>Осталось</td>';
    else
        echo '<td>Количество</td>';
}
echo '<td>Цена</td>';
echo '<td>Время начала</td>';
echo '<td>Время окончания</td>';
echo '<td>Описание</td>';
echo '</tr>';
foreach($ticket as $n=>$value){
    echo '<tr>';
    if($buy){
        if($log->ticket_id == $value->ticket_id)
            $check = true;
        else
            $check = false;
        echo '<td>';
        echo CHtml::radioButton('TransactionLog[ticket_id]', $check, array('id'=>'TransactionLog_ticket_id_'.$n,'value'=>$value->ticket_id,'price'=>$value->price, 'quantity'=>$value->quantity));
        echo '</td>';
        }

        if(!$uniqEvent->infinity_qantitty)
            echo '<td>'.CHtml::encode($value->quantity).'</td>';

        if ($value->type!='free')
            echo '<td>'.CHtml::encode($value->price).'&nbsp;руб.</td>';
        else
            echo '<td>0&nbsp;руб.</td>';

        if ($value->time_begin)
            echo '<td>'.CHtml::encode($value->time_begin).'</td>';
        else
            echo '<td></td>';

        if ($value->time_end)
            echo '<td>'.CHtml::encode($value->time_end).'</td>';
        else
            echo '<td></td>';

        if ($value->description)
            echo '<td class="last_cell'.$uniqEvent->prefix_class.'">'.CHtml::encode($value->description).'</td>';
        else
            echo '<td class="last_cell'.$uniqEvent->prefix_class.'"></td>';
        echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
?>