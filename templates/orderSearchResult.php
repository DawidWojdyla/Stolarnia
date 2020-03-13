<?php if(!isset($this)) die(); ?>
<div class='contentContainer'>
	<table style="margin-top: 0px;" class='table orderListTable'>
		<tr>
			<th>nr dokumentu</th><th>klient</th><th>data</th><th>termin</th><th>sprzedawca</th><th>piła</th>
		</tr>
		
	<?PHP foreach($orders as $order): ?>
		<tr class="pointer" id="<?=$order -> orderId?>" onclick="showOrderOptions('<?=$order -> orderId?>');">
			<td id="document<?=$order -> orderId?>"><?PHP if ($order->document_number): ?><?=$order->document_number?><?PHP else: ?>Brak<?PHP endif; ?></td>
			<td><?PHP if($order -> customerId != 1): ?><div id="customer<?=$order -> orderId?>"><?=$order->customerName?> <?=$order->customerSurname?></div><div style="display: none;" id="number<?=$order -> orderId?>"><?=$order -> phone?></div><?PHP else: ?><div id="customer<?=$order -> orderId?>"><?=$order->tempCustomerName?></div><div style="display: none;" id="number<?=$order -> orderId?>"><?=$order->tempPhone?></div><?PHP endif;?></td>
			<td id="admissionDate<?=$order -> orderId?>"><?=$order -> admission_date?></td>
			<td id="completionDate<?=$order -> orderId?>"><?=$order -> order_completion_date?></td>
			<td id="sellerName<?=$order -> orderId?>"><?=$order -> sellerName?></td>
			<td id="sawNumber<?=$order -> orderId?>"><?=$order->saw_number?></td>
			<td id="customerId<?=$order -> orderId?>" style="display: none;"><?=$order->customerId?></td>
			<td id="comment<?=$order -> orderId?>" style="display: none;"><?=$order->orderComment?></td>
			<td id="sellerId<?=$order -> orderId?>" style="display: none;"><?=$order->sellerId?></td>
			<td id="minCuttingDate<?=$order -> orderId?>" style="display: none;"><?=$order -> minCuttingDate?></td>
		</tr>
			<?PHP endforeach; ?>
	</table>
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