<h3 class="text-center">Lista zleceń</h3>
<table class='table'>
	<tr>
		<th>klient</th><th>nr dokumentu</th>
	</tr>
<?PHP foreach($orderList as $order):?>
	<tr class="orderLink" id='<?=$order->orderId?>' onclick="carryOutTheOrder('<?=$order->orderId?>');">
		<td id="name<?=$order->orderId?>">
			<?PHP if($order->customer_id != 1): ?>
			<?=$order->customerName?> <?=$order->customerSurname?>
			<?PHP else: ?>
			<?=$order->orderComment?>
			<?PHP endif;?>
		</td>
		<td id="document<?=$order->orderId?>">
			<?PHP if ($order->document_number): ?>
			<?=$order->document_number?>
			<?PHP else: ?>
			Brak
			<?PHP endif; ?>
		</td>
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
