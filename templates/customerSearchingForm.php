<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<form action="index.php?action=findTheCustomer" name="customerSearchingForm" id="customerSearchingForm" method="post" autocomplete="off">
		<div class="text-center header">Znajdź klienta: </div>
		<div class="tabContainer">
			<table class="table addingTab">
				<tr>
					<td><label>Imię:</label></td>
					<td><input minlength="2"  id='name' class="form-control text-center" type="text" name="name" maxlength="25" value="<?=$name?>"/></td>
				</tr>
				<tr>
					<td><label>Nazwisko (nazwa firmy):</label></td>
					<td><input minlength="2"  id='surname' class="form-control text-center" type="text" name="surname" maxlength="25" value="<?=$surname?>"/></td>
				</tr>
				<tr>
					<td><label>Numer telefonu:</label></td>
					<td>
						<input class="form-control text-center" type="tel" id='phone' name="phone" pattern="[1-9]{1}[0-9]{8}" minlength="9" maxlength="9" value="<?=$phone?>" />
					</td>
				</tr>
				<tr><td colspan='2'><button class="btn btn-default btn-block" type="submit"><span class="glyphicon glyphicon-search"></span> Znajdź</button></td></tr>
			</table>
		</div>
	</form>
</div>
	
