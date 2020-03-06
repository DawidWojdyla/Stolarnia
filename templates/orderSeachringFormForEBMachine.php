<?php if(!isset($this)) die(); ?>
<h3 class="text-center">Realizuj zlecenie: </h3>
<form action="index.php?action=findTheOrder" name="orderSearchingForm" id="orderSearchingForm" method="post" autocomplete="off">
	<table class="table table-condensed">
		<tr>
			<td>Dokument:</td>
			<td>
				<div class="fullWidth">
					<select id='documentTypeSelect' style='max-width: 51px; display: inline; padding:2px;' class="form-control" name="documentType">
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
					<input id='documentNumberInput' style='display: inline; width: 73px;' class="documentNumber form-control text-center" type="text" pattern="\d*" maxlength="6" name="documentNumber" value="<?=$documentNumber?>" required/>
					<select class="form-control" style='max-width: 51px; display: inline; padding:2px;'  name="documentBranch">
						<option value="RZ" <?PHP if ($documentBranch == 'RZ'):?>selected<?PHP endif;?>>RZ</option>
						<option value="RA" <?PHP if ($documentBranch == 'RA'):?>selected<?PHP endif;?>>RA</option>
						<option value="KR" <?PHP if ($documentBranch == 'KR'):?>selected<?PHP endif;?>>KR</option>
						<option value="NS" <?PHP if ($documentBranch == 'NS'):?>selected<?PHP endif;?>>NS</option>
						<option value="5" <?PHP if ($documentBranch == '5'):?>selected<?PHP endif;?>>	</option>
					</select>
				</div>
			</td>
		</tr>
		<tr><td colspan='2'><button class="btn btn-default btn-block" type="submit"><span class="glyphicon glyphicon-expand"></span> Realizuj</button></td></tr>
	</table>
</form>
<div style="margin-bottom: -30px;"></div>