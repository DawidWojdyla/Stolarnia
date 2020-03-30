<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Ostatnio cięte</div>
	<div class="table-responsive" style="border: none;"> 
		<table class='table orderListTable'>
			<tr>
				<th class="text-center">termin</th><th class="text-center">nr dokumentu</th><th class="text-center">klient</th>
			</tr>
			<?PHP foreach($orderList as $order):?>
			<tr class="btn btn-default btn-block orderLink" style="display: table-row;" id='<?=$order->orderId?>' onclick="carryOutTheOrder('<?=$order->orderId?>');">
				<td><?=$order -> completionDate?></td>
				<td id="document<?=$order->orderId?>"><?PHP if ($order->document_number): ?><?=$order->document_number?><?PHP else: ?>Brak<?PHP endif; ?></td>
				<td id="name<?=$order->orderId?>">
					<?PHP if($order->customer_id != 1): ?>
					<?=$order->customerName?> <?=$order->customerSurname?><div id="phone<?=$order->orderId?>" style="display: none;"><?=$order->phone?></div>
					<?PHP else: ?>
					<?=$order->customerTempName?> <div id="phone<?=$order->orderId?>" style="display: none;"><?=$order->customerTempPhone?></div>
					<?PHP endif;?>
				</td>
				<td id="comment<?=$order->orderId?>" style="display: none;"><?=$order -> orderComment?></td>
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

