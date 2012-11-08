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
		
		<?php /*$totaLbuyT =0; ?>
		<?php $totaLPricebuyT = 0; ?>
		<?php $totaLusedT = 0; ?>
		<?php $totaLPriceusedT = 0; ?>
		<table class="statistics">
			<tr class="title_table">
				<td>Дата</td>
				<!--<td style='width: 300px;'>Мероприятие</td>-->
				<td>Билет</td>
				<td>Куплено</td>
				<td>Использовано</td>
			</tr>
			<?php foreach ($users as $u=>$user): ?>
				<?php $event_c = 'id="'.$event_id.'"'; ?>
				<?php foreach ($user->events(array('condition'=>$event_c)) as $e=>$ev): ?>
				
					<?php foreach ($ev->tickets as $е=>$tick): ?>
						<?php foreach ($daysPeriod as $d=>$dayValue): ?>
							<tr>
								<td>
									<?php
										switch ($model->period) {
											case 'days':
												echo $dayValue['day'].'.'.$dayValue['mounth'].'.'.$dayValue['year'];
												break;
											case 'weeks':
												echo $dayValue['week'].'.'.$dayValue['year'];
												break;
											case 'mounths':
												echo $dayValue['mounth'].'.'.$dayValue['year'];
												break;
										}
									?>
								</td>
								<!--<td>
									<?php //echo $ev->title; ?>
									<br/>
									<span style="font-size: 10px;">Организатор: <?php //echo $user->name; ?></span>
								</td>-->
								<td style="text-align: center;">
									 <strong><?php echo $tick->price; ?>&nbsp;руб.</strong>
									 <br/>
									 <span style="font-size: 10px;"><?php echo Tickets::$type_ticket[$tick->type]; ?></span>
								</td>
								<?php $bookedT = 0;    $buyT = 0;    $usedT = 0;    $expiredT = 0; ?>
								<?php foreach($model->searchStatistics($ev, $dayValue, $user, $model->period, $tick)->getData() as $t=>$ticket1): ?>
									<?php
										switch ($ticket1->status) {
											/*case 0:
												$bookedT = intval($ticket1->quantity);
												break;*/
											/*case 1:
												$buyT = intval($ticket1['quantity']) + $buyT;
												break;
											case 2:
												$expiredT = intval($ticket1['quantity']);
												break;
											case 3:
												$usedT = intval($ticket1['quantity']);
												$buyT = $usedT + $buyT;
												break;
										}
									?>
					<?php endforeach; ?>
								<td style="text-align: center;"><?php echo $buyT; ?>&nbsp;/&nbsp;<span><?php echo intval($tick->price) * $buyT; ?>&nbsp;руб.</span></td>
								<td style="text-align: center;"><?php echo $usedT; ?>&nbsp;/&nbsp;<span><?php echo intval($tick->price) * $usedT; ?>&nbsp;руб.</span></td>

								<?php $totaLbuyT += $buyT; ?>
								<?php $totaLPricebuyT += intval($tick->price) * $buyT; ?>
								<?php $totaLusedT += $usedT; ?>
								<?php $totaLPriceusedT += intval($tick->price) * $usedT; ?>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endforeach; ?>
			<tr>
				<td colspan="2">Итоги</td>
				<td style="text-align: center;"><?php echo $totaLbuyT; ?>&nbsp;/&nbsp;<span><?php echo $totaLPricebuyT; ?>&nbsp;руб.</span></td>
				<td style="text-align: center;"><?php echo $totaLusedT; ?>&nbsp;/&nbsp;<span><?php echo $totaLPriceusedT; ?>&nbsp;руб.</span></td>
			</tr>
		</table>*/?>
	</div>
</div>