<td id="scanner-description"><?php echo CHtml::link(CHtml::encode($data->DESCRIPTION),array('view','id'=>$data->SCANNERS_ID)); ?></td>

<td id="scanner-uniq"><?php echo CHtml::encode($data->UNIQ); ?></td>

<?php if($data->ACCESS): ?>
    <td id="scanner-status"><span class="scanner-access">&nbsp;</span></td>
    <td>Сканер выслал сигнал <?php echo Events::normalViewDate($data->DATE_LAST_ACCESS); ?></td>
<?php else: ?>
    <td id="scanner-status"><span class="scanner-not-access">&nbsp;</span></td>
    <td>Сканер не отвечает.
        <?php if($data->DATE_LAST_ACCESS): ?>
            &nbsp;Последний сигнал <?php echo Events::normalViewDate($data->DATE_LAST_ACCESS); ?>
        <?php endif; ?>
    </td>
<?php endif; ?>