<?php if(!isset($this)) die(); ?>
<?PHP if(isset($_SESSION['orderListPeriodError'])): ?>
<div class="text-center inputHeight alert-danger" style="max-width: 300px; margin: 5px auto;"><?=$_SESSION['orderListPeriodError']?></div>
<?PHP unset($_SESSION['orderListPeriodError']); endif; ?>
<form class="text-center" id='orderListDatesForm' method="post" action="index.php?action=showOrderList">
	Termin realizacji od <input class="inputHeight" type="date" id="dateFrom" name="dateFrom" <?PHP if(isset($_SESSION['dateFrom'])):?> value="<?=$_SESSION['dateFrom']?>"<?PHP endif; ?> required/> do <input class="inputHeight" type="date" id="dateTo" name="dateTo" <?PHP if(isset($_SESSION['dateTo'])):?> value="<?=$_SESSION['dateTo']?>"<?PHP endif; ?> required/>
	<div class="btn btn-default inputHeight" style="display: inline; max-width: 250px;" type="button" onclick="sendDatesOfOrderList();">Pokaż</div>
</form>
<h3 class="text-center">Stan zleceń</h3>
<table class='table'>
	<tr>
		<th>nr dokumentu</th><th>klient</th><th>piła</th><th>termin</th><th>stan</th>
	</tr>
	<?PHP foreach($orderLists as $orderList): ?> 
		<?PHP foreach($orderList as $order):?>
	<tr id="<?=$order -> orderId?>" onclick="showOrderOptions('<?=$order -> orderId?>');" class="<?PHP switch($order->state):?><?PHP case 'niepocięte':?>danger<?PHP break; ?><?PHP case 'pocięte':?>info<?PHP break; ?><?PHP case 'gotowe':?>success<?PHP break; ?><?PHP endswitch;?>">
		<td id="document<?=$order -> orderId?>"><?PHP if ($order->document_number): ?><?=$order->document_number?><?PHP else: ?>Brak<?PHP endif; ?></td>
		<td id="customerId<?=$order -> orderId?>" style="display: none;"><?=$order->customerId?></td>
		<td  id="customerName<?=$order -> orderId?>"><?PHP if($order->customer_id != 1): ?><?=$order->customerName?> <?=$order->customerSurname?> (<?=$order->customerPhone?>)<?PHP else: ?><?=$order->customerTempName?> (<?=$order->customerTempPhone?>)<?PHP endif;?>
		</td>
		<td id="sawNumber<?=$order -> orderId?>">
			<?=$order->saw_number?>
		</td>
		<td id="completionDate<?=$order -> orderId?>">
			<?=$order->order_completion_date?>
		</td>
		<td style="display: none;" id="admissionDate<?=$order -> orderId?>"><?=$order -> admission_date?></td>
		<td style="display: none;" id="sellerName<?=$order -> orderId?>"><?=$order -> sellerName?></td>
		<td id="comment<?=$order -> orderId?>" style="display: none;"><?=$order->orderComment?></td>
		<td>
			<?=$order->state?>
		</td>
	</tr>
		<?PHP endforeach; ?>
	<?PHP endforeach; ?>
</table>