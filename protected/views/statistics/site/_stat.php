<meta charset="utf-8">
<div class="main_form_wrapper list_buy_events">
	<div id="list_tickets">
		<h1>
			Статистика  по мероприятию «<?php echo Events::model()->getEventTitle($event_id); ?>»
		</h1>		
		<table>
			<tr class="title_table">
				<td style="width: 34%">
					Период
				</td>
				<td style="width: 33%">
					Купленно
				</td>
				<td style="width: 33%">
					Использовано
				</td>
			</tr>
			<tr>
				<td>
					Итого на начало периода
				</td>
				<td>
					<?php echo $info['cByeStart'].'/'.$info['pByeStart'].' руб.';?>
				</td>
				<td>
					<?php echo $info['cUseStart'].'/'.$info['pUseStart'].' руб.';?>
				</td>
			</tr>
			<tr>
				<td>
					Итого на окончание периода
				</td>
				<td>
					<?php echo $info['cByeEnd'].'/'.$info['pByeEnd'].' руб.';?>
				</td>
				<td>
					<?php echo $info['cUseEnd'].'/'.$info['pUseEnd'].' руб.';?>
				</td>
			</tr>
			<tr>
				<td>
					Изменение за период
				</td>
				<td>
					<?php echo ($info['cByeStart'] - $info['cByeEnd']<0) ? $info['cByeEnd'] - $info['cByeStart'] : $info['cByeStart'] - $info['cByeEnd'];?>/<?php echo ($info['pByeStart'] - $info['pByeEnd']<0) ? $info['pByeEnd'] - $info['pByeStart'] : $info['pByeStart'] - $info['pByeEnd']; ?> руб.
				</td>
				<td>
					<?php echo ($info['cUseStart'] - $info['cUseEnd']<0) ? $info['cUseEnd'] - $info['cUseStart'] : $info['cUseStart'] - $info['cUseEnd'];?>/<?php echo ($info['pUseStart'] - $info['pUseEnd']<0) ? $info['pUseEnd'] - $info['pUseStart'] : $info['pUseStart'] - $info['pUseEnd'];?> руб.
					
				</td>
			</tr>
		</table>
	</div>
</div>