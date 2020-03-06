<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
<div class="text-center header">Ostatnio zrealizowane</div>
<table class='table orderListTable'>
	<tr>
		<th>nr dokumentu</th><th>klient</th><th>termin realizacji</th><th>sprzedawca</th>
	</tr>
		<?PHP foreach($orderList as $order):?>
	<tr style="cursor: pointer;" id="<?=$order -> orderId?>" onclick="showOrderOptions('<?=$order -> orderId?>');">
		<td id="document<?=$order -> orderId?>"><?PHP if ($order->document_number): ?><?=$order->document_number?><?PHP else: ?>Brak<?PHP endif; ?></td>
		<td id="customerId<?=$order -> orderId?>" style="display: none;"><?=$order->customerId?></td>
		<td  id="customer<?=$order -> orderId?>"><?PHP if($order->customer_id != 1): ?><span id='customerName<?=$order -> orderId?>'><?=$order->customerName?> <?=$order->customerSurname?></span> (<span id='number<?=$order -> orderId?>'><?=$order->customerPhone?></span>)<?PHP else: ?><span id='customerName<?=$order -> orderId?>'><?=$order->customerTempName?></span> (<span id='number<?=$order -> orderId?>'><?=$order->customerTempPhone?></span>)<?PHP endif;?>
		</td>
		<td style="display: none;" id="sawNumber<?=$order -> orderId?>"><?=$order->saw_number?></td>
		<td id="completionDate<?=$order -> orderId?>"><?=$order->order_completion_date?></td>
		<td style="display: none;" id="admissionDate<?=$order -> orderId?>"><?=$order -> admission_date?></td>
		<td id="sellerName<?=$order -> orderId?>"><?=$order -> sellerName?></td>
		<td style="display: none;" id="sellerId<?=$order -> orderId?>"><?=$order -> sellerId?></td>
		<td id="comment<?=$order -> orderId?>" style="display: none;"><?=$order->orderComment?></td>
	</tr>
		<?PHP endforeach; ?>
</table>
</div>

<div class="modal fade" id="modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id='modalBody'>
			</div>
		</div>
	</div>
</div>