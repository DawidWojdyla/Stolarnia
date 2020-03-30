<?php if(!isset($this)) die(); ?>
<div class="contentContainer noPadding noMargin">
	<div class="text-center header">Lista zleceń</div>
	<div class="table-responsive" style="border: none;"> 
		<table class='table orderListTable'>
			<tr>
				<th class="text-center">termin</th><th class="text-center">nr dokumentu</th><th class="text-center">klient</th>
			</tr>
		<?PHP foreach($orderList as $order):?>
			<tr class="btn btn-default btn-block orderLink" style="display: table-row;"  id='<?=$order->orderId?>' onclick="carryOutTheOrder('<?=$order->orderId?>');">
				<td><?=$order -> completionDate?></td>
				<td id="document<?=$order->orderId?>"><?PHP if ($order->document_number): ?><?=$order->document_number?><?PHP else: ?>Brak<?PHP endif; ?></td>
				<?PHP if($order->customer_id != 1): ?>
				<td id="phone<?=$order->orderId?>" style="display:none;"><?=$order->phone?></td>
				<td id="name<?=$order->orderId?>"><?=$order->customerName?> <?=$order->customerSurname?>
				<?PHP else: ?>
				<td id="phone<?=$order->orderId?>" style="display:none;"><?=$order->customerTempPhone?></td>
				<td id="name<?=$order->orderId?>"><?=$order->customerTempName?></td>
				<?PHP endif;?>
				<td id="comment<?=$order->orderId?>" style="display: none;"><?=$order -> orderComment?></td>
				<td id="sawNumber<?=$order->orderId?>" style="display: none;"><?=$order -> saw_number?></td>
			</tr>
		<?PHP endforeach; ?>	
		</table>
	</div>
</div>

<div class="modal fade" id="modal" role="dialog">
	<div class="modal-dialog">
		<div class="contentContainer">
			<div class="modal-content">
				<div class="modal-body" id='modalBody'>
				</div>
			</div>
		</div>
	</div>
</div>