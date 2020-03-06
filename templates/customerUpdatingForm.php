<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Edycja danych klienta</div>
	<div class="tabContainer">
		<table class="table addingTab">
			<tr class="noTopBorder">
				<td><label>Imię:</label></td>
				<td>
					<div style="float:left;" id="name" class="text-capitalize"><?=$name?></div>
					<div class="text-right noPadding">
						<span onclick="updateName();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span>
					</div>
				</td>
			</tr>
			<tr>
				<td><label>Nazwisko (nazwa firmy):</label></td>
				<td>
					<div  id="surname" style="float: left;" class="text-capitalize"><?=$surname?></div>
					<div class="text-right noPadding">
						<span onclick="updateSurname();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span>
					</div>
				</td>
			</tr>
			<tr>
				<td><label>Numer telefonu:</label></td>
				<td><div id='phone' style="float: left;"><?=$phone?></div><div class="text-right noPadding"><span onclick="updatePhone();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div></td>
			</tr>
			<tr>
				<td><label>Adres:</label></td>
				<td><div id='address' style="float: left;"><?PHP if($address): ?><?=$address?><?PHP else:?>Brak<?PHP endif;?></div><div class="text-right noPadding"><span onclick="updateAddress();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div></td>
			</tr>
		</table>
	</div>
</div>
<div class="modal fade" id="updatingCustomerDataModal" role="dialog">
	<div class="modal-dialog">
		<div class="contentContainer">
			<div class="modal-content">
				<div class="modal-body" id='updatingCustomerDataModalBody'>
				</div>
			</div>
		</div>
	</div>
</div>