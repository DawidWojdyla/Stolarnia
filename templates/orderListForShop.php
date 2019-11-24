<?PHP if(isset($_SESSION['orderListPeriodError'])): ?>
<div class="text-center inputHeight alert-danger" style="max-width: 300px; margin: 5px auto;"><?=$_SESSION['orderListPeriodError']?></div>
<?PHP unset($_SESSION['orderListPeriodError']); endif; ?>
<form class="text-center" id='orderListDatesForm' method="post" action="index.php?action=showOrderListForShop">
	Termin realizacji od <input class="inputHeight" type="date" id="dateFrom" name="dateFrom" <?PHP if(isset($_SESSION['dateFrom'])):?> value="<?=$_SESSION['dateFrom']?>"<?PHP endif; ?> required/> do <input class="inputHeight" type="date" id="dateTo" name="dateTo" <?PHP if(isset($_SESSION['dateTo'])):?> value="<?=$_SESSION['dateTo']?>"<?PHP endif; ?> required/>
	<div class="btn btn-default btn-block inputHeight" style=" display: inline; max-width: 250px;" type="button" onclick="sendDatesOfOrderList();">Pokaż</div>
</form>
<h3 class="text-center">Lista zleceń</h3>
<table class='table'>
	<tr>
		<th>nr dokumentu</th><th>klient</th><th>piła</th><th>data realizacji</th><th>stan</th>
	</tr>
<?PHP foreach($orders as $order):?>
	<tr>
		<td>
			<?PHP if ($order->document_number): ?>
			<?=$order->document_number?>
			<?PHP else: ?>
			Brak
			<?PHP endif; ?>
		</td>
		<td>
			<?PHP if($order->customer_id != 1): ?>
			<?=$order->customerName?> <?=$order->customerSurname?> (<?=$order->customerPhone?>)
			<?PHP else: ?>
			<?=$order->orderComment?>
			<?PHP endif;?>
		</td>
		<td>
			<?=$order->saw_number?>
		</td>
		<td>
			<?=$order->order_completion_date?>
		</td>
		<td>
			<?=$order->state?>
		</td>
	</tr>
<?PHP endforeach; ?>	
</table>