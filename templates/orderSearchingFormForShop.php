<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
<form action="index.php?action=findTheOrder" name="orderSearchingForm" id="orderSearchingForm" method="post" autocomplete="off">
	<div class="text-center header">Znajdź zlecenie: </div>
	<div class="tabContainer">
		<table class="table addingTab">
			<tr>
				<td><label> Nr dokumentu:</label></td>
				<td>
					<div class="row noMargin">
					<div class="col-sm-3 smallerPadding">
						<select id='documentTypeSelect' class="form-control text-center" name="documentType">
							<option value="PA"  
								<?PHP if ($documentType == 'PA'):?>
								selected
								<?PHP endif;?>>PA</option>
							<option value="FV"
								<?PHP if ($documentType == 'FV'):?>
								selected
								<?PHP endif;?>>FV</option>
							<option value="RV"
								<?PHP if ($documentType == 'RV'):?>
								selected
								<?PHP endif;?>>RV</option>
						</select>
						</div>
						<div class="col-sm-6 smallerPadding">
						<input id='documentNumberInput' class="form-control text-center" type="text" pattern="\d*" maxlength="6" name="documentNumber" value="<?=$documentNumber?>" />
						</div>
						<div class="col-sm-3 smallerPadding">
						<select class="form-control text-center" name="documentBranch">
							<option value="RZ" <?PHP if ($documentBranch == 'RZ'):?>selected<?PHP endif;?>>RZ</option>
							<option value="RA" <?PHP if ($documentBranch == 'RA'):?>selected<?PHP endif;?>>RA</option>
							<option value="KR" <?PHP if ($documentBranch == 'KR'):?>selected<?PHP endif;?>>KR</option>
							<option value="NS" <?PHP if ($documentBranch == 'NS'):?>selected<?PHP endif;?>>NS</option>
							<option value="5" <?PHP if ($documentBranch == '5'):?>selected<?PHP endif;?>>	</option>
						</select>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td><label>Nazwa klienta:</label></td>
				<td><input minlength="2"  id='customerName' class="form-control text-center" type="text" name="customerName" maxlength="25" value="<?=$customerName?>"/></td>
			</tr>
			<tr>
				<td><label>Numer telefonu:</label></td>
				<td>
					<input class="form-control text-center" type="tel" id='customerPhone' name="customerPhone" pattern="[1-9]{1}[0-9]{8}" minlength="9" maxlength="9" value="<?=$customerPhone?>" />
				</td>
			</tr>
			<tr><td colspan='2'><button class="btn btn-default btn-block" type="submit"><span class="glyphicon glyphicon-search"></span> Znajdź</button></td></tr>
		</table>
	</div>
</form>
</div>
	
