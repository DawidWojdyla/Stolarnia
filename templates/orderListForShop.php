<?php if(!isset($this)) die(); ?>
<?PHP if(isset($_SESSION['orderListPeriodError'])): ?>
<div class="text-center inputHeight alert-danger" style="max-width: 300px; margin: 5px auto;"><?=$_SESSION['orderListPeriodError']?></div>
<?PHP unset($_SESSION['orderListPeriodError']); endif; ?>
<div class="contentContainer">
	<div class="text-center header">Stan zleceń</div>
	<div style="padding:10px;" class="tabContainer">
		<div class="container">
			<form class="text-center" id='orderListDatesForm' method="post" action="index.php?action=showOrderList">
					<div class="col-md-1 noPadding"></div>
					<div class="col-md-2 noPadding"><label for="dateFrom">Termin realizacji od: </label></div>
					<div class="col-md-2 noPadding">
						<input class="form-control text-center pointer" type="date" id="dateFrom" name="dateFrom" <?PHP if(isset($_SESSION['dateFrom'])):?> value="<?=$_SESSION['dateFrom']?>"<?PHP endif; ?> required/>
					</div>
					<div class="col-md-1 noPadding"><label for="dateTo">do: </label></div>
					<div class="col-md-2 noPadding">
						<input class="form-control text-center pointer" type="date" id="dateTo" name="dateTo" <?PHP if(isset($_SESSION['dateTo'])):?> value="<?=$_SESSION['dateTo']?>"<?PHP endif; ?> required/>
					</div>
					<div class="col-md-1 noPadding"></div>
					<div class="col-md-2 noPadding"><div class="btn btn-default form-control" type="button" onclick="sendDatesOfOrderList();">Pokaż</div></div>
					<div class="col-md-1 noPadding"></div>
			</form>
		</div>
	</div>
	<table class='table orderListTable'>
		<tr>
			<th>nr dokumentu</th><th>klient</th><th>piła</th><th>termin</th><th>stan</th>
		</tr>
			<?PHP foreach($orderList as $order):?>
		<tr style="cursor: pointer;" id="<?=$order -> orderId?>" onclick="showOrderOptions('<?=$order -> orderId?>');" class="<?PHP switch($order->state):?><?PHP case 'niepocięte':?>notCut<?PHP break; ?><?PHP case 'pocięte':?>cut<?PHP break; ?><?PHP case 'gotowe':?>completed<?PHP break; ?><?PHP endswitch;?>">
			<td id="document<?=$order -> orderId?>"><?PHP if ($order->document_number): ?><?=$order->document_number?><?PHP else: ?>Brak<?PHP endif; ?></td>
			<td id="customerId<?=$order -> orderId?>" style="display: none;"><?=$order->customerId?></td>
			<td  id="customer<?=$order -> orderId?>"><?PHP if($order->customer_id != 1): ?><span id='customerName<?=$order -> orderId?>'><?=$order->customerName?> <?=$order->customerSurname?></span> (<span id='number<?=$order -> orderId?>'><?=$order->customerPhone?></span>)<?PHP else: ?><span id='customerName<?=$order -> orderId?>'><?=$order->customerTempName?></span> (<span id='number<?=$order -> orderId?>'><?=$order->customerTempPhone?></span>)<?PHP endif;?>
			</td>
			<td id="sawNumber<?=$order -> orderId?>"><?=$order->saw_number?></td>
			<td style="display: none;" id="completionDate<?=$order -> orderId?>"><?=$order->order_completion_date?></td>
			<td><?=$order -> completionDate?></td>
			<td style="display: none;" id="admissionDate<?=$order -> orderId?>"><?=$order -> admission_date?></td>
			<td style="display: none;" id="sellerName<?=$order -> orderId?>"><?=$order -> sellerName?></td>
			<td style="display: none;" id="sellerId<?=$order -> orderId?>"><?=$order -> sellerId?></td>
			<td id="comment<?=$order -> orderId?>" style="display: none;"><?=$order->orderComment?></td>
			<td id="state<?=$order -> orderId?>"><?=$order->state?></td>
		</tr>
			<?PHP endforeach; ?>
	</table>
</div>

<div class="modal fade" id="modal" role="dialog">
	<div class="modal-dialog">
		<div class="contentContainer">
			<div class="modal-content">
				<div class="modal-body" id='modalBody'></div>
			</div>
		</div>
	</div>
</div>