<?php if(!isset($this)) die(); ?>
<h3 class="text-center">Nowy klient</h3>
<form action="index.php?action=addNewCustomer" name="customerAddingForm" id="customerAddingForm" method="post" autocomplete="off">
	<table class="table table-condensed addingTab">
		<?PHP if (isset($_SESSION ['customerNameError'])):?>
		<tr class="danger text-center"><td colspan="2"><?=$_SESSION ['customerNameError']?></td></tr>
		<?PHP unset($_SESSION ['customerNameError']); endif; ?>
		<tr>
			<td>Imię:</td>
			<td>
				<input id='customerName' class="inputHeight" type="text" name="customerName" value="<?PHP if (isset($_SESSION['customerName'])): ?><?=$_SESSION['customerName']?><?PHP unset ($_SESSION['customerName']); endif; ?>"/>
			</td>
		<?PHP if (isset($_SESSION ['customerSurnameError'])):?>
		<tr class="danger text-center"><td colspan="2"><?=$_SESSION ['customerSurnameError']?></td></tr>
		<?PHP unset($_SESSION ['customerSurnameError']); endif; ?>
		</tr>
		<tr>
			<td>*Nazwisko (nazwa firmy):</td>
			<td>
				<input id='customerSurname' minlength="2" class="inputHeight" type="text" name="customerSurname" required <?PHP if (isset($_SESSION['customerSurname'])): ?>value="<?=$_SESSION['customerSurname']?>"<?PHP unset ($_SESSION['customerSurname']); endif; ?>/>
			</td>
		</tr>
		<tr>
			<td>*Numer tel. (9 cyfr):</td>
			<td>
				<input id='customerPhone' class="inputHeight" type="tel" name="customerPhone" pattern="[1-9]{1}[0-9]{8}" minlength="9" maxlength="9" required <?PHP if (isset($_SESSION['customerPhone'])): ?>value="<?=$_SESSION['customerPhone']?>"<?PHP unset ($_SESSION['customerPhone']); endif; ?>/>
			</td>
		</tr>
		<tr>
			<td>
				Adres:
			</td>
			<td>
				<textarea id="customerAddress" name="customerAddress" rows="3" cols="25" maxlength="250" form="customerAddingForm"><?PHP if (isset($_SESSION['customerAddress'])): ?><?=$_SESSION['customerAddress']?><?PHP unset ($_SESSION['customerAddress']);?><?PHP endif; ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<button class="btn btn-default btn-block" type="submit"><span class="glyphicon glyphicon-plus"></span> Dodaj klienta</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			* - pola obowiązkowe
			</td>
		</tr>
	</table>
</form>