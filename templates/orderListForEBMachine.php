<?php if(!isset($this)) die(); ?>
<h3 class="text-center">Lista zleceń</h3>

<?PHP $orderCompletionDate = '0'; ?>
<table>
<?PHP foreach($orderList as $order):?>
<?PHP if($orderCompletionDate != $order -> order_completion_date):
				$orderCompletionDate = $order -> order_completion_date; ?>
</table>
	<div class='btn btn-default btn-block orderListButton' onclick="showOrders('<?=$orderCompletionDate?>');"><?=$order->order_completion_date?></div>
	<table id="<?=$orderCompletionDate?>" style="display: none;" class="table edgeBandingOrderListTable">
<?PHP endif; ?>
	<tr class="orderLink <?=$orderCompletionDate?>" id='<?=$order->orderId?>' onclick="carryOutTheOrder('<?=$order->orderId?>');">
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
		<td><?=$order->saw_number?></td>
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