<?php if(!isset($this)) die(); ?>
<table class='table'>
	<tr>
		<th>nr dokumentu</th><th>klient</th><th>data</th><th>termin</th><th>sprzedawca</th><th>piła</th>
	</tr>
	
<?PHP foreach($orders as $order): ?>
	<tr class="pointer" id="<?=$order -> orderId?>" onclick="showOrderOptions('<?=$order -> orderId?>');">
		<td id="document<?=$order -> orderId?>"><?PHP if ($order->document_number): ?><?=$order->document_number?><?PHP else: ?>Brak<?PHP endif; ?></td>
		<td id="customer<?=$order -> orderId?>"><?PHP if($order -> customerId != 1): ?><?=$order->customerName?> <?=$order->customerSurname?><div style="display: none;" id="number<?=$order -> orderId?>"><?=$order -> phone?></div><?PHP else: ?><?=$order->tempCustomerName?><div style="display: none;" id="number<?=$order -> orderId?>"><?=$order->tempPhone?></div><?PHP endif;?></td>
		<td>
			<?=$order -> admission_date?>
		</td>
		<td>
			<?=$order -> order_completion_date?>
		</td>
		<td>
			<?=$order -> sellerName?>
		</td>
		<td>
			<?=$order->saw_number?>
		</td>
	</tr>
		<?PHP endforeach; ?>
</table>

<div class="modal fade" id="modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id='modalBody'>
			</div>
		</div>
	</div>
</div>