<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Nowy klient</div>
	<form action="index.php?action=addNewCustomer" name="customerAddingForm" id="customerAddingForm" method="post" autocomplete="off">
		<div class="tabContainer">
			<table class="table table-condensed addingTab">
				<?PHP if (isset($_SESSION ['customerNameError'])):?>
				<tr class="danger text-center"><td colspan="2"><?=$_SESSION ['customerNameError']?></td></tr>
				<?PHP unset($_SESSION ['customerNameError']); endif; ?>
				<tr>
					<td><label>Imię:</label></td>
					<td>
						<input id='customerName' class="form-control" type="text" name="customerName" value="<?PHP if (isset($_SESSION['customerName'])): ?><?=$_SESSION['customerName']?><?PHP unset ($_SESSION['customerName']); endif; ?>"/>
					</td>
				<?PHP if (isset($_SESSION ['customerSurnameError'])):?>
				<tr class="danger text-center"><td colspan="2"><?=$_SESSION ['customerSurnameError']?></td></tr>
				<?PHP unset($_SESSION ['customerSurnameError']); endif; ?>
				</tr>
				<tr>
					<td><label>*Nazwisko (nazwa firmy):</label></td>
					<td>
						<input id='customerSurname' minlength="2" class="form-control" type="text" name="customerSurname" required <?PHP if (isset($_SESSION['customerSurname'])): ?>value="<?=$_SESSION['customerSurname']?>"<?PHP unset ($_SESSION['customerSurname']); endif; ?>/>
					</td>
				</tr>
				<tr>
					<td><label>*Numer tel. (9 cyfr):</label></td>
					<td>
						<input id='customerPhone'class="form-control" type="tel" name="customerPhone" pattern="[1-9]{1}[0-9]{8}" minlength="9" maxlength="9" required <?PHP if (isset($_SESSION['customerPhone'])): ?>value="<?=$_SESSION['customerPhone']?>"<?PHP unset ($_SESSION['customerPhone']); endif; ?>/>
					</td>
				</tr>
				<tr>
					<td><label>Adres:</label></td>
					<td>
						<textarea id="customerAddress" class="form-control" name="customerAddress" rows="3" cols="25" maxlength="250" form="customerAddingForm"><?PHP if (isset($_SESSION['customerAddress'])): ?><?=$_SESSION['customerAddress']?><?PHP unset ($_SESSION['customerAddress']);?><?PHP endif; ?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<button class="btn btn-default btn-block" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> Zapisz klienta</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					* - pola obowiązkowe
					</td>
				</tr>
			</table>
			</div>
	</form>
</div>