<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Edycja danych pracownika</div>
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
				<td><label>Nazwisko:</label></td>
				<td>
					<div  id="surname" style="float: left;" class="text-capitalize"><?=$surname?></div>
					<div class="text-right noPadding">
						<span onclick="updateSurname();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span>
					</div>
				</td>
			</tr>
			<tr>
				<td><label>Stanowiska:</label></td>
				<td><div id='stands' style="float: left;"><?=$stands?></div><span style="display: none;" id='standsIds'><?=$standsIds?></span><div class="text-right noPadding"><span onclick="updateStands();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div></td>
			</tr>
		</table>
	</div>
</div>
<div class="modal fade" id="updatingWorkerDataModal" role="dialog">
	<div class="modal-dialog">
		<div class="contentContainer">
			<div class="modal-content">
				<div class="modal-body" id='updatingWorkerDataModalBody'>
				</div>
			</div>
		</div>
	</div>
</div>