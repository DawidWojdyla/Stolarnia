<?php if(!isset($this)) die(); ?>
<h3 class="text-center">Ostatnio oklejane</h3>
<table class='table cutFormTable edgeBandingOrderListTable'>
	<tr>
		<th class='text-center'>klient</th><th class='text-center'>nr dokumentu</th>
	</tr>
<?PHP foreach($orderList as $order):?>
	<tr class="btn btn-default btn-block orderLink" style="display: table-row;" id='<?=$order->orderId?>' onclick="carryOutTheOrder('<?=$order->orderId?>');">
		<td id="comment<?=$order->orderId?>" style="display: none;"><?=$order -> orderComment?></td>
	<?PHP if($order->customer_id != 1): ?>
		<td id="phone<?=$order->orderId?>" style="display:none;"><?=$order->phone?></td>
		<td id="name<?=$order->orderId?>"><?=$order->customerName?> <?=$order->customerSurname?>
	<?PHP else: ?>
		<td id="phone<?=$order->orderId?>" style="display:none;"><?=$order->customerTempPhone?></td>
		<td id="name<?=$order->orderId?>"><?=$order->customerTempName?>
	<?PHP endif;?>
		</td>
		<td id="document<?=$order->orderId?>"><?PHP if ($order->document_number): ?><?=$order->document_number?><?PHP else: ?>Brak<?PHP endif; ?></td>
	</tr>
<?PHP endforeach; ?>	
</table>

<div class="modal fade" id="askIfToCheckoutTheOrder" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id='askingModal'>
			</div>
		</div>
	</div>
</div>