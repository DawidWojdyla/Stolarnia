<?php if(!isset($this)) die(); ?>
<h3 class="text-center">Lista zleceń</h3>
<table class='table edgeBandingOrderListTable'>
	<tr>
		<th>klient</th><th>nr dokumentu</th>
	</tr>
<?PHP foreach($orderList as $order):?>
	<tr class="orderLink" id='<?=$order->orderId?>' onclick="carryOutTheOrder('<?=$order->orderId?>');">
		<td id="name<?=$order->orderId?>">
			<?PHP if($order->customer_id != 1): ?>
			<?=$order->customerName?> <?=$order->customerSurname?><div id="phone<?=$order->orderId?>" style="display: none;"><?=$order->phone?></div>
			<?PHP else: ?>
			<?=$order->customerTempName?> <div id="phone<?=$order->orderId?>" style="display: none;"><?=$order->customerTempPhone?></div>
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

